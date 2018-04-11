<?php
namespace App;
use Illuminate\Support\Facades\Auth;

trait Mine {
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