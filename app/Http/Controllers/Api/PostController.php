<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Post;
use App\Http\Requests\Post as PostRequets;

class PostController extends Controller
{
    protected $posts;
    
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        // Si no colocamos un status HTTP, siempre mandará 200 OK.
        return response()->json($this->post->paginate(), 200);

        /*
         Verificamos la respuesta y vemos la ventaja de poner esa estructura json en la prueba test_index
         Usando el método dd()
         return dd($this->post->paginate()); 
         */
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequets $request)
    {
        $post = $this->post->create($request->all());

        return response()->json($post, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return response()->json($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequets $request, Post $post)
    {
        $post->update($request->all());

        return response()->json($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return response()->json(null, 204); //Puedes retornar un array con un mensaje en vez de null.
    }
}
