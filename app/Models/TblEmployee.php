<?php

//region Namespace
namespace App\Models;
//endregion

//region Using
use Illuminate\Database\Eloquent\Model;
//endregion

class TblEmployee extends Model
{
    protected $table = 'tbl_employee';

    protected $primaryKey = 'id';

    protected $fillable = [
        'company_id',
        'name',
        'mail',
        'phone',
        'password',
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