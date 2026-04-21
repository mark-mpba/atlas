<?php

namespace Modules\Documents\Tests\Feature;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Categories\Database\Seeders\CategoriesDatabaseSeeder;
use Modules\Categories\Models\Category;
use Modules\Documents\Models\Document;
use Tests\TestCase;

/**
 * Class DocumentTest
 */
class DocumentTest extends TestCase
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
     * It can create a document.
     *
     * @return void
     */
    public function test_it_can_create_a_document(): void
    {
        $category = Category::query()->where('slug', 'wms')->firstOrFail();

        $document = Document::query()->create([
            'title' => 'Warehouse Guide',
            'slug' => 'warehouse-guide',
            'excerpt' => 'Guide',
            'markdown_body' => '# Warehouse Guide',
            'html_body' => '<h1>Warehouse Guide</h1>',
            'status' => 'draft',
            'is_featured' => false,
            'category_id' => $category->id,
        ]);

        $this->assertNotNull($document->id);

        $this->assertDatabaseHas(Document::TABLE_NAME, [
            'title' => 'Warehouse Guide',
            'slug' => 'warehouse-guide',
            'status' => 'draft',
            'category_id' => $category->id,
        ]);
    }

    /**
     * It casts is_featured as boolean.
     *
     * @return void
     */
    public function test_it_casts_is_featured_as_boolean(): void
    {
        $document = Document::query()->create([
            'title' => 'Featured Doc',
            'slug' => 'featured-doc',
            'excerpt' => 'Featured',
            'markdown_body' => '# Featured',
            'html_body' => '<h1>Featured</h1>',
            'status' => 'draft',
            'is_featured' => 1,
        ]);

        $document->refresh();

        $this->assertIsBool($document->is_featured);
        $this->assertTrue($document->is_featured);
    }

    /**
     * It casts published_at as datetime.
     *
     * @return void
     */
    public function test_it_casts_published_at_as_datetime(): void
    {
        $publishedAt = now();

        $document = Document::query()->create([
            'title' => 'Published Doc',
            'slug' => 'published-doc',
            'excerpt' => 'Published',
            'markdown_body' => '# Published',
            'html_body' => '<h1>Published</h1>',
            'status' => 'published',
            'published_at' => $publishedAt,
        ]);

        $document->refresh();

        $this->assertNotNull($document->published_at);
        $this->assertSame($publishedAt->format('Y-m-d H:i'), $document->published_at->format('Y-m-d H:i'));
    }

    /**
     * It belongs to a category.
     *
     * @return void
     */
    public function test_it_belongs_to_a_category(): void
    {
        $category = Category::query()->where('slug', 'sentinel')->firstOrFail();

        $document = Document::query()->create([
            'title' => 'Sentinel Manual',
            'slug' => 'sentinel-manual',
            'excerpt' => 'Manual',
            'markdown_body' => '# Sentinel Manual',
            'html_body' => '<h1>Sentinel Manual</h1>',
            'status' => 'draft',
            'category_id' => $category->id,
        ]);

        $document->refresh();

        $this->assertNotNull($document->category);
        $this->assertSame($category->id, $document->category->id);
        $this->assertSame('sentinel', $document->category->slug);
    }

    /**
     * It can exist without a category.
     *
     * @return void
     */
    public function test_it_can_exist_without_a_category(): void
    {
        $document = Document::query()->create([
            'title' => 'Uncategorised Doc',
            'slug' => 'uncategorised-doc',
            'excerpt' => 'Uncategorised',
            'markdown_body' => '# Uncategorised Doc',
            'html_body' => '<h1>Uncategorised Doc</h1>',
            'status' => 'draft',
            'category_id' => null,
        ]);

        $document->refresh();

        $this->assertNull($document->category_id);
        $this->assertNull($document->category);
    }

    /**
     * It can be soft deleted.
     *
     * @return void
     */
    public function test_it_can_be_soft_deleted(): void
    {
        $document = Document::query()->create([
            'title' => 'Old Doc',
            'slug' => 'old-doc',
            'excerpt' => 'Old',
            'markdown_body' => '# Old Doc',
            'html_body' => '<h1>Old Doc</h1>',
            'status' => 'draft',
        ]);

        $document->delete();

        $this->assertSoftDeleted(Document::TABLE_NAME, [
            'id' => $document->id,
        ]);
    }

    /**
     * It requires a unique slug.
     *
     * @return void
     */
    public function test_it_requires_a_unique_slug(): void
    {
        Document::query()->create([
            'title' => 'Doc One',
            'slug' => 'duplicate-slug',
            'excerpt' => 'One',
            'markdown_body' => '# One',
            'html_body' => '<h1>One</h1>',
            'status' => 'draft',
        ]);

        $this->expectException(QueryException::class);

        Document::query()->create([
            'title' => 'Doc Two',
            'slug' => 'duplicate-slug',
            'excerpt' => 'Two',
            'markdown_body' => '# Two',
            'html_body' => '<h1>Two</h1>',
            'status' => 'draft',
        ]);
    }
}
