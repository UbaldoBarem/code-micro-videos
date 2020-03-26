<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Uuid;


class Category extends Model
{
    use SoftDeletes;
    use Uuid;

    public $incrementing = false;

    protected $fillable =
        [
            'name',
            'description',
            'is_active'
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
            'is_active' => 'boolean'
        ];

    public function genres()
    {
        return $this->belongsToMany(Genre::class)->withTrashed();
    }

    public function videos()
    {
        return $this->belongsToMany(Video::class)->withTrashed();
    }

}
