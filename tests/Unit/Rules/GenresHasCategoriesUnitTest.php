<?php

namespace Tests\Unit\Rules;

use App\Rules\GenreHasCategoriesRule;
use Mockery\MockInterface;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GenresHasCategoriesUnitTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function testCategoriesIdField()
    {
        $rule = new GenreHasCategoriesRule([1, 1, 2, 2]);

        $reflectionClass = new \ReflectionClass(GenreHasCategoriesRule::class);
        $reflectionProperty = $reflectionClass->getProperty('categoriesId');
        $reflectionProperty->setAccessible(true);

        $genresId = $reflectionProperty->getValue($rule);
        $this->assertEqualsCanonicalizing([1, 2], $genresId);
    }


    public function testPassesReturnsFalseWhenCategoriesOrGenreIsArrayEmpty()
    {
        $rule = $this->createRuleMock([1]);
        $this->assertFalse($rule->passes('', []));

        $rule = $this->createRuleMock([]);
        $this->assertFalse($rule->passes('', [1]));
    }

    public function testPassesReturnsFalseWhenGetRowsIsEmpty()
    {
        $rule = $this->createRuleMock([1]);
        $rule
            ->shouldReceive('getRows')
            ->withAnyArgs()
            ->andReturn(collect());
        $this->assertFalse($rule->passes('', [1]));
    }

    public function testPassesReturnsFalseWhenHasCategoriesWithoutGenres()
    {
        $rule = $this->createRuleMock([1, 2]);
        $rule
            ->shouldReceive('getRows')
            ->withAnyArgs()
            ->andReturn(collect(['category->id' => 1]));
        $this->assertFalse($rule->passes('', [1]));
    }

    public function testPassesIsValid()
    {
        $rule = $this->createRuleMock([1, 2]);
        $rule
            ->shouldReceive('getRows')
            ->withAnyArgs()
            ->andReturn(collect([
                ['category_id' => 1],
                ['category_id' => 2]
            ]));
        $this->assertTrue($rule->passes('', [1]));

        $rule = $this->createRuleMock([1, 2]);
        $rule
            ->shouldReceive('getRows')
            ->withAnyArgs()
            ->andReturn(collect([
                ['category_id' => 1],
                ['category_id' => 2],
                ['category_id' => 1],
                ['category_id' => 2],
            ]));
        $this->assertTrue($rule->passes('', [1]));
    }

    private function createRuleMock(array $categoriesId): MockInterface
    {
        return \Mockery::mock(GenreHasCategoriesRule::class, [$categoriesId])
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();
    }

}
