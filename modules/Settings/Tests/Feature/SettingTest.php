<?php

namespace Modules\Settings\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Settings\Models\Setting;
use Tests\TestCase;

/**
 * Class SettingTest
 */
class SettingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * It can create a setting.
     *
     * @return void
     */
    public function test_it_can_create_a_setting(): void
    {
        $setting = Setting::query()->create([
            'key' => 'site_name',
            'value' => 'Atlas',
            'type' => 'string',
        ]);

        $this->assertNotNull($setting->id);

        $this->assertDatabaseHas(Setting::TABLE_NAME, [
            'key' => 'site_name',
            'value' => 'Atlas',
            'type' => 'string',
        ]);
    }

    /**
     * It can update a setting.
     *
     * @return void
     */
    public function test_it_can_update_a_setting(): void
    {
        $setting = Setting::query()->create([
            'key' => 'site_name',
            'value' => 'Atlas',
            'type' => 'string',
        ]);

        $setting->update([
            'value' => 'Atlas Docs',
        ]);

        $this->assertDatabaseHas(Setting::TABLE_NAME, [
            'id' => $setting->id,
            'key' => 'site_name',
            'value' => 'Atlas Docs',
            'type' => 'string',
        ]);
    }

    /**
     * It can find a setting by key.
     *
     * @return void
     */
    public function test_it_can_find_a_setting_by_key(): void
    {
        Setting::query()->create([
            'key' => 'default_theme',
            'value' => 'rtd',
            'type' => 'string',
        ]);

        $setting = Setting::query()
            ->where('key', 'default_theme')
            ->first();

        $this->assertNotNull($setting);
        $this->assertSame('rtd', $setting->value);
        $this->assertSame('string', $setting->type);
    }

    /**
     * It can store json-like values as text.
     *
     * @return void
     */
    public function test_it_can_store_json_like_values_as_text(): void
    {
        $jsonValue = json_encode([
            'brand' => 'Atlas',
            'theme' => 'rtd',
        ]);

        Setting::query()->create([
            'key' => 'ui_config',
            'value' => $jsonValue,
            'type' => 'json',
        ]);

        $setting = Setting::query()
            ->where('key', 'ui_config')
            ->firstOrFail();

        $this->assertSame($jsonValue, $setting->value);
        $this->assertSame('json', $setting->type);
    }

    /**
     * It requires a unique key.
     *
     * @return void
     */
    public function test_it_requires_a_unique_key(): void
    {
        Setting::query()->create([
            'key' => 'site_name',
            'value' => 'Atlas',
            'type' => 'string',
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Setting::query()->create([
            'key' => 'site_name',
            'value' => 'Atlas Docs',
            'type' => 'string',
        ]);
    }
}
