<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['video', 'audio', 'pdf', 'text'])->default('video');
            $table->string('content_url')->nullable();
            $table->longText('content_text')->nullable();
            $table->integer('duration_seconds')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_preview')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['module_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
