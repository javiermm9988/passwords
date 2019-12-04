<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Password extends Model
{
    protected $table = 'password';
    protected $fillable = ['title', 'password', 'category'];

    protected function category() 
    {
        return $this->belongsTo(Catedory::class);
    }
}
