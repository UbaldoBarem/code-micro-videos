<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Genre;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GenreControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testIndex()
    {
        $genre = factory(Genre::class)->create();
        $response = $this->get(route('api.genres.index'));

        $response
            ->assertStatus(200)
            ->assertJson([$genre->toArray()]);
    }

    public function testShow()
    {
        $genre = factory(Genre::class)->create();
        $response = $this->get(route('api.genres.show', ['genre' => $genre->id]));

        $response
            ->assertStatus(200)
            ->assertJson($genre->toArray());
    }

    public function testInvalidationData()
    {
        $response = $this->json('POST', route('api.genres.store'), []);
        $this->assertInvalidationRequired($response);

        $response = $this->json('POST', route('api.genres.store'), [
            'name' => str_repeat('a', 256),
            'is_active' => 'a'
        ]);
        $this->assertInvalidationMax($response);
        $this->assertInvalidationBoolean($response);


        $genre = factory(Genre::class)->create();
        $response = $this->json('PUT', route('api.genres.update', ['genre' => $genre->id]), []);
        $this->assertInvalidationRequired($response);

        $response = $this->json('PUT', route('api.genres.update', ['genre' => $genre->id]),
            [
                'name' => str_repeat('a', 256),
                'is_active' => 'a'
            ]
        );
        $this->assertInvalidationMax($response);
        $this->assertInvalidationBoolean($response);

    }

    protected function assertInvalidationRequired(TestResponse $response)
    {
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name'])
            ->assertJsonMissingValidationErrors(['is_active'])
            ->assertJsonFragment([
                \Lang::get('validation.required', ['attribute' => 'name'])
            ]);
    }

    protected function assertInvalidationMax(TestResponse $response)
    {
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name'])
            ->assertJsonFragment([
                \Lang::get('validation.max.string', ['attribute' => 'name', 'max' => 255])
            ]);
    }

    protected function assertInvalidationBoolean(TestResponse $response)
    {
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['is_active'])
            ->assertJsonFragment([
                \Lang::get('validation.boolean', ['attribute' => 'is active'])
            ]);
    }

    public function testStore()
    {
        $response = $this->json('POST', route('api.genres.store'), [
            'name' => 'test'
        ]);

        $id = $response->json('id');
        $genre = Genre::find($id);

        $response
            ->assertStatus(201)
            ->assertJson($genre->toArray());

        $this->assertTrue($response->json('is_active'));
        $this->assertNull($response->json('description'));

        $response = $this->json('POST', route('api.genres.store'), [
            'name' => 'test',
            'description' => 'description test',
            'is_active' => false

        ]);

        $response
            ->assertJsonFragment([
                'description' => 'description test',
                'is_active' => false
            ]);
    }

    public function testUpdate()
    {
        $genre = factory(Genre::class)->create(
            [
                'description' => 'description',
                'is_active' => false
            ]
        );
        $response = $this->json('PUT', route('api.genres.update', ['genre' => $genre->id]),
            [
                'name' => 'test',
                'description' => 'test',
                'is_active' => true
            ]
        );
        $id = $response->json('id');
        $genre = Genre::find($id);
        $response
            ->assertStatus(200)
            ->assertJson($genre->toArray())
            ->assertJsonFragment([
                'description' => 'test',
                'is_active' => true
            ]);

        $response = $this->json(
            'PUT',
            route('api.genres.update', ['genre' => $genre->id]),
            [
                'name' => 'test',
                'description' => '',
                'is_active' => true
            ]
        );
        $response
            ->assertJsonFragment([
                'description' => null
            ]);

    }

    public function testDelete()
    {
        $response = $this->json('POST', route('api.genres.store'), [
            'name' => 'genre to delete'
        ]);

        $id = $response->json('id');
        $genre = Genre::find($id);

        $response = $this->json('DELETE', route('api.genres.destroy', ['genre' => $genre->id]));
        $response->assertStatus(204);

        $response = $this->json('GET', route('api.genres.show', ['genre' => $genre->id]));
        $response->assertStatus(404);

    }
}
