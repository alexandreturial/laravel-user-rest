<?php

namespace App\Http\Controllers\Auth;

use App\Notifications\EmailValidade;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    public function showRegisterForm(){
        if(key_exists('email',session()->all())){
            return view('home');
        }else{
            return view('auth.register');
        }

    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {

        $userLaravel =  User::insert([
            'DS_NOME' => $data['name'],
            'DS_EMAIL' => $data['email'],
            'DS_SENHA' => Hash::make($data['password']),
        ]);


        $userLaravel = User::where('DS_EMAIL',$data['email'])->first();

        //EMAIL DE VALIDACAO
        if ($userLaravel) {

            $invitedUser = new User;
            $invitedUser->email = $data['email'];

            $invitedUser->notify(
                new EmailValidade($userLaravel)
            );
            $success = [
                'successId' => 200,
                'successMessage' => 'Usuário cadastrado com sucesso!'
            ];
            $response = [
                'success' => $success
            ];

        }
        return $userLaravel;
    }
}
