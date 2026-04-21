<?php

namespace Modules\Categories\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Categories\Models\Category;

class CategoriesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::query()->updateOrCreate(
            ['slug' => 'wms'],
            [
                'name' => 'WMS - Warehouse Mangement System',
                'description' => 'Warehouse Managment System',
            ]
        );

        Category::query()->updateOrCreate(
            ['slug' => 'sentinel'],
            [
                'name' => 'Sentinel',
                'description' => 'Sentinel System',
            ]
        );
    }
}
