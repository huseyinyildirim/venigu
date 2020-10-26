<?php

//region Namespace
namespace App\Models;
//endregion

//region Using
use Illuminate\Database\Eloquent\Model;
//endregion

class TblCurrency extends Model
{
    protected $table = 'tbl_currency';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'shorten',
        'symbol',
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