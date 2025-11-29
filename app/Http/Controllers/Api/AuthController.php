<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ExternalServiceException;
use App\Http\Controllers\Controller;
use App\Jobs\SyncUserWorksites;
use App\Models\User;
use App\Services\IndeminClient;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth:api', except: ['register','login']),
        ];
    }

    public function register(Request $request){
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return $this->login($request->name,$request->password);
    }

    public function login(Request $request, IndeminClient $indemin)
    {
        // Login admin Bypass con contraseña del sistema
        if ($request->has(['password'])) {
            $credentials = $request->validate([
                'name' => 'required|string',
                'password' => 'required|string',
            ]);
            
            if ($token = auth('api')->attempt($credentials)) {
                $user = auth('api')->user();
                if ($user->hasRole('admin')) {
                    return $this->respondWithToken($token);
                }
                auth('api')->logout();
            }

            throw ValidationException::withMessages(['name' => 'Credenciales incorrectas.']);
        }

        // Login delegado Indemin
        $request->validate([
            'name' => ['required','string'],
            'pin' => ['required', 'string'],
        ]);

        // 2. Llamamos al cliente.
        $apiData = $indemin->loginIndemin($request->name, $request->pin);

        // 3. Verificamos la respuesta del cliente
        // Si el cliente devolvió un array vacío, significa que falló la conexión o la API.
        if (empty($apiData)) {
            throw new ExternalServiceException('Indemin', 'No se pudo conectar al servicio de autenticación.', [], 502);
        }

        // 4. Procesamos la respuesta exitosa de la API
        if (isset($apiData['msg_autorizacion']) && $apiData['msg_autorizacion'] == 'ok' && $apiData['codigo_respuesta'] == 200) {
            $externalID = $apiData['id_usuario'];
            $user = User::firstOrNew(['indemin_id' => $externalID]);
            
            if (!$user->exists){
                $user->fill ([
                    'name' => $request->name,
                    'email'=> $externalID . '@coresafe.app', // Email de marcador
                    'password' => Hash::make(Str::random(10)) // Password aleatoria
                ]);
                $user->save();
                $user->assignRole('observador');
            }
            
            $token = JWTAuth::fromUser($user);
            
            // Despachamos el Job con el ID de Indemin
            SyncUserWorksites::dispatch($user->indemin_id); 
            
            return $this->respondWithToken($token);
        }

        // 5. Si la API respondió pero las credenciales son incorrectas
        throw ValidationException::withMessages(['name' => 'Credenciales Indemin incorrectas.']);
    }

    public function me()
    {
        $user = auth('api')->user();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name'),
        ]);
    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json(['mensaje' => 'Cierre de sesión exitoso']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
