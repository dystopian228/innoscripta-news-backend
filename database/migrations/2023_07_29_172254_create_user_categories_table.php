<?php

use App\Entities\BaseFields;
use App\Entities\UserCategoryDefinition;
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
        Schema::create(UserCategoryDefinition::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger(UserCategoryDefinition::USER_ID)
                ->constrained(
                    UserDefinition::TABLE_NAME,
                    BaseFields::ID)->onDelete('cascade');
            $table->string(UserCategoryDefinition::CATEGORY);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(UserCategoryDefinition::TABLE_NAME);
    }
};
