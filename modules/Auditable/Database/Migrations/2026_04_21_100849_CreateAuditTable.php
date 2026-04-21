<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Auditable\Models\Audit;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up(): void

    {

        Schema::create(Audit::TABLE_NAME, function (Blueprint $table): void {

            $table->id();
            $table->string('module', 100)->nullable();
            $table->string('entity_type', 150);
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->string('action', 50);
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->unsignedBigInteger('performed_by')->nullable();
            $table->timestamps();
            $table->index(['entity_type', 'entity_id']);
            $table->index(['module', 'action']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */

    public function down(): void

    {

        Schema::dropIfExists(Audit::TABLE_NAME);

    }
};
