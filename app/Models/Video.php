<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Video extends Model
{
    use SoftDeletes;
    use Traits\Uuid;
    use Traits\UploadFiles;

    const RATTING_LIST = ['L', '10', '12', '14', '16', '18'];

    public $incrementing = false;
    public static $fileFields = ['video_file','thumb_file'];

    protected $fillable =
        [
            'title',
            'description',
            'year_launched',
            'opened',
            'rating',
            'duration',
            'video_file',
            'thumb_file',
        ];

    protected $dates =
        [
            'created_at',
            'updated_at',
            'deleted_at'
        ];

    protected $casts =
        [
            'id' => 'string',
            'opened' => 'boolean',
            'year_launched' => 'integer',
            'duration' => 'integer'
        ];


    public static function create(array $attributes = [])
    {
        $files = self::extractFiles($attributes);

        try {
            DB::beginTransaction();
            /** @var Video $obj */
            $obj = static::query()->create($attributes);
            static::handleRelations($obj, $attributes);
            $obj->uploadFiles($files);
            DB::commit();
            return $obj;
        } catch (\Exception $e) {
            if (isset($obj)) {
                $obj->deleteFiles($files);
            }
            DB::rollBack();
            throw $e;
        }
    }

    public function update(array $attributes = [], array $options = [])
    {
        $files = self::extractFiles($attributes);
        try {
            DB::beginTransaction();
            $saved = parent::update($attributes, $options);
            static::handleRelations($this, $attributes);
            if ($saved) {
                $this->uploadFiles($files);
            }
            DB::commit();
            if ($saved && count($files)) {
                $this->deleteOldFiles();
            }
            return $saved;
        } catch (\Exception $e) {
            $this->deleteFiles($files);
            DB::rollBack();
            throw $e;
        }
    }

    public static function handleRelations(Video $video, array $attributes)
    {
        if (isset($attributes['categories_id'])) {
            $video->categories()->sync($attributes['categories_id']);
        }
        if (isset($attributes['genres_id'])) {
            $video->genres()->sync($attributes['genres_id']);
        }
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class)->withTrashed();
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class)->withTrashed();
    }

    public function uploadDir()
    {
        //video file upload in user->id (uuid) name folder
        return $this->id;
    }
}
