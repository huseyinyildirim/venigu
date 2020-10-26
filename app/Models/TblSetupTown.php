<?php

//region Namespace
namespace App\Models;
//endregion

//region Using
use Illuminate\Database\Eloquent\Model;
//endregion

class TblSetupTown extends Model
{
    protected $table = 'tbl_setup_town';

    protected $primaryKey = 'id';

    protected $fillable = [
        'city_id',
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