<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Password;
use App\User;
use Firebase\JWT\JWT;
use App\Helpers\Token;

class PasswordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request_token = $request->header('Authorization');
        $token = new token();
        $decoded_email = $token->decode($request_token);

        $email = $decoded_email->email;
        $user_email = ['email' => $email];

        $user = User::where('email', '=', $user_email)->first();
        $user_id = $user->id;

        $category_requested = Category::where('name', '=', $request->category)
            ->where('user_id', '=', $user->id)
            ->first();

        if ($category_requested == NULL)
        {
            return response()->json([
                "message" => 'Tienes que crear primero la categoría'
            ], 401);
        }

        //$decoded_category = ['name' => $request->category];
        //$category = Category::where($decoded_category)->first();

        $category_id_requested = $category_requested->id;
        
         $password = new Password();
         $password->title = $request->title;
         $password->password = $request->password;
         $password->category_id = $category_id_requested;
         $password->save();

         return response()->json([
            "Password" => 'Password creada'
         ],200);
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
    public function update(Request $request, $id)
    {
        $request_token = $request->header('Authorization');
        $token = new token();
        $decoded_token = $token->decode($request_token);

        $user_email = $decoded_token->email;
        $user = User::where('email', '=', $user_email)->first();
        $user_id = $user->id;

        $password = Password::where('id', '=', $id)->first();

        $category_id_to_password = $password->category_id;
        $category_from_password = Category::where('id', '=', $category_id_to_password)->first();

        $user_from_category = $category_from_password->user_id;

        if($user_id!=$user_from_category)
        {
            return response()->json([
                "message" => 'Solo puedes editar tus contraseñas'
            ], 401);
        }

        if($request->title==NULL || $request->password==NULL)
        {
            return response()->json([
                "message" => 'Rellena todos los campos'
            ], 401);
        }

        $password->title = $request->title;
        $password->password = $request->password;
        $password->save();

        return response()->json([
            "message" => 'Contraseña actualizada'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $password = Password::find($id);
        $password->delete();
    }

    public function show_passwords(Request $request)
    {
        $request_token = $request->header('Authorization');
        $token = new token();
        $decoded_token = $token->decode($request_token);

        $user_email = $decoded_token->email;
        $user = User::where('email', '=', $user_email)->first();


        return response()->json([
            $user->passwords
        ], 200);
        
    }
}
