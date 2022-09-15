<?php

namespace App\Stores\Shoprite;

use Faker\Generator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Shoprite2 implements \App\Interfaces\StoreCurlInterface
{
    protected string $rawSite;
    protected array $paths;

    public function __construct(Generator $faker)
    {
        $this->setStoreName();
        $this->initializePaths();
        $this->rawSite = '';
        $this->faker = $faker;
    }

    /**
     * Set the store name and slugs
     */
    public function setStoreName(): void
    {
        $this->storeName = 'Shoprite';
        $this->storeSlug = 'shoprite';
    }

    /**
     * Set product xpaths
     */
    public function initializePaths(): void
    {
        $this->paths['price-special'] = ['method' => 'xpath', 'path' => '//*[contains(@class, "special-price__extra__price")]'];
        $this->paths['price-normal'] = ['method' => 'xpath', 'path' => '//*[contains(@class, "special-price__price")]'];
    }

    /**
     * Fetch the contents of the HTML page to avoid detection
     */
    public function fetchProduct($item)
    {
        // Directly search via the get request so that they don't detect chrome driver
        $url = "https://www.shoprite.co.za/search/all?q={$item[$this->storeSlug]['exact-item-names']}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->faker->userAgent());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $this->rawSite = curl_exec($ch);
        curl_close($ch);
    }

    /**
     * Use xpath in site html to find product data
     */
    public function findProductPrice()
    {
        $doc = new \DOMDocument();
        $doc->preserveWhiteSpace = false;
        Storage::put("ScrapedRaw/raw_site.html", $this->rawSite);
        $doc->loadHTML( Storage::get('ScrapedRaw/raw_site.html'), LIBXML_HTML_NOIMPLIED);

        $xpath = new \DOMXPath($doc);
        $data = $xpath->query($this->paths['price-special']['path'])->item(0);

        Log::debug("======================================");
        Log::debug(print_r($data, true));
        Log::debug("======================================");

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
        try {
            $products = $this->fetchAndFormatProducts();
            foreach ($products as $product) {
                $this->fetchProduct($product);
                $this->findProductPrice();
            }
        } catch (\Exception $e) {
            Log::debug($e);
        }
    }
}
