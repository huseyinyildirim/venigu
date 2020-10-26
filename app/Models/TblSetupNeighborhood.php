<?php

//region Namespace
namespace App\Models;
//endregion

//region Using
use Illuminate\Database\Eloquent\Model;
//endregion

class TblSetupNeighborhood extends Model
{
    protected $table = 'tbl_setup_neighborhood';

    protected $primaryKey = 'id';

    protected $fillable = [
        'district_id',
        'title',
        'zip_code',
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