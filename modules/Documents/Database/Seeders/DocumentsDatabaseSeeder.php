<?php

namespace Modules\Documents\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Categories\Models\Category;
use Modules\Documents\Models\Document;

class DocumentsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = Category::query()->updateOrCreate(

            ['slug' => 'sentinel-epro'],
            [
                'name' => 'Sentinel EPRO',
                'description' => 'Sentinel EPRO documentation section',
                'sort_order' => 10,
                'show_in_nav' => true,

            ]

        );

        Document::query()->updateOrCreate(

            ['slug' => 'home'],
            [
                'title' => 'Welcome to Sentinel Docs',
                'excerpt' => 'Sentinel home page',
                'markdown_body' => '# Welcome to Sentinel Docs',
                'html_body' => '<h1>Welcome to Sentinel Docs</h1>',
                'status' => 'published',
                'category_id' => $category->id,
                'sort_order' => 0,
                'show_in_nav' => false,
                'is_home' => true,
            ]

        );

        Document::query()->updateOrCreate(

            ['slug' => 'commit-messages'],
            [
                'title' => 'Commit Messages',
                'excerpt' => 'Commit message guidance',
                'markdown_body' => '## Commit Messages',
                'html_body' => '<h2>Commit Messages</h2>',
                'status' => 'published',
                'category_id' => $category->id,
                'sort_order' => 10,
                'show_in_nav' => true,
                'is_home' => false,
            ]

        );

        Document::query()->updateOrCreate(

            ['slug' => 'production-branches'],
            [
                'title' => 'Production Branches',
                'excerpt' => 'Production branch guidance',
                'markdown_body' => '## Production Branches',
                'html_body' => '<h2>Production Branches</h2>',
                'status' => 'published',
                'category_id' => $category->id,
                'sort_order' => 20,
                'show_in_nav' => true,
                'is_home' => false,
            ]
        );
    }
}
