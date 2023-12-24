<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $table = "activities";
    protected $primaryKey = "id";
    protected $keyType = "int";
    protected $timestamps = true;
    protected $incrementing = true;
}
