<?php

declare(strict_types=1);

namespace Modules\Documents\Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * Class DocumentPageTest
 */
class DocumentPageTest extends DuskTestCase
{
    /**
     * Test that a document page loads successfully.
     *
     * @return void
     *
     * @throws \Throwable
     */
    public function testDocumentPageLoads(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/docs/welcome-to-sentinel-docs')
                ->pause(3000)
                ->assertPathIs('/docs/welcome-to-sentinel-docs');
        });
    }
}
