<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('todos', function (Blueprint $table) {
            $table->id();
            $table->string("title",100)->nullable(false);
            $table->string("priority",100)->nullable();
            $table->boolean("is_active");
            $table->unsignedBigInteger("activity_id")->nullable(false);

            $table->foreign("activity_id")->on("activities")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('todos');
    }
};
