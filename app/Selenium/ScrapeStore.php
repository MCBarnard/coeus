<?php

namespace App\Selenium;

use Carbon\Carbon;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ScrapeStore
{
    protected string $seleniumHost;
    protected RemoteWebDriver $driver;
    protected array $paths;
    protected string $storeName;
    protected string $storeSlug;

    public function __construct()
    {
        // Stores have these methods present, polymorphic calls
        $this->initializePaths();
        $this->setStoreName();

        // Fetch the chrome driver instance
        $this->seleniumHost = env('CHROME_DRIVER_HOST', 'http://localhost:9595');

        // Set browser settings
        $our_user_agent = array( '--user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4324.104 Safari/537.36' );

        $options = new ChromeOptions();
        $options->setExperimentalOption("excludeSwitches", array("enable-automation"));
        $options->addArguments( $our_user_agent );

        $flags = explode(",", env('DRIVER_FLAGS'));
        foreach ($flags as $flag) {
            $options->addArguments([$flag]);
        }

        if (env('RUN_CHROMEDRIVER_HEADLESS', false)) {
            $options->addArguments(["--headless"]);
        }

        $capabilities = DesiredCapabilities::chrome();
        $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);

        $this->driver = RemoteWebDriver::create(
            $this->seleniumHost,
            $capabilities,
            60 * 10000, // Connection timeout in milliseconds
            60 * 10000  // Request timeout in milliseconds
        );
    }

    public function webDriverSearch($path): WebDriverBy|bool
    {
        return match ($path['method']) {
            'id' => WebDriverBy::id($path['path']),
            'xpath' => WebDriverBy::xpath($path['path']),
            'cssSelect', 'css' => WebDriverBy::cssSelector($path['path']),
            'name' => WebDriverBy::name($path['path']),
            'class', 'className' => WebDriverBy::className($path['path']),
            'tag', 'tagName' => WebDriverBy::tagName($path['path']),
            'link' => WebDriverBy::linkText($path['path']),
            'partial', 'partialLink', 'partialLinkText' => WebDriverBy::partialLinkText($path['path']),
            default => false,
        };
    }

    public function closeChromeDriver()
    {
        $this->driver->quit();
    }

    public function handleElementNotFound($exception)
    {
        Log::alert("Error: Could not find element...");
        Log::alert(print_r($exception->getMessage(), true));
        $this->takeScreenshot('element-not-found');
        $this->closeChromeDriver();
    }

    public function generalException($exception)
    {
        Log::alert("Error: General Exception Thrown...");
        Log::alert(print_r($exception, true));
        $this->takeScreenshot();
        $this->closeChromeDriver();

    }

    public function takeScreenshot($directory='general'): void
    {
        $screenshot = Carbon::now()->format('d-m-Y_H:i:s') . '-' . $this->driver->getSessionID() . ".png";
        Storage::put("screenshots/{$directory}/$this->storeSlug/" . $screenshot, $this->driver->takeScreenshot());
    }

    public function recordPrice($store, $productCommonName, $price, $barcode, $promo=null)
    {
        Log::debug($store . ' has ' . $productCommonName . ' for ' . $price . '. Barcode: ' . $barcode);
        if (!blank($promo)) {
            Log::debug($store . ' has a promotion for ' . $productCommonName . ' : ' . $promo);
        }
    }

    public function generalWait($time=5) {
        sleep(floatval($time) . '.' . rand());
    }

    public function fetchAndFormatProducts(): array
    {
        $list = array();
        foreach (config('watchlist.' . env('PRODUCT_LIST', 'products')) as $key => $product) {
            if (isset($product[$this->storeSlug])) {
                array_push($list, $product);
            }
        }
        return $list;
    }

    public function process($close=true): void
    {
        // Open up Store
        $this->goToSite();

        try {
            $products = $this->fetchAndFormatProducts();
            foreach ($products as $product) {
                $this->findProductPrice($product);
            }
        } catch (NoSuchElementException $e) {
            $this->handleElementNotFound($e);
        } catch (\Exception $e) {
            $this->generalException($e);
        }
        // Close the driver
        if ($close) {
            $this->closeChromeDriver();
        }
    }
}
