<?php

/**

 * Class CreateDocumentsTable

 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Modules\Categories\Models\Category;
use Modules\Documents\Models\Document;

return new class extends Migration

{

    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up(): void
    {
        Schema::create(Document::TABLE_NAME, function (Blueprint $table): void {
            $table->id();
            $table->string('title', 255);
            $table->string('slug', 255)->unique();
            $table->text('excerpt')->nullable();
            $table->longText('markdown_body');
            $table->longText('html_body')->nullable();
            $table->string('status', 20)->default('draft')->index();
            $table->boolean('is_featured')->default(false)->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */

    public function down(): void

    {
        Schema::dropIfExists(Document::TABLE_NAME);
    }

};
