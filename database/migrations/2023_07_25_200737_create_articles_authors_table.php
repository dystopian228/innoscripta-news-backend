<?php

use App\Entities\ArticleAuthorDefinition;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(ArticleAuthorDefinition::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger(ArticleAuthorDefinition::ARTICLE_ID)
                ->constrained(
                    \App\Entities\ArticleDefinition::TABLE_NAME,
                    \App\Entities\BaseFields::ID)->onDelete('cascade');
            $table->unsignedBigInteger(ArticleAuthorDefinition::AUTHOR_ID)
                ->constrained(
                    \App\Entities\AuthorDefinition::TABLE_NAME,
                    \App\Entities\BaseFields::ID)
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(ArticleAuthorDefinition::TABLE_NAME);
    }
};
