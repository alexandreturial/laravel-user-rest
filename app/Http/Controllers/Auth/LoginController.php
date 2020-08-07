<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\PasswordResetRequest;
use Auth;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Route;

//use Auth;
//use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest', ['only'=> 'showLoginForm']);
    }

    public function showLoginForm(){
        if(key_exists('email',session()->all())){
            return view('home');
        }else{
            return view('auth.login');
        }

    }
    //use AuthenticatesUsers;

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function login(){

        $crendentials = $this->validate(request(),[
            'email' => 'email|required|string',
            'password' => 'required|string',
        ]);

        // Get user by email
        $company = User::where('DS_EMAIL', $crendentials['email'])->first();
        //  dd($company);
        // Validate Company
        if(!$company) {
            return back()->withErrors([
                'email' => 'email ou senha errados'
            ])->withInput(\request(['email']));
        }

        if (Auth::attempt ( array (
            'DS_EMAIL' => $crendentials['email'],
            'password' =>  $crendentials['password']
        ) )) {
            session()->flush(); // Removes a specific variable
            session ( [
                'email' => $crendentials['email']
            ] );
            return view('home');
        } else {
            return back()->withErrors([
                'email' => 'email ou senha errados'
            ])->withInput(\request(['email']));
        }
    }

    public function logout() {


        Session()->flush();
        Auth()->logout();

       // dd(key_exists('email',session()->all()));
        return Redirect()->route('welcome');
    }

    public function newPassword(Request $json){
        $currentPath= Route::getFacadeRoot()->current()->uri();
        $pattern = '/' . 'api' . '/';
        if (preg_match($pattern, $currentPath)) {
            $request = json_decode(json_encode($json['request'],true));
        }else{
            $request = $json->request;
        }
        foreach ($request as $item){
            $email = $item;
        }

        $user = User::where('DS_EMAIL', $email)->first();

        if (!$user)
            return response()->json([
                'message' => 'We cant find a user with that e-mail address.'], 404);

        if ($user) {

            $array= [
                'senha' => substr(md5(rand(600000 , 12000000)), 0,8),
                'email' => $email
            ];

            $invitedUser = new User;
            $invitedUser->email = $email;

            $invitedUser->notify(
                new PasswordResetRequest($user, $array)
            );

            User::updatesenha($array);
            // Usuario::token($passwordReset->remember_token);

            $currentPath= Route::getFacadeRoot()->current()->uri();
            $pattern = '/' . 'api' . '/';

            if (preg_match($pattern, $currentPath)) {
                $success = [
                    'successMessage' => 'Email enviado com secesso!'
                ];

                return response()->json(compact('success'));
            }else{
                $senha = 1;

                //$categorias = Produto::listAll();
                $show = 3;
                //return redirect()->back()->with([$senha,$categoria,$show]);
              return "OK";
            }

        }
    }
}
