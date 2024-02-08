<?php

namespace Laravel\Jetstream\Actions;

use App\Enums\AccionEnum;
use App\Models\Bitacora;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CerrarSesionActualAction
{
    public static function execute($idUsuario): int
    {
        try {
            return DB::transaction(function () use ($idUsuario) {
                Auth::guard('web')->logout();
                Bitacora::registrar(AccionEnum::CierreSesion, $idUsuario, null);

                return $idUsuario;
            });
        } catch (Exception $e) {
            Log::error($e::class . ' > ' . $e->getFile() . '('.$e->getLine().'): ' . $e->getMessage());
            throw $e;
        }
    }
}
