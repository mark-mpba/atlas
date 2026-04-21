<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Settings\Models\Setting;

/**
 * Class CreateSettingsTable
 */
return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(Setting::TABLE_NAME, function (Blueprint $table): void {
            $table->id();
            $table->string('key', 150)->unique();
            $table->longText('value')->nullable();
            $table->string('type', 50)->default('string');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists(Setting::TABLE_NAME);
    }
};
