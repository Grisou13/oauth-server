<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Scope extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','description',
    ];
    public $appends = [
      "scope"
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [

    ];

    public $timestamps = false;

    public function project(){
        return $this->belongsTo(Project::class);
    }
    public function getScopeAttribute(){
      $exploded = explode(".",$this->attributes["name"]);
      array_shift($exploded);
      return implode(".",$exploded);

    }
    public function setNameAttribute($value){
      return $this->attributes["name"] = $this->project->name.".".trim($value);
    }

}
