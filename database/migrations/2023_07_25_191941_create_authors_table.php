<?php

use App\Entities\AuthorDefinition;
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
        Schema::create(AuthorDefinition::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->string(AuthorDefinition::NAME);
            $table->string(AuthorDefinition::ORGANIZATION)->nullable();
            $table->string(AuthorDefinition::TITLE)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(AuthorDefinition::TABLE_NAME);
    }
};
