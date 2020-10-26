<?php

//region Namespace
namespace App\Models;
//endregion

//region Using
use Illuminate\Database\Eloquent\Model;
//endregion

class TblSetupExtension extends Model
{
    protected $table = 'tbl_setup_extension';

    protected $primaryKey = 'id';

    protected $fillable = [
        'google_analytics',
        'google_analytics_id',
        'yandex_metrica',
        'yandex_metrica_id',
        'google_site_verification',
        'bing_site_verification',
        'support_chat',
        //region Updated By and Created By
        'updated_by'
        //endregion
    ];

    protected $dates = [
        'updated_at'
    ];

    public $timestamps = false;
}