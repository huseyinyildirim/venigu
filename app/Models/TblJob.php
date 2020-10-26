<?php

//region Namespace
namespace App\Models;
//endregion

//region Using
use Illuminate\Database\Eloquent\Model;
//endregion

class TblJob extends Model
{
    protected $table = 'tbl_job';

    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        //region Updated By and Created By
        'created_by',
        'updated_by'
        //endregion
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public $timestamps = false;
}