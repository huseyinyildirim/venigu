<?php

//region Namespace
namespace App\Models;
//endregion

//region Using
use Illuminate\Database\Eloquent\Model;
//endregion

class TblCompany extends Model
{
    protected $table = 'tbl_company';

    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
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