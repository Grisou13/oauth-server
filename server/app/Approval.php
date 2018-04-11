<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Approval extends Model
{
    use SoftDeletes, Mine;

    public $timestamps = false;
    protected $attributes = [
        "approved" => false
    ];
    public $casts = [
      "approved" => "boolean"
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'approved', "user_id","project_id"
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * Approve a pensing request
     * @return $this
     */
    public function setApproved(){
        $this->approved = true;
        return $this;
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function project(){
        return $this->belongsTo(Project::class);
    }



}
