<?php
namespace App\Services;

use App\Exceptions\ExternalServiceException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IndeminClient
{
    protected string $apiUrl;
    protected array $headers;
    protected bool $verify;

    public function __construct()
    {
        // Configuración centralizada en config/services.php
        $this->apiUrl = config('services.indemin.url');

        $apiUser = config('services.indemin.user');
        $apiPass = config('services.indemin.pass');
        $authStr = base64_encode(trim("{$apiUser} {$apiPass}"));

        $this->headers = [
            'Content-Type' => 'application/json',
            'Authorization' => $authStr,
        ];

        // Flag  para desactivar verificación SSL si el certificado está vencido
        $this->verify = filter_var(config('services.indemin.verify_ssl'), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Cargamos los datos de las faenas del usuario con la API de Indemin.
     * Devuelve respuesta JSON con las faenas del usuario.
     */
    public function faenasGet(int $idUser): array
    {
        if (empty($this->apiUrl)) {
            Log::error('IndeminClient: apiUrl no configurada');
            return [];
        }

        try {
            $response = Http::withOptions(['verify' => $this->verify])
                ->withHeaders($this->headers)
                ->timeout(15)
                ->post("{$this->apiUrl}/movil_faenas_usuario_prod001.php", ['id_usuario' => $idUser]);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('IndeminClient: connection error', ['message' => $e->getMessage()]);
            return [];
        }

        if ($response->failed()) {
            Log::error('IndeminClient: remote failed', ['status' => $response->status(), 'body' => $response->body()]);
            return [];
        }

        $json = $response->json() ?? [];

        // registrar estructura para verificar campo "datos"
        Log::info('IndeminClient.faenasGet response keys', ['keys' => array_keys($json ?: [])]);

        // el campo datos contiene las faenas del usuario
        return [
            'raw' => $json,
            'datos' => $json['datos'] ?? [],
        ];
    }

    /**
     * Autentica a un usuario con la API de Indemin.
     * Devuelve el cuerpo de la respuesta JSON o lanza una ExternalServiceException en caso de error.
     */
    public function loginIndemin(string $usuario, string $pin): array
    {
        if (empty($this->apiUrl)) {
            Log::error('IndeminClient (login): apiUrl no configurada');
            // Lanzamos una excepción si la URL no está configurada
            throw new ExternalServiceException(
                'Indemin', 
                'Configuración interna del servicio Indemin incompleta.', 
                ['error' => 'INDEMIN_API_URL no está definida'], 
                500
            );
        }

        $endpoint = "{$this->apiUrl}/movil_login_prod001.php";
        $data = [
            'usuario' => $usuario,
            'pin' => $pin,
        ];

        try {
            $response = Http::withOptions(['verify' => $this->verify])
                ->withHeaders($this->headers)
                ->timeout(15)
                ->post($endpoint, $data);

        } catch (ConnectionException $e) {
            throw new ExternalServiceException(
                'Indemin',
                'No se pudo conectar al servicio de autenticación.',
                ['message' => $e->getMessage()],
                502, // 502 Bad Gateway
                $e
            );
        }

        if ($response->failed()) {
            throw new ExternalServiceException(
                'Indemin',
                'El servicio externo de autenticación devolvió un error.',
                [
                    'status' => $response->status(),
                    'body' => $response->json() ?? $response->body()
                ],
                $response->status()
            );
        }

        // Si todo sale bien, devolvemos la respuesta JSON como un array
        return $response->json() ?? [];
    }
}