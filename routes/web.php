<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');


Route::get('/logar', 'Auth\LoginController@showLoginForm')->name('logar');
Route::get('/registro', 'Auth\RegisterController@showRegisterForm')->name('registro');


Route::post('login', 'Auth\LoginController@login')->name('login');
Route::post('register', 'Auth\RegisterController@register')->name('register');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');


$this->group(['prefix' => '/usuario'], function() {
    $this->group(['middleware' => 'login'], function() {
        $this->get('/home', 'Auth\LoginController@showLoginForm')->name('home');
    });
    $this->get('/validade','UsuarioController@validar')->name('validade');
    $this->get('/validacao/email/{id?}', 'UsuarioController@verificacao');
    $this->post('/recuperasenha', 'Auth\LoginController@newPassword')->name('recuperasenha');
    $this->get('/esquecisenha', function () {
        return view('auth.passwords.email');
    })->name('senha');
});

