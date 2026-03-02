<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('role_conflicts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('conflict_role_id');
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('roles')->cascadeOnDelete();
            $table->foreign('conflict_role_id')->references('id')->on('roles')->cascadeOnDelete();

            $table->unique(['role_id', 'conflict_role_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_conflicts');
    }
};
