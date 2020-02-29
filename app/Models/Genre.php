<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Genre extends Model
{
    use SoftDeletes;
    use Traits\Uuid;

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

    public function categories()
    {
        return $this->belongsToMany(Category::class)->withTrashed();
    }

    public function videos()
    {
        return $this->belongsToMany(Video::class)->withTrashed();
    }

}
