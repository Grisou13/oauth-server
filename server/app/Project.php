<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Project extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','url',"description"
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [

    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function scopes(){
        return $this->hasMany(Scope::class);
    }
    public function approvals(){
        return $this->hasMany(Approval::class);
    }
    public function pendingApprovals(){
        return $this->approvals()->where("approved","=",false);
    }

    /**
    * Scope a query to only include active users.
    *
    * @param \Illuminate\Database\Eloquent\Builder $query
    * @return \Illuminate\Database\Eloquent\Builder
    */
    public static function scopeMine($query){
      if(Auth::check())
      return $query->where("user_id","=", Auth::user()->id);
      else
      return $query;
    }
    /**
     * Scope a query to only include active users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function scopeNotMine($query){
        if(Auth::check())
            return $query->where("user_id","!=", Auth::user()->id);
        else
            return $query;
    }

}
