<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;

    public function testList()
    {
        factory(Category::class, 1)->create();
        $categories = Category::all();
        $this->assertCount(1, $categories);
        $categoryKey = array_keys($categories->first()->getAttributes());
        // print_r($categoryKey);
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
            $categoryKey);
    }

    public function testCreate()
    {
        $category = Category::create([
            'name' => 'teste1000'
        ]);

        $category->refresh();

        $this->assertEquals('teste1000',$category->name);
        $this->assertNull($category->description);
        $this->assertTrue($category->is_active);

        $category = Category::create([
            'name' => 'teste1001',
            'description' => null
        ]);
        $this->assertNull($category->description);

        $category = Category::create([
            'name' => 'teste1001',
            'description' => 'description test'
        ]);
        $this->assertEquals('description test',$category->description);

        // ----------------
        // is_active test
        // ----------------
        $category = Category::create([
            'name' => 'teste1001',
            'is_active' => false
        ]);
        $this->assertFalse($category->is_active);

        $category = Category::create([
            'name' => 'teste1001',
            'is_active' => true
        ]);
        $this->assertTrue($category->is_active);
    }

    public function testUuidIsValid()
    {
        $category = Category::create([
            'name' => 'teste uuid valid'
        ]);

        $uuid = $category->id;

        if (!is_string($uuid) || (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $uuid) !== 1)) {
            $result = false;
        } else {
            $result = true;
        }

        $this->assertTrue($result);

        // $this->assertStringMatchesFormat('%x-%x-%x-%x-%x',$category->id);
    }

}
