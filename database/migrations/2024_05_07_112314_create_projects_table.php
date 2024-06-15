<?php

use App\Enums\Statuses;
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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('status_id')->default(Statuses::NOT_STARTED->value)
                ->constrained('statuses')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->string('name');
            $table->text('description');
            $table->integer('budget');
            $table->timestampTz('end_date');
            $table->string('documentation')->nullable();
            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
