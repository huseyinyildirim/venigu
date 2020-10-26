<?php

//region Namespace
namespace App\Models;
//endregion

//region Using
use Illuminate\Database\Eloquent\Model;
//endregion

class TblSetupSystem extends Model
{
    protected $table = 'tbl_setup_system';

    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'domain',
        'seo_title',
        'seo_description',
        'seo_keyword',
        'meta_author',
        'meta_robots',
        'meta_application_name',
        'meta_other',
        'og_title',
        'og_description',
        'og_twitter_card',
        'og_twitter_site',
        'og_twitter_creator',
        'default_mail_id',
        'contact_mail_id',
        //region Updated By and Created By
        'updated_by'
        //endregion
    ];

    protected $dates = [
        'updated_at'
    ];

    public $timestamps = false;
}