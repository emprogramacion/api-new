<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_store()
    {

        //$this->withoutExceptionHandLing(); //--> método para ver claramente los errores pruebas vs códigos 

        //Construir un dato JSON
        $response = $this->json('POST', '/api/posts', [   
            'title' => 'El post de prueba'
        ]);

        $response->assertJsonStructure(['id', 'title', 'created_at', 'updated_at'])
            ->assertJson(['title' => 'El post de prueba'])
            ->assertStatus(201); //OK, creado un recurso en BD

        $this->assertDatabaseHas('posts', ['title' => 'El post de prueba']); //Revisar en la BD esta información.
    }

    public function test_validate_title()
    {
        $response = $this->json('POST', 'api/posts', [
            'title' => ''
        ]);

        //Estatus HTTP 422
        $response -> assertStatus(422)
            ->assertJsonValidationErrors('title');
    }

    public function test_show()
    {
        $post = factory(Post::class)->create(); //Se creará un post, se creará el post de id = 1.

        $response = $this->json('GET', "/api/posts/$post->id");

        $response->assertJsonStructure(['id', 'title', 'created_at', 'updated_at'])
            ->assertJson(['title' => $post->title])
            ->assertStatus(200); //OK, creado un recurso en BD
    }

    public function test_404_show()
    {
        $response = $this->json('GET', '/api/posts/1000');// Se colocan comillas simples si no usamos variables.

        $response->assertStatus(404); //No existe el post
    }

    public function test_update()
    {

        //$this->withoutExceptionHandLing(); //--> método para ver claramente los errores pruebas vs códigos 
        $post = factory(Post::class)->create(); //Se creará un post.

        $response = $this->json('PUT', "/api/posts/$post->id", [   
            'title' => 'Nuevo'
        ]);

        $response->assertJsonStructure(['id', 'title', 'created_at', 'updated_at'])
            ->assertJson(['title' => 'Nuevo'])
            ->assertStatus(200); //OK

        $this->assertDatabaseHas('posts', ['title' => 'Nuevo']); //Revisar en la BD esta información.
    }

    public function test_delete()
    {

        //$this->withoutExceptionHandLing(); //--> Esta linea nos ayuda a verificar si tenemos un error.
        $post = factory(Post::class)->create(); //Se creará un post.

        $response = $this->json('DELETE', "/api/posts/$post->id");

        $response->assertSee(null)
            ->assertStatus(204); //Sin contenido...

        $this->assertDatabaseMissing('posts', ['id' => $post->id]); //Revisar en la BD que no existe esta información.
    }

    public function test_index(){

        factory(Post::class, 5)->create();

        $response = $this->json('GET', "/api/posts");

        $response->assertJsonStructure([
            'data' => [
                '*' => ['id','title','created_at','updated_at']
            ]
        ])->assertStatus(200);
    }
}
