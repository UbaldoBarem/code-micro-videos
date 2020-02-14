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
        $categoryKey = $categories->first()->getAttributes;
        print_r($categoryKey);
        $this->assertEqualsCanonicalizing([
            'id',
            'name',
            'description',
            'is_active',
            'created_at',
            'updated_at',
            'deleted_at'
        ], $categoryKey);
    }

}
