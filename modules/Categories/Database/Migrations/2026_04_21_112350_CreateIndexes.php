<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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
        if (Schema::hasTable(Document::TABLE_NAME)) {
            Schema::table(Document::TABLE_NAME, function (Blueprint $table): void {
                if (
                    Schema::hasColumn(Document::TABLE_NAME, 'category_id')
                    && !$this->hasIndex(Document::TABLE_NAME, 'documents_category_id_index')
                ) {
                    $table->index('category_id', 'documents_category_id_index');
                }

                if (
                    Schema::hasColumn(Document::TABLE_NAME, 'status')
                    && !$this->hasIndex(Document::TABLE_NAME, 'documents_status_index')
                ) {
                    $table->index('status', 'documents_status_index');
                }

                if (
                    Schema::hasColumn(Document::TABLE_NAME, 'published_at')
                    && !$this->hasIndex(Document::TABLE_NAME, 'documents_published_at_index')
                ) {
                    $table->index('published_at', 'documents_published_at_index');
                }

                if (
                    Schema::hasColumn(Document::TABLE_NAME, 'is_featured')
                    && !$this->hasIndex(Document::TABLE_NAME, 'documents_is_featured_index')
                ) {
                    $table->index('is_featured', 'documents_is_featured_index');
                }

                if (
                    Schema::hasColumn(Document::TABLE_NAME, 'slug')
                    && !$this->hasIndex(Document::TABLE_NAME, 'documents_slug_unique')
                ) {
                    $table->unique('slug', 'documents_slug_unique');
                }
            });
        }

        if (Schema::hasTable(Category::TABLE_NAME)) {
            Schema::table(Category::TABLE_NAME, function (Blueprint $table): void {
                if (
                    Schema::hasColumn(Category::TABLE_NAME, 'slug')
                    && !$this->hasIndex(Category::TABLE_NAME, 'categories_slug_unique')
                ) {
                    $table->unique('slug', 'categories_slug_unique');
                }

                if (
                    Schema::hasColumn(Category::TABLE_NAME, 'name')
                    && !$this->hasIndex(Category::TABLE_NAME, 'categories_name_index')
                ) {
                    $table->index('name', 'categories_name_index');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        if (Schema::hasTable(Document::TABLE_NAME)) {
            Schema::table(Document::TABLE_NAME, function (Blueprint $table): void {
                if ($this->hasIndex(Document::TABLE_NAME, 'documents_category_id_index')) {
                    $table->dropIndex('documents_category_id_index');
                }

                if ($this->hasIndex(Document::TABLE_NAME, 'documents_status_index')) {
                    $table->dropIndex('documents_status_index');
                }

                if ($this->hasIndex(Document::TABLE_NAME, 'documents_published_at_index')) {
                    $table->dropIndex('documents_published_at_index');
                }

                if ($this->hasIndex(Document::TABLE_NAME, 'documents_is_featured_index')) {
                    $table->dropIndex('documents_is_featured_index');
                }

                if ($this->hasIndex(Document::TABLE_NAME, 'documents_slug_unique')) {
                    $table->dropUnique('documents_slug_unique');
                }
            });
        }

        if (Schema::hasTable(Category::TABLE_NAME)) {
            Schema::table(Category::TABLE_NAME, function (Blueprint $table): void {
                if ($this->hasIndex(Category::TABLE_NAME, 'categories_slug_unique')) {
                    $table->dropUnique('categories_slug_unique');
                }

                if ($this->hasIndex(Category::TABLE_NAME, 'categories_name_index')) {
                    $table->dropIndex('categories_name_index');
                }
            });
        }
    }

    /**
     * Determine whether the given index exists on the table.
     *
     * @param string $tableName
     * @param string $indexName
     * @return bool
     */
    private function hasIndex(string $tableName, string $indexName): bool
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            return $this->hasIndexSqlite($tableName, $indexName);
        }

        if ($driver === 'mysql') {
            return $this->hasIndexMySql($tableName, $indexName);
        }

        return false;
    }

    /**
     * Determine whether the given index exists on a MySQL table.
     *
     * @param string $tableName
     * @param string $indexName
     * @return bool
     */
    private function hasIndexMySql(string $tableName, string $indexName): bool
    {
        return DB::table('information_schema.statistics')
            ->where('table_schema', DB::getDatabaseName())
            ->where('table_name', $tableName)
            ->where('index_name', $indexName)
            ->exists();
    }

    /**
     * Determine whether the given index exists on a SQLite table.
     *
     * @param string $tableName
     * @param string $indexName
     * @return bool
     */
    private function hasIndexSqlite(string $tableName, string $indexName): bool
    {
        $indexes = DB::select("PRAGMA index_list('{$tableName}')");

        foreach ($indexes as $index) {
            if (isset($index->name) && $index->name === $indexName) {
                return true;
            }
        }

        return false;
    }
};
