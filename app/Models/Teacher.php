<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;


class Teacher extends Model
{
    use HasFactory , HasApiTokens;

    protected $table='teachers';
    protected $fillable=['name_ar' ,'name_en' ,'email' ,'mobile'];

    protected $appends = ['user_type'];

    public function getUserTypeAttribute()
    {
        return 'teachers';
    }
}
