<?php

//region Namespace
namespace App\Models;
//endregion

//region Using
use Illuminate\Database\Eloquent\Model;
//endregion

class TblSetupSocial extends Model
{
    protected $table = 'tbl_setup_social';

    protected $primaryKey = 'id';

    protected $fillable = [
        'facebook',
        'facebook_username',
        'twitter',
        'twitter_username',
        'instagram',
        'instagram_username',
        'google_plus',
        'google_plus_username',
        'pinterest',
        'pinterest_username',
        'linkedin',
        'linkedin_username',
        'tumblr',
        'tumblr_username',
        //region Updated By and Created By
        'updated_by'
        //endregion
    ];

    protected $dates = [
        'updated_at'
    ];

    public $timestamps = false;
}