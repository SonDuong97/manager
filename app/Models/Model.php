<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Model extends EloquentModel
{
    use SoftDeletes;

    /**
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];


    /**
     * List of columns
     * @var string
     */
    const COL_ID = 'id';
    const COL_CREATED_AT = 'created_at';
    const COL_MODIFIED_AT = 'modified_at';
    const COL_DELETED_AT = 'deleted_at';
}
