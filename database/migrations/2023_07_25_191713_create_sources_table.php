<?php

use App\Entities\SourceDefinition;
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
        Schema::create(SourceDefinition::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->string(SourceDefinition::SYMBOL);
            $table->string(SourceDefinition::NAME);
            $table->text(SourceDefinition::DESCRIPTION)->nullable();
            $table->text(SourceDefinition::URL)->nullable();
            $table->string(SourceDefinition::MAIN_CATEGORY)->default('Other');
            $table->string(SourceDefinition::COUNTRY)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(SourceDefinition::TABLE_NAME);
    }
};
