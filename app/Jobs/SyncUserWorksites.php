<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\IndeminClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncUserWorksites implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $userId;
    public int $tries = 3;
    public int $timeout = 30;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

   public function handle(IndeminClient $indemin)
    {
        $user = User::where('indemin_id', $this->userId)->first();
        
        if (! $user) {
            Log::warning("SyncUserWorksites: user {$this->userId} not found");
            return;
        }

        try {
            $resp = $indemin->faenasGet($this->userId);
        } catch (\Exception $e) {
            Log::error("SyncUserWorksites: api error", ['user' => $this->userId, 'error' => $e->getMessage()]);
            return;
        }

        // soporta tanto ['raw'=>..., 'datos'=>...] como la respuesta directa
        $items = data_get($resp, 'datos', $resp['datos'] ?? $resp);

        if (empty($items) || ! is_array($items)) {
            Log::info("SyncUserWorksites: no hay datos para user {$this->userId}", [
                'cantidad_datos' => data_get($resp, 'cantidad_datos'),
                'resp_keys' => is_array($resp) ? array_keys($resp) : null,
            ]);
            // opcional: detach all si se desea borrar relaciones cuando no haya datos
            // $user->worksites()->sync([]);
            return;
        }

        $ids = collect($items)
            ->map(function ($it) {
                $row = is_array($it) ? $it : (is_object($it) ? (array) $it : []);
                return $row['id_faena'] ?? null;
            })
            ->filter()
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values()
            ->all();
        
        // modificar fecha de sincronización
        $syncData = [];
        $fechaSync = now();
        foreach ($ids as $id) {
            $syncData[$id] = ['last_synced_at'=>$fechaSync];
        }

        $user->worksites()->sync($syncData);

        Log::info("SyncUserWorksites: synced user {$this->userId} -> " . count($ids) . " worksites", [
            'sample_items' => array_slice($items, 0, 3),
            'ids' => $ids,
        ]);
    }
}
