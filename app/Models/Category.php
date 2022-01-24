<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table='categories';
    protected $fillable=['name_ar','name_en','status'];

    public function scopeSelection($query)
    {
        return $query->select('id' , 'name_'. app()->getLocale(). ' as name' , 'status');
    }
}
