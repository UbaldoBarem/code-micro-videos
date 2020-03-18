<?php

namespace Tests\Unit\Models;

use App\Models\Traits\UploadFiles;
use App\Models\Traits\Uuid;
use App\Models\Video;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPUnit\Framework\TestCase;

class VideoUnitTest extends TestCase
{
    private $video;

    protected function setUp(): void
    {
        parent::setUp();
        $this->video = new Video();
    }

    protected function tearDown(): void
    {
        parent::tearDown(); // TODO: Change the autogenerated stub
    }

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass(); // TODO: Change the autogenerated stub
    }

    public function testFillableAttribute()
    {
        $fillable = [
            'title',
            'description',
            'year_launched',
            'opened',
            'rating',
            'duration',
            'video_file',
            'thumb_file',
        ];
        $this->assertEquals($fillable, $this->video->getFillable());
    }

    public function testIfUseTraits()
    {
        $traits = [
            SoftDeletes::class,
            Uuid::class,
            UploadFiles::class
        ];
        $videoTraits = array_keys(class_uses(Video::class));
        $this->assertEquals($traits, $videoTraits);

    }

    public function testDatesAttribute()
    {
        $dates = [
            'created_at', 'updated_at', 'deleted_at'
        ];
        foreach ($dates as $date) {
            $this->assertContains($date, $this->video->getDates());
        }
        $this->assertCount(count($dates), $this->video->getDates());

    }

    public function testCastsAttribute()
    {
        $casts =
            [
                'id' => 'string',
                'opened' => 'boolean',
                'year_launched' => 'integer',
                'duration' => 'integer'
            ];
        $this->assertEquals($casts, $this->video->getCasts());
    }


    public function testIncrementing()
    {
        $this->assertFalse($this->video->incrementing);
    }
}
