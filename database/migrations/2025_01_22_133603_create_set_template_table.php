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
        Schema::create('set_template', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('set_id')->index();
            $table->foreignUuid('template_id')->index();
            $table->timestamps();
            $table->unique(['set_id', 'template_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('set_template');
    }
};
