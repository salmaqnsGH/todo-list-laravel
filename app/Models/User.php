<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    protected $table = "users";
    protected $primaryKey = "id";
    protected $keyType = "int";
    protected $timestamps = true;
    protected $incrementing = true;

    public function activities(): HasMany {
        return $this->hasMany(Activity::class,"user_id","id");
    }
}
