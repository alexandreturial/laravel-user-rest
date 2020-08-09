<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{

    public function validar(array $data)
    {
       
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|',
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $user = User::where('id', session()->all()['id'])->first();
    
        return view('user.index', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
       
        $crendentials = $this->validate(request(),[
            'id' => 'required|int|exists:TB_USUARIO,id',
            'DS_NOME' => 'required|string|max:255',
            'DS_EMAIL' => 'required|string|email|max:255|email:rfc,dns',
            'DS_SENHA' => 'string|max:255|nullable|confirmed|min:6',
            
        ]);
      
        $credentials = array_filter($crendentials);
        
        if(array_key_exists('DS_SENHA', $credentials)){
           
            $credentials['DS_SENHA'] = Hash::make($credentials['DS_SENHA']);
            
        }
        
        try{
            User::Where('id',$crendentials['id'])->update($credentials);
        }catch(\Exception $e){
            return 'Não foi possível atualizar o usuário';
        }
        return redirect()->route('perfil');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
