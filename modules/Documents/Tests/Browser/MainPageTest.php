<?php

declare(strict_types=1);

namespace Modules\Documents\Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * Class MainPageTest
 */
class MainPageTest extends DuskTestCase
{
    /**
     * Test that the main page loads successfully.
     *
     * @return void
     */
    public function testMainPageLoads(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/')
                ->assertPathIs('/')
                ->assertSee('Atlas');
        });
    }
}
