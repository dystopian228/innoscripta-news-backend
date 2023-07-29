<?php

use App\Entities\BaseFields;
use App\Entities\SourceDefinition;
use App\Entities\UserDefinition;
use App\Entities\UserSourceDefinition;
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
        Schema::create(UserSourceDefinition::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger(UserSourceDefinition::USER_ID)
                ->constrained(
                    UserDefinition::TABLE_NAME,
                    BaseFields::ID)->onDelete('cascade');
            $table->unsignedBigInteger(UserSourceDefinition::SOURCE_ID)
                ->constrained(
                    SourceDefinition::TABLE_NAME,
                    BaseFields::ID)
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(UserSourceDefinition::TABLE_NAME);
    }
};
