<?php

namespace Laravel\Jetstream\Actions;
use App\Enums\AccionEnum;
use App\Models\Bitacora;
use App\Models\Usuario;
use App\Rules\ReCaptcha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AutenticarUsuarioAction
{
    /**
     * @throws ValidationException
     */
    public function execute(Request $request): Usuario | null
    {
        $validator = Validator::make($request->all(), [
            'g-recaptcha-response' => [new ReCaptcha()],
        ]);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        $usuario = Usuario::where('email', $request->email)->first();

        if ($usuario && $usuario->activo &&
            Hash::check($request->password, $usuario->password)) {
            Bitacora::registrar(AccionEnum::InicioSesion, $usuario->id_usuario, null);
            return $usuario;
        }
        if ($usuario && ! $usuario->activo) {
            $validator->errors()->add('email', 'Su cuenta de usuario ha sido inhabilitada. Le sugerimos ponerse en contacto con el administrador.');
            throw new ValidationException($validator);
        }
        return null;
    }
}
