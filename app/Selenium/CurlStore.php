<?php

namespace App\Selenium;

use Carbon\Carbon;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Faker\Generator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CurlStore
{
    protected string $rawSite;
    protected array $paths;
    protected string $storeName;
    protected string $storeSlug;

    public function __construct(Generator $faker)
    {
        $this->setStoreName();
        $this->initializePaths();
        $this->faker = $faker;
    }

    public function setStoreName(): void
    {
        // Recreate this in the store
    }

    public function initializePaths(): void
    {
        // Recreate this in the store
    }

    public function curlStore($url): void
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->faker->userAgent());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

        // Proxy through tor so that the store does not catch our ip and allows us to pass
        curl_setopt($ch, CURLOPT_PROXY, env('TOR_PROXY_URL', 'http://localhost:9050'));
        curl_setopt($ch, CURLOPT_PROXYTYPE, 7);

        // Execute request
        $this->rawSite = curl_exec($ch);
        curl_close($ch);
    }

    public function saveHtml($directory='general'): void
    {
        $fileName = Carbon::now()->format('d-m-Y_H:i:s') . ".html";
        Storage::put("scraped/{$directory}/$this->storeSlug/" . $fileName, $this->rawSite);
    }

    public function recordPrice($store, $productCommonName, $price, $barcode, $promo=null): void
    {
        Log::debug($store . ' has ' . $productCommonName . ' for ' . $price . '. Barcode: ' . $barcode);
        if (!blank($promo)) {
            Log::debug($store . ' has a promotion for ' . $productCommonName . ' : ' . $promo);
        }
    }

    public function process($close=true): void
    {
        try {
            $products = $this->fetchAndFormatProducts();
            foreach ($products as $product) {
                $this->fetchProduct($product);
                $this->findProductPrice($product);
            }
        } catch (\Exception $e) {
            Log::debug($e);
        }
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

    public function parseDocForValidXml($html): array|string
    {
        $html = str_replace('&', ' &amp; ', $html);
        $html = str_replace('<googletagmanager:iframe/>', '', $html);
        $html = str_replace('main', 'div', $html);
        $html = str_replace('header', 'div', $html);
        $html = str_replace('svg', 'div', $html);
        $html = str_replace('<g', '<div', $html);
        $html = str_replace('</g>', '</div>', $html);
        $html = str_replace('path', 'div', $html);
        $html = str_replace('nav', 'div', $html);
        $html = str_replace('picture', 'div', $html);
        $html = str_replace('source', 'div', $html);
        $html = str_replace('type="text/javascript"', '', $html);
        $html = str_replace('type="hidden"', '', $html);
        $html = str_replace('type="text"', '', $html);
        $html = str_replace('type="number"', '', $html);
        $html = str_replace('polygon', 'div', $html);
        $html = str_replace('figure', 'div', $html);
        $html = str_replace('figcaption', 'div', $html);
        $html = str_replace('footer', 'div', $html);
        $html = str_replace('{', '', $html);
        $html = str_replace('}', '', $html);
        $html = str_replace('id', 'data-' . Str::random(10), $html);
        return $html;
    }

    public function noProduct($productName)
    {
        Log::debug("We could not find {$productName} on the {$this->storeName} website...");
    }
}
