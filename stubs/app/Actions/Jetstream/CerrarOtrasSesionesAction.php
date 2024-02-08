<?php

namespace Laravel\Jetstream\Actions;

use App\Enums\AccionEnum;
use App\Models\Bitacora;
use Exception;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CerrarOtrasSesionesAction
{
    public static function execute($password, StatefulGuard $guard, $idUsuario): int
    {
        try {
            return DB::transaction(function () use ($password, $guard, $idUsuario) {
                if (! Hash::check($password, Auth::user()->password)) {
                    throw ValidationException::withMessages([
                        'password' => [__('This password does not match our records.')],
                    ]);
                }

                $guard->logoutOtherDevices($password);

                $currentSessionId = session()->getId();
                DB::table('sessions')->where('id', '!=', $currentSessionId)->where('user_id', $idUsuario)->delete();

                request()->session()->put([
                    'password_hash_'.Auth::getDefaultDriver() => Auth::user()->getAuthPassword(),
                ]);
                Bitacora::registrar(AccionEnum::CierreSesion, $idUsuario, null);

                return $idUsuario;
            });
        } catch (Exception $e) {
            Log::error($e::class . ' > ' . $e->getFile() . '('.$e->getLine().'): ' . $e->getMessage());
            throw $e;
        }
    }
}
