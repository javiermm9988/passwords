<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = ['name'];

    protected function user() 
    {
        return $this->belongsTo(User::class);
    }

    protected function password() 
    {
        return $this->hasMany(Password::class);
    }

}
