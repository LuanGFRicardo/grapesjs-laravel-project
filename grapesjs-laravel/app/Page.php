<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $table = 'templates';
    public $timestamps = false;
    protected $fillable = ['nome', 'html', 'projeto'];
}
