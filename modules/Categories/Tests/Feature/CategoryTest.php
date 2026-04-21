<?php

namespace Modules\Categories\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Categories\Database\Seeders\CategoriesDatabaseSeeder;
use Modules\Categories\Models\Category;
use Modules\Documents\Models\Document;
use Tests\TestCase;

/**
 * Class CategoryTest
 */
class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Set up the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(CategoriesDatabaseSeeder::class);
    }

    /**
     * It seeds the default categories.
     *
     * @return void
     */
    public function test_it_seeds_the_default_categories(): void
    {
        $this->assertDatabaseHas(Category::TABLE_NAME, [
            'slug' => 'wms',
        ]);

        $this->assertDatabaseHas(Category::TABLE_NAME, [
            'slug' => 'sentinel',
        ]);
    }

    /**
     * It does not duplicate categories when seeded twice.
     *
     * @return void
     */
    public function test_it_does_not_duplicate_categories_when_seeded_twice(): void
    {
        $this->seed(CategoriesDatabaseSeeder::class);

        $this->assertSame(2, Category::query()->count());
    }

    public function test_it_can_create_a_category(): void
    {
        $category = Category::create([
            'name' => 'Reports',
            'slug' => 'reports',
            'description' => 'Reports category',
        ]);

        $this->assertDatabaseHas(Category::TABLE_NAME, [
            'id' => $category->id,
            'name' => 'Reports',
            'slug' => 'reports',
        ]);
    }

    public function test_it_can_soft_delete_a_category(): void
    {
        $category = Category::create([
            'name' => 'Archive',
            'slug' => 'archive',
            'description' => 'Archive category',
        ]);

        $category->delete();

        $this->assertSoftDeleted(Category::TABLE_NAME, [
            'id' => $category->id,
        ]);
    }

    /**
     * A category has many documents.
     *
     * @return void
     */
    public function test_category_has_many_documents(): void
    {
        $category = Category::query()->where('slug', 'wms')->firstOrFail();

        $documentOne = Document::query()->create([
            'title' => 'WMS Guide',
            'slug' => 'wms-guide',
            'excerpt' => 'Guide',
            'markdown_body' => '# WMS Guide',
            'html_body' => '<h1>WMS Guide</h1>',
            'status' => 'draft',
            'category_id' => $category->id,
        ]);

        $documentTwo = Document::query()->create([
            'title' => 'WMS API',
            'slug' => 'wms-api',
            'excerpt' => 'API',
            'markdown_body' => '# WMS API',
            'html_body' => '<h1>WMS API</h1>',
            'status' => 'draft',
            'category_id' => $category->id,
        ]);

        $category->refresh();

        $this->assertCount(2, $category->documents);
        $this->assertTrue($category->documents->contains($documentOne));
        $this->assertTrue($category->documents->contains($documentTwo));
    }

    /**
     * A document belongs to a category.
     *
     * @return void
     */
    public function test_document_belongs_to_a_category(): void
    {
        $category = Category::query()->where('slug', 'sentinel')->firstOrFail();

        $document = Document::query()->create([
            'title' => 'Sentinel Overview',
            'slug' => 'sentinel-overview',
            'excerpt' => 'Overview',
            'markdown_body' => '# Sentinel Overview',
            'html_body' => '<h1>Sentinel Overview</h1>',
            'status' => 'draft',
            'category_id' => $category->id,
        ]);

        $document->refresh();

        $this->assertNotNull($document->category);
        $this->assertSame($category->id, $document->category->id);
        $this->assertSame('sentinel', $document->category->slug);
    }
}
