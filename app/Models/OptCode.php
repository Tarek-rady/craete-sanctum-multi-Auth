<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptCode extends Model
{
    use HasFactory;

    protected $table='opt_codes';
    protected $fillable=['mobile' ,'opt'];


}
