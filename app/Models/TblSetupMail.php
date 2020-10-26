<?php

//region Namespace
namespace App\Models;
//endregion

//region Using
use Illuminate\Database\Eloquent\Model;
//endregion

class TblSetupMail extends Model
{
    protected $table = 'tbl_setup_mail';

    protected $primaryKey = 'id';

    protected $fillable = [
        'type',
        'host',
        'username',
        'password',
        'port',
        'use_ssl',
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