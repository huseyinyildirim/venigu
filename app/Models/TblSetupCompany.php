<?php

//region Namespace
namespace App\Models;
//endregion

//region Using
use Illuminate\Database\Eloquent\Model;
//endregion

class TblSetupCompany extends Model
{
    protected $table = 'tbl_setup_company';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'address',
        'domain',
        'mail',
        'phone',
        'mobile',
        'fax',
        'whatsapp',
        'facebook',
        'instagram',
        //region Updated By and Created By
        'updated_by'
        //endregion
    ];

    protected $dates = [
        'updated_at'
    ];

    public $timestamps = false;
}