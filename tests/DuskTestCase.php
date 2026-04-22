<?php

declare(strict_types=1);

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Laravel\Dusk\TestCase as BaseTestCase;

/**
 * Class DuskTestCase
 */
abstract class DuskTestCase extends BaseTestCase
{
    /**
     * Start ChromeDriver before the test class runs.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        if (! static::runningInSail()) {
            static::startChromeDriver([
                '--port=9515',
                '--verbose',
            ]);
        }
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver(): RemoteWebDriver
    {
        $options = (new ChromeOptions())->addArguments([
            '--window-size=1920,1080',
            '--disable-search-engine-choice-screen',
            '--disable-smooth-scrolling',
            '--disable-gpu',
            '--headless=new',
            '--no-sandbox',
        ]);

        $options->setBinary('/Applications/Google Chrome.app/Contents/MacOS/Google Chrome');

        return RemoteWebDriver::create(
            'http://127.0.0.1:9515',
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY,
                $options
            )
        );
    }
}
