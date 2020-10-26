<?php

//region Namespace
namespace App\Models;
//endregion

//region Using
use Illuminate\Database\Eloquent\Model;
//endregion

class TblSetupCountry extends Model
{
    protected $table = 'tbl_setup_country';

    protected $primaryKey = 'id';

    protected $fillable = [
        'binary_code',
        'triple_code',
        'title',
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