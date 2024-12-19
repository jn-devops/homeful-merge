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
        Schema::create('field_template', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('template_id')->index();
            $table->foreignId('field_id')->index();
            $table->timestamps();
            $table->unique(['template_id', 'field_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('field_template');
    }
};
