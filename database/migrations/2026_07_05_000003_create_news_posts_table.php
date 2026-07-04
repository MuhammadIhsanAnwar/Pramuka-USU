<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news_posts', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('news_category_id')->constrained('news_categories')->cascadeOnDelete();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('thumbnail_path')->nullable();
            $table->string('excerpt', 500)->nullable();
            $table->longText('content');
            $table->enum('status', ['draft', 'publish'])->default('draft');
            $table->timestamp('published_at')->nullable()->index();
            $table->unsignedInteger('viewer_count')->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['news_category_id', 'status']);
            $table->index(['author_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_posts');
    }
};