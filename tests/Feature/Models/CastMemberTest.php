<?php

namespace Tests\Feature\Models;

use App\Models\CastMember;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CastMemberTest extends TestCase
{
    use DatabaseMigrations;

    public function testList()
    {
        factory(CastMember::class, 1)->create();
        $castMembers = CastMember::all();
        $this->assertCount(1, $castMembers);
        $castMemberKey = array_keys($castMembers->first()->getAttributes());
        $this->assertEqualsCanonicalizing(
            [
                'id',
                'name',
                'type',
                'created_at',
                'updated_at',
                'deleted_at'
            ],
            $castMemberKey);
    }

    public function testCreate()
    {
        $castMember = CastMember::create([
            'name' => 'teste diretor',
            'type' => CastMember::TYPE_DIRECTOR
        ]);
        $castMember->refresh();
        $this->assertEquals('teste diretor', $castMember->name);
        $this->assertEquals(CastMember::TYPE_DIRECTOR, $castMember->type);

        $castMember = CastMember::create([
            'name' => 'teste ator',
            'type' => CastMember::TYPE_ACTOR
        ]);
        $castMember->refresh();
        $this->assertEquals('teste ator', $castMember->name);
        $this->assertEquals(CastMember::TYPE_ACTOR, $castMember->type);

    }

    public function testUuidIsValid()
    {
        $castMember = CastMember::create([
            'name' => 'teste uuid valid',
            'type' => CastMember::TYPE_DIRECTOR
        ]);

        $uuid = $castMember->id;

        if (!is_string($uuid) || (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $uuid) !== 1)) {
            $result = false;
        } else {
            $result = true;
        }

        $this->assertTrue($result);


        $this->assertStringMatchesFormat('%x-%x-%x-%x-%x', $castMember->id);
    }

    public function testDelete()
    {
        $castMember = CastMember::create([
            'name' => 'Cast Member to delete',
            'type' => CastMember::TYPE_DIRECTOR
        ]);

        print_r($castMember->name);

        $this->assertEquals('Cast Member to delete', $castMember->name);
        $this->assertTrue($castMember->delete());
    }
}
