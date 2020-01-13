<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Mail\UserCreated;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credentials')->only(['store', 'resend']);
        $this->middleware('auth:api')->except(['store', 'resend', 'verify']);
    }

    public function index()
    {
        $usuarios = User::all();
        return $this->showAll($usuarios);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ];

        $this->validate($request, $rules);

        $campos = $request->all();

        $campos['password'] = bcrypt($request->password);
        $campos['verified'] = User::USUARIO_NO_VERIFICADO;
        $campos['verification_token'] = User::generarVerificationToken();
        $campos['admin'] = User::USUARIO_REGULAR;

        $usuario = User::create($campos);

        return $this->showOne($usuario, 201);
    }

    public function show(User $user)
    {
        return $this->showOne($user);
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'email' => 'email|unique:users,email,'.$user->id,
            'password' => 'min:6|confirmed',
            'admin' => 'in:'. User::USUARIO_ADMINISTRADOR .','.User::USUARIO_REGULAR
        ];

        $this->validate($request, $rules);     
        
        if($request->has('email') && $user->email != $request->email)
        {
            $user->verified = User::USUARIO_NO_VERIFICADO;
            $user->verification_token = User::generarVerificationToken();
            $user->email = $request->email;
        }

        if($request->has('admin'))
        {
            if(!$user->esVerificado())
            {
                return $this->errorResponse('Unicamente los usuarios verificados', 409);
            }

            $user->admin = $request->admin;
        }

        if(!$user->isDirty())
        {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $user->save();

        return $this->showOne($user);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return $this->showOne($user);
    }

    public function verify($token) 
    {
        $user = User::where('verification_token', $token)->firstOrFail();

        $user->verified = User::USUARIO_VERIFICADO;
        $user->verification_token = null;

        $user->save();
    }

    public function resend(User $user)
    {
        if ($user->esVerificado())
            return $this->errorResponse('Este usuario ya ha sido verificado', 409);

        retry(5, function() use ($user){
            Mail::to($user)->send(new UserCreated);
        }, 100);
    }
}
