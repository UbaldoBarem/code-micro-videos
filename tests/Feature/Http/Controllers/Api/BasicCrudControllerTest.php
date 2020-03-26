<?php
declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Tests\Stubs\Controllers\CategoryControllerStub;
use Tests\Stubs\Models\CategoryStub;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Traits\TestSaves;
use Tests\Traits\TestValidations;
use App\Http\Controllers\Api\BasicCrudController;

class BasicCrudControllerTest extends TestCase
{
    private $controller;

    protected function setUp(): void
    {
        parent::setUp();
        CategoryStub::dropTable();
        CategoryStub::creteTable();
        $this->controller = new CategoryControllerStub();
    }

    protected function tearDown(): void
    {
        CategoryStub::dropTable();
        parent::tearDown();
    }

    public function testIndex()
    {
        /** @var CategoryStub $category */
        $category = CategoryStub::create(['name' => 'test_name', 'description' => 'test_description']);
        // $controller = new CategoryControllerStub();
        $resource = $this->controller->index();
        $serialized = $resource->response()->getData(true);

        $this->assertEquals(
            [$category->toArray()],
            $serialized['data']
        );
        $this->assertArrayHasKey('meta',$serialized);
        $this->assertArrayHasKey('links',$serialized);
    }

    public function testInvalidationDataInStore()
    {
        $this->expectException(ValidationException::class);

        $request = \Mockery::mock(Request::class);
        $request
            ->shouldReceive('all')
            ->once()
            ->andReturn(['name' => '']);
        $this->controller->store($request);
    }

    public function testStore()
    {
        $request = \Mockery::mock(Request::class);
        $request
            ->shouldReceive('all')
            ->once()
            ->andReturn(['name' => 'test_name', 'description' => 'test_description']);
        $resource = $this->controller->store($request);
        $serialized = $resource->response()->getData(true);
        $this->assertEquals(CategoryStub::first()->toarray(), $serialized['data']);
    }

    public function testIfFindOrFailFetchModel()
    {
        /** @var CategoryStub $category */
        $category = CategoryStub::create(['name' => 'test_name', 'description' => 'test_description']);

        $reflectionClass = new \ReflectionClass(BasicCrudController::class);
        $reflectionMethod = $reflectionClass->getMethod('findOrFail');
        $reflectionMethod->setAccessible(true);

        $resource = $reflectionMethod->invokeArgs($this->controller, [$category->id]);
        $this->assertInstanceOf(CategoryStub::class, $resource);
    }

    public function testIfFindOrFailThrowExceptionWhenIdInvalid()
    {
        $this->expectException(ModelNotFoundException::class);

        $reflectionClass = new \ReflectionClass(BasicCrudController::class);
        $reflectionMethod = $reflectionClass->getMethod('findOrFail');
        $reflectionMethod->setAccessible(true);

        $resource = $reflectionMethod->invokeArgs($this->controller, [0]);
        return $resource;
    }

    public function testShow()
    {
        $category = CategoryStub::create(['name' => 'test_name', 'description' => 'test_description']);
        $resource = $this->controller->show($category->id);
        $serialized = $resource->response()->getData(true);
        $this->assertEquals($category->toArray(), $serialized['data']);
    }

    public function testUpdate()
    {
        $category = CategoryStub::create(['name' => 'test_name', 'description' => 'test_description']);
        $request = \Mockery::mock(Request::class);
        $request->shouldReceive('all')
            ->once()
            ->andReturn(['name' => 'test_changed', 'description' => 'test_description_changed']);
        $resource = $this->controller->update($request, $category->id);
        $serialized = $resource->response()->getData(true);
        $category->refresh();
        $this->assertEquals(CategoryStub::find(1)->toarray(), $serialized['data']);
    }

    public function testDestroy()
    {
        $category = CategoryStub::create(['name' => 'test_name', 'description' => 'test_description']);
        $response = $this->controller->destroy($category->id);
        $this
            ->createTestResponse($response)
            ->assertStatus(204);
        $this->assertCount(0, CategoryStub::all());
    }

}
