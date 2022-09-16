<?php

namespace App\Stores\Shoprite;

use App\Interfaces\StoreCurlInterface;
use App\Selenium\CurlStore;
use DOMDocument;

class ShopriteCurl extends CurlStore implements StoreCurlInterface
{

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
        $this->curlStore($url);
        $this->saveHtml();
    }

    /**
     * Use xpath in site html to find product data
     */
    public function findProductPrice($item)
    {
        $doc = new DOMDocument();
        @$data = $doc->loadHTML($this->parseDocForValidXml($this->rawSite));
        $xpath = new \DOMXPath($doc);

        // Currently Checkers and Shoprite hide specials by inserting them via JavaScript
        // in a secondary call
        try {
            $promo = $xpath->query($this->paths['price-special']['path'])->item(0)->nodeValue;
        } catch (\Exception $e) {
            $promo = null;
        }
        $price = $xpath->query($this->paths['price-normal']['path'])->item(0)->nodeValue;
        $this->recordPrice($this->storeName, $item['common-name'], $price, $item['barcode'], $promo);
    }
}
