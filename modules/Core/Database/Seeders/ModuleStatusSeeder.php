<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ModuleStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $tableName = 'module_statuses';

        if (!Schema::hasTable($tableName)) {
            $this->command?->warn("Table [{$tableName}] does not exist. Seeder skipped.");
            return;
        }

        $columns = Schema::getColumnListing($tableName);

        $modules = [
            'Admin',
            'Auditable',
            'Categories',
            'Core',
            'CoreUI',
            'Documents',
            'Settings',
            'Users',
        ];

        foreach ($modules as $moduleName) {
            $lookup = $this->buildLookup($columns, $moduleName);
            $values = $this->buildValues($columns, $moduleName);

            if (empty($lookup)) {
                $this->command?->warn("No usable key column found for [{$tableName}]. Seeder skipped.");
                return;
            }

            DB::table($tableName)->updateOrInsert($lookup, $values);
        }
    }

    /**
     * Build the lookup portion for updateOrInsert.
     *
     * @param array<int, string> $columns
     * @param string $moduleName
     * @return array<string, mixed>
     */
    private function buildLookup(array $columns, string $moduleName): array
    {
        if (in_array('module', $columns, true)) {
            return ['module' => $moduleName];
        }

        if (in_array('name', $columns, true)) {
            return ['name' => $moduleName];
        }

        if (in_array('slug', $columns, true)) {
            return ['slug' => strtolower($moduleName)];
        }

        return [];
    }

    /**
     * Build the value portion for updateOrInsert.
     *
     * @param array<int, string> $columns
     * @param string $moduleName
     * @return array<string, mixed>
     */
    private function buildValues(array $columns, string $moduleName): array
    {
        $values = [];

        if (in_array('module', $columns, true)) {
            $values['module'] = $moduleName;
        }

        if (in_array('name', $columns, true)) {
            $values['name'] = $moduleName;
        }

        if (in_array('slug', $columns, true)) {
            $values['slug'] = strtolower($moduleName);
        }

        if (in_array('enabled', $columns, true)) {
            $values['enabled'] = 1;
        }

        if (in_array('is_enabled', $columns, true)) {
            $values['is_enabled'] = 1;
        }

        if (in_array('status', $columns, true)) {
            $values['status'] = 'enabled';
        }

        if (in_array('created_at', $columns, true)) {
            $values['created_at'] = now();
        }

        if (in_array('updated_at', $columns, true)) {
            $values['updated_at'] = now();
        }

        return $values;
    }
}
