<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\User;
use App\Helpers\Token;
use Firebase\JWT\JWT;

class CategoryController extends Controller
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

        $category_requested = Category::where('name', '=', $request->name)
            ->where('user_id', '=', $user->id)
            ->first();

        if ($category_requested != NULL)
        {
            return response()->json([
                "message" => 'Error, tienes una categoría con el mismo nombre creada'
            ], 401);
        }

         $category = new Category();
         $category->name = $request->name;
         $category->user_id = $user->id;
         $category->save();

        return response()->json([
            "name" => $category->name,
        ], 200);
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

        $category = Category::where('id', '=', $id)->first();

        $user_id_of_category = $category->user_id;

        if($user_id!=$user_id_of_category)
        {
            return response()->json([
                "message" => 'Solo puedes editar tus categorias'
            ], 401);
        }

        if($request->name==NULL)
        {
            return response()->json([
                "message" => 'Rellena todos los campos'
            ], 401);
        }

        $category->name = $request->name;
        $category->save();

        return response()->json([
            "message" => 'Categoría actualizada'
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
        $category = Category::find($id);
        $category->delete();
    }

    public function show_categories(Request $request)
    {
        $request_token = $request->header('Authorization');
        $token = new token();
        $decoded_token = $token->decode($request_token);

        $user_email = $decoded_token->email;
        $user = User::where('email', '=', $user_email)->first();


        return response()->json([
            $user->categories,
        ], 200);
        
    }

}
