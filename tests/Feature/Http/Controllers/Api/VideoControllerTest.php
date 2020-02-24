<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Video;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Traits\TestSaves;
use Tests\Traits\TestValidations;

class VideoControllerTest extends TestCase
{
    use DatabaseMigrations;
    Use TestValidations;
    Use TestSaves;

    private $video;

    protected function setUp(): void
    {
        parent::setUp();
        $this->video = factory(Video::class)->create();
    }

    public function testIndex()
    {
        $response = $this->get(route('api.videos.index'));

        $response
            ->assertStatus(200)
            ->assertJson([$this->video->toArray()]);
    }

    public function testInvalidationRequired()
    {
        $data = [
            'title' => '',
            'description' => '',
            'year_launched' => '',
            'duration' => '',
            'rating' => ''
        ];
        $this->assertInvalidationInStoreAction($data, 'required');
        $this->assertInvalidationInUpdateAction($data, 'required');
    }

    public function testInvalidationMax()
    {
        $data = [
            'title' => str_repeat('a', 256),
        ];
        $this->assertInvalidationInStoreAction($data, 'max.string', ['max' => 255]);
        $this->assertInvalidationInUpdateAction($data, 'max.string', ['max' => 255]);
    }

    public function testInvalidationInteger()
    {
        $data = [
            'duration' => 'd',
        ];
        $this->assertInvalidationInStoreAction($data, 'max.string', 'integer');
        $this->assertInvalidationInUpdateAction($data, 'max.string', 'integer');
    }

    public function testInvalidationYearLaunchedField()
    {
        $data = [
            'year_launched' => 'Y',
        ];
        $this->assertInvalidationInStoreAction($data, 'date_format', ['format'=> 'Y']);
        $this->assertInvalidationInUpdateAction($data, 'date_format',['format'=> 'Y']);
    }

    public function testInvalidationBoolean()
    {
        $data = [
            'opened' => 'R'
        ];
        $this->assertInvalidationInStoreAction($data, 'boolean');
        $this->assertInvalidationInUpdateAction($data, 'boolean');
    }

    public function testInvalidationRatingField()
    {
        $data = [
            'rating' => 0
        ];
        $this->assertInvalidationInStoreAction($data, 'in');
        $this->assertInvalidationInUpdateAction($data, 'in');
    }

    public function testStore()
    {
        $data = [
            [
                'name' => 'test',
                'type' => Video::TYPE_DIRECTOR
            ],
            [
                'name' => 'test',
                'type' => Video::TYPE_ACTOR
            ]
        ];
        foreach ($data as $key => $value) {
            $response = $this->assertStore($value, $value + ['deleted_at' => null]);
            $response->assertJsonStructure([
                'created_at', 'updated_at'
            ]);
        }
    }

    public function testUpdate()
    {
        $data = [
            'name' => 'test',
            'type' => Video::TYPE_ACTOR
        ];
        $response = $this->assertUpdate($data, $data + ['deleted_at' => null]);
        $response->assertJsonStructure([
            'created_at', 'updated_at'
        ]);

    }

    public function testShow()
    {
        $response = $this->get(route('api.cast_members.show', ['cast_member' => $this->castMember->id]));
        $response
            ->assertStatus(200)
            ->assertJson($this->castMember->toArray());
    }

    public function testDestroy()
    {
        $response = $this->json('DELETE', route('api.cast_members.destroy', ['cast_member' => $this->castMember->id]));
        $response->assertStatus(204);
        $this->assertNull(Video::find($this->castMember->id));
        $this->assertNotNull(Video::withTrashed()->find($this->castMember->id));
    }

    protected function routeStore()
    {
        return route('api.videos.store');
    }

    protected function routeUpdate()
    {
        return route('api.videos.update', ['video' => $this->video->id]);
    }

    protected function model()
    {
        return Video::class;
    }
}
