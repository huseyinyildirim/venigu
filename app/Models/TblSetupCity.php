<?php

//region Namespace
namespace App\Models;
//endregion

//region Using
use Illuminate\Database\Eloquent\Model;
//endregion

class TblSetupCity extends Model
{
    protected $table = 'tbl_setup_city';

    protected $primaryKey = 'id';

    protected $fillable = [
        'country_id',
        'title',
        'plate_no',
        'phone_code',
        'is_active',
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