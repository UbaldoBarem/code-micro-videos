<?php

namespace Tests\Feature\Models\Video;

use App\Models\Genre;
use App\Models\Video;
use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Tests\Exceptions\TestException;
use Illuminate\Database\QueryException;
use Illuminate\Database\Events\TransactionCommitted;

class VideoUploadTest extends BaseVideoTestCase
{

    public function testCreateWithFiles()
    {
        \Storage::fake();
        $video = Video::create(
            $this->data + [
                'thumb_file' => UploadedFile::fake()->image('thumb.jpg'),
                'video_file' => UploadedFile::fake()->image('video.mp4'),
            ]
        );
        \Storage::assertExists("{$video->id}/{$video->thumb_file}");
        \Storage::assertExists("{$video->id}/{$video->video_file}");
    }

    public function testCreateIfRollbackFiles()
    {
        \Storage::fake();
        \Event::listen(TransactionCommitted::class, function () {
            throw new TestException();
        });
        $hasError = false;

        try {
            Video::create(
                $this->data + [
                    'video_file' => UploadedFile::fake()->image('video.mp4'),
                    'thumb_file' => UploadedFile::fake()->image('thumb.jpg')
                ]
            );
        } catch (TestException $e) {
            $this->assertCount(0, \Storage::allFiles());
            $hasError = true;
        }

        // dd($hasError);

        $this->assertTrue($hasError);
    }

    public function testUpdateWithFiles()
    {
        \Storage::fake();
        $video = factory(Video::class)->create();
        $thumbFile = UploadedFile::fake()->image('thumb.jpg');
        $videoFile = UploadedFile::fake()->image('video.mp4');
        $video->update($this->data + [
                'thumb_file' => $thumbFile,
                'video_file' => $videoFile,
            ]);
        \Storage::assertExists("{$video->id}/{$video->thumb_file}");
        \Storage::assertExists("{$video->id}/{$video->video_file}");


        $newVideoFile = UploadedFile::fake()->image('video.mp4');
        $video->update($this->data + [
                'video_file' => $newVideoFile,
            ]);

        \Storage::assertExists("{$video->id}/{$thumbFile->hashName()}");
        \Storage::assertExists("{$video->id}/{$newVideoFile->hashName()}");
        \Storage::assertMissing("{$video->id}/{$videoFile->hashName()}");
    }

    public function testUpdateIfRollbackFiles()
    {
        \Storage::fake();
        $video = factory(Video::class)->create();
        \Event::listen(TransactionCommitted::class, function () {
            throw new TestException();
        });
        $hasError = false;

        try {
            $video->update(
                $this->data + [
                    'video_file' => UploadedFile::fake()->create('video.mp4'),
                    'thumb_file' => UploadedFile::fake()->create('thumb.jpg')
                ]
            );
        } catch (TestException $e) {
            $this->assertCount(0, \Storage::allFiles());
            $hasError = true;
        }

        $this->assertTrue($hasError);
    }

    public function testFileUrlWithLocalDriver()
    {
        $fileFields = [];
        foreach (Video::$fileFields as $field) {
            $fileFields[$field] = "$field.test";
        }

        $video = factory(Video::class)->create($fileFields);
        $localDriver = config('filesystems.default'); // video_local
        // dd($localDriver);
        $u = 'filesystem.disks.video_local.url';
        //dd($u);
        //$u['url'];

        $baseUrl = config($u);
        //dd($baseUrl);
        foreach ($fileFields as $field => $value){
            $fileUrl = $video->{"{$field}_url"};
            $this->assertEquals("{$baseUrl}/$video->id/$value",$fileUrl);
        }
    }

    public function testFileUrlWithGcsDriver()
    {
        $fileFields = [];
        foreach (Video::$fileFields as $field) {
            $fileFields[$field] = "$field.test";
        }

        $video = factory(Video::class)->create($fileFields);
        $baseUrl = config('filesystems.disks.gcs.storage_api_uri');
        \Config::set('filesystems.default','gcs');

        foreach ($fileFields as $field => $value){
            $fileUrl = $video->{"{$field}_url"};
            $this->assertEquals("{$baseUrl}/$video->id/$value",$fileUrl);
        }
    }

    public function testFileUrlsIfNullWhenFieldsAreNull()
    {
        $video = factory(Video::class)->create();
        foreach (Video::$fileFields as $field){
            $fileUrl = $video->{"{$field}_url"};
            $this->assertNull($fileUrl);
        }
    }
}
