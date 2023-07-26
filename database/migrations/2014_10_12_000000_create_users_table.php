<?php

use App\Entities\UserDefinition;
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
        Schema::create(UserDefinition::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->string(UserDefinition::NAME);
            $table->string(UserDefinition::EMAIL)->unique();
            $table->timestamp(UserDefinition::EMAIL_VERIFIED_AT)->nullable();
            $table->string(UserDefinition::PASSWORD);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(UserDefinition::TABLE_NAME);
    }
};
