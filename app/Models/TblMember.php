<?php

//region Namespace
namespace App\Models;
//endregion

//region Using
use Illuminate\Database\Eloquent\Model;
//endregion

class TblMember extends Model
{
    protected $table = 'tbl_member';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'mail',
        'phone',
        'username',
        'password',
        'is_active'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public $timestamps = false;

    //region Set Password
    public function setPassword($password)
    {
        $this->update([
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);
    }
    //endregion
}