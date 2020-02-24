<?php

namespace Tests\Feature\Models;

use App\Models\Genre;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GenreUnitTest extends TestCase
{
    use DatabaseMigrations;

    public function testList()
    {
        factory(Genre::class, 1)->create();
        $genres = Genre::all();
        $this->assertCount(1, $genres);
        $genreKey = array_keys($genres->first()->getAttributes());
        // print_r($genreKey);
        $this->assertEqualsCanonicalizing(
            [
                'id',
                'name',
                'description',
                'is_active',
                'created_at',
                'updated_at',
                'deleted_at'
            ],
            $genreKey);
    }

    public function testCreate()
    {
        $genre = Genre::create([
            'name' => 'teste1000'
        ]);

        $genre->refresh();

        $this->assertEquals('teste1000', $genre->name);
        $this->assertNull($genre->description);
        $this->assertTrue($genre->is_active);

        $genre = Genre::create([
            'name' => 'teste1001',
            'description' => null
        ]);
        $this->assertNull($genre->description);

        $genre = Genre::create([
            'name' => 'teste1001',
            'description' => 'description test'
        ]);
        $this->assertEquals('description test', $genre->description);

        // ----------------
        // is_active test
        // ----------------
        $genre = Genre::create([
            'name' => 'teste1001',
            'is_active' => false
        ]);
        $this->assertFalse($genre->is_active);

        $genre = Genre::create([
            'name' => 'teste1001',
            'is_active' => true
        ]);
        $this->assertTrue($genre->is_active);
    }


    public function testUuidIsValid()
    {
        $genre = Genre::create([
            'name' => 'teste uuid valid'
        ]);

        $uuid = $genre->id;

        if (!is_string($uuid) || (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $uuid) !== 1)) {
            $result = false;
        } else {
            $result = true;
        }

        $this->assertTrue($result);

        $this->assertStringMatchesFormat('%x-%x-%x-%x-%x',$genre->id);
    }


    public function testDelete()
    {
        $genre = Genre::create([
            'name' => 'Genre to delete'
        ]);

        print_r($genre->name);

        $this->assertEquals('Genre to delete',$genre->name);
        $this->assertTrue($genre->delete());
    }

}
