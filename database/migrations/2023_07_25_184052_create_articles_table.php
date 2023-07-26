<?php

use App\Entities\ArticleDefinition;
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
        Schema::create(ArticleDefinition::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->string(ArticleDefinition::TITLE);
            $table->string(ArticleDefinition::HEADLINE)->nullable();
            $table->text(ArticleDefinition::LEAD_PARAGRAPH)->nullable();
            $table->dateTime(ArticleDefinition::PUBLISH_DATE);
            $table->string(ArticleDefinition::ARTICLE_URL);
            $table->string(ArticleDefinition::IMAGE_URL)->nullable();
            $table->smallInteger(ArticleDefinition::NEWS_PROVIDER_TYPE);
            $table->unsignedInteger(ArticleDefinition::SOURCE_ID);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(ArticleDefinition::TABLE_NAME);
    }
};
