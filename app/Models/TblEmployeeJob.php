<?php

//region Namespace
namespace App\Models;
//endregion

//region Using
use Illuminate\Database\Eloquent\Model;
//endregion

class TblEmployeeJob extends Model
{
    protected $table = 'tbl_employee_job';

    protected $primaryKey = 'id';

    protected $fillable = [
        'employee_id',
        'job_id',
        //region Updated By and Created By
        'created_by'
        //endregion
    ];

    protected $dates = [
        'created_at'
    ];

    public $timestamps = false;
}