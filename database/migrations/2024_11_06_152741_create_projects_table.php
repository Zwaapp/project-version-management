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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('active')->default(true);
            $table->string('url');
            $table->string('source');
            $table->string('main_branch');
            $table->string('custom_branch')->nullable()->comment('Custom branch to fetch packages from');
            $table->string('repository_slug');
            $table->string('repository_client')->comment('The repository client class name that fetched the project');
            $table->string('type')->nullable()->comment('Komma separated list of the types of the project');
            $table->timestamps();
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
