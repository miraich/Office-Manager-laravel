<?php

use App\Enums\Statuses;
use App\Enums\Subscriptions;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('status_id')->default(Statuses::NOT_STARTED->value)->constrained('statuses');
            $table->foreignId('project_id')->constrained('projects');
            $table->timestampTz('end_date');
            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
