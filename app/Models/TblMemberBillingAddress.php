<?php

//region Namespace
namespace App\Models;
//endregion

//region Using
use Illuminate\Database\Eloquent\Model;
//endregion

class TblMemberBillingAddress extends Model
{
    protected $table = 'tbl_member_billing_address';

    protected $primaryKey = 'id';

    protected $fillable = [
        'member_id',
        'type',
        'title',
        'name',
        'tax_id',
        'tax_office',
        'address',
        'country_id',
        'city_id',
        'town_id',
        'district_id',
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