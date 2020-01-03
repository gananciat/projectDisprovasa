<?php

namespace App\Http\Controllers\Usuario;

use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;

class AuthController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth:api')->except(['login','loginEscuela']);
    }

	//iniciar session
    public function login(Request $request)
    {
        $request->validate([
            'email'       => 'required|string|email',
            'password'    => 'required|string',
        ]);

        $user = User::where('email',$request->email)->first();

        if(!is_null($user) && $user->current_school){
            return response()->json([
                'error' => 'Usuario no registrado','code'=>'401'], 401);
        }


        $credentials = request(['email', 'password']);

        $http = new Client();

        $response = $http->post(config('app.server_auth').'/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => config('services.passport.client_id'),
                'client_secret' => config('services.passport.client_secret'),
                'username' => $request->email,
                'password' => $request->password,
                'scope' => '*',
            ],
        ]);

        //return json_decode((string) $response->getBody(), true);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'error' => 'Usuario o contraseña incorrectos','code'=>'401'], 401);
        }

        $user = $request->user();

        $tokenResult = $user->createToken('token_project');

        $token = $tokenResult->token;

        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'usuario' => $request->user(),
            'token_type'   => 'Bearer',
            'expires_at'   => Carbon::parse(
                $tokenResult->token->expires_at)
                    ->toDateTimeString(),
        ]);
    }

    //iniciar session
    public function loginEscuela(Request $request)
    {
        $request->validate([
            'email'       => 'required|string|email',
            'password'    => 'required|string',
        ]);

        $user = User::where('email',$request->email)->first();

        if(!is_null($user) && !$user->current_school){
            return response()->json([
                'error' => 'Usuario no registrado','code'=>'401'], 401);
        }


        $credentials = request(['email', 'password']);

        $http = new Client();

        $response = $http->post(config('app.server_auth').'/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => config('services.passport.client_id'),
                'client_secret' => config('services.passport.client_secret'),
                'username' => $request->email,
                'password' => $request->password,
                'scope' => '*',
            ],
        ]);

        //return json_decode((string) $response->getBody(), true);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Usuario o contraseña incorrectos'], 401);
        }

        $user = $request->user();

        $tokenResult = $user->createToken('token_escuela');

        $token = $tokenResult->token;

        $token->save();


        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'usuario' => $request->user(),
            'token_type'   => 'Bearer',
            'expires_at'   => Carbon::parse(
                $tokenResult->token->expires_at)
                    ->toDateTimeString(),
        ]);
    }

    //cerrar session
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json(['message' => 
            'saliendo...']);
    }

    //obtener usuario logueado
    public function user(Request $request)
    {
        $user = $request->user();

        $people = $user->people;

        return response()->json($user);
    }

    //obtener usuario logueado
    public function userEschool(Request $request)
    {
        $user = $request->user();

        $people = $user->people;
        $school = $people->current_school()->with('school')->first();

        return response()->json([
            'user' => $user,
            'people' => $people,
            'school' => $school
        ]);
    }
}
