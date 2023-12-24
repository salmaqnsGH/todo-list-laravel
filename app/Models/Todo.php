<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Todo extends Model
{
    protected $table = "todos";
    protected $primaryKey = "id";
    protected $keyType = "int";
    protected $timestamps = true;
    protected $incrementing = true;

    public function activity(): BelongsTo {
        return $this->belongsTo(Activity::class, "activity_id", "id");
    }
}
