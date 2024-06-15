<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_group', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreignId('group_id')->constrained('groups')
                ->onDelete('cascade')
                ->onUpdate('cascade');
//            $table->string('invitation_code');
//            $table->timestampTz('invitation_confirmed_at')->nullable();
            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_group');
    }
};
