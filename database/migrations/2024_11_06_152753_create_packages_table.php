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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->string('name');
            $table->string('version');
            $table->string('type');
            $table->boolean('from_composer_json')->default(false);
            $table->longText('source')->nullable();
            $table->longText('require')->nullable();
            $table->string('latest_version')->nullable();
            $table->string('latest_version_url')->nullable();
            $table->timestamps();
        });
        // Make foreign key
        Schema::table('packages', function (Blueprint $table) {
            $table->foreign('project_id')->references('id')->on('projects');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
