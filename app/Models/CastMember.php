<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CastMember extends Model
{
    use SoftDeletes;
    use Traits\Uuid;

    public $incrementing = false;

    const TYPE_DIRECTOR = 1;
    const TYPE_ACTOR = 2;

    protected $fillable =
        [
            'name',
            'type'
        ];

    protected $dates =
        [
            'created_at',
            'updated_at',
            'deleted_at'
        ];

    protected $casts =
        [
            'id' => 'string'
        ];
}
