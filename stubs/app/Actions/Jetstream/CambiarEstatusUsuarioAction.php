<?php

namespace Laravel\Jetstream\Actions;

use App\Enums\AccionEnum;
use App\Enums\RegistroTipoEnum;
use App\Models\Bitacora;
use App\Models\Usuario;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CambiarEstatusUsuarioAction
{
    public static function execute(int $idUsuario, $idUsuarioModificador)
    {
        try {
            return DB::transaction(function () use ($idUsuario, $idUsuarioModificador) {
                $usuario = Usuario::find($idUsuario);
                $usuario->activo = ! $usuario->activo;
                $usuario->save();

                DB::table('sessions')->where('user_id', $idUsuario)->delete();

                Bitacora::registrar(AccionEnum::CambiarEstatus, $idUsuarioModificador, $idUsuario, RegistroTipoEnum::Usuario);
                return $usuario;
            });
        } catch (Exception $e) {
            Log::error($e::class . ' > ' . $e->getFile() . '('.$e->getLine().'): ' . $e->getMessage());
            throw $e;
        }
    }
}
