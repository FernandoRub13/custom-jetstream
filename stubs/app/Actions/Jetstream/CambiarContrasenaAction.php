<?php

namespace Laravel\Jetstream\Actions;

use App\Enums\AccionEnum;
use App\Livewire\Forms\Usuarios\CambiarContrasenaForm;
use App\Models\Bitacora;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;

class CambiarContrasenaAction
{
    public static function execute(UpdatesUserPasswords $updater, CambiarContrasenaForm $cambiarContrasenaForm, Authenticatable $user)
    {
        $cambiarContrasenaForm->validate();
        $user->forceFill([
            'password' => Hash::make($cambiarContrasenaForm->password),
        ])->save();

        if (request()->hasSession()) {
            request()->session()->put([
                'password_hash_'.Auth::getDefaultDriver() => $user->getAuthPassword(),
            ]);
        }

        Bitacora::registrar(AccionEnum::CambioContrasena, $user->id_usuario, null);
    }
}
