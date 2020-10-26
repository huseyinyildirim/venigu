<?php

//region Namespace
namespace App\Models;
//endregion

//region Using
use Illuminate\Database\Eloquent\Model;
//endregion

class TblServiceField extends Model
{
    protected $table = 'tbl_service_field';

    protected $primaryKey = 'id';

    protected $fillable = [
        'service_id',
        'detail',
        'start_date',
        'start_time',
        'start_address',
        'start_country_id',
        'start_city_id',
        'start_town_id',
        'start_district_id',
        'target_address',
        'target_country_id',
        'target_city_id',
        'target_town_id',
        'target_district_id',
        'delivery_address',
        'delivery_country_id',
        'delivery_city_id',
        'delivery_town_id',
        'delivery_district_id',
        'note',
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