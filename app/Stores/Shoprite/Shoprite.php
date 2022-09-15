<?php

namespace App\Stores\Shoprite;

use App\Interfaces\StoreInterface;
use App\Selenium\ScrapeStore;
use Illuminate\Support\Facades\Storage;

class Shoprite extends ScrapeStore implements StoreInterface
{
    public function setStoreName(): void
    {
        $this->storeName = 'Shoprite';
        $this->storeSlug = 'shoprite';
    }

    public function initializePaths(): void
    {
        $this->paths['price-special'] = ['method' => 'xpath', 'path' => '//*[contains(@class, "special-price__extra__price")]'];
        $this->paths['price-normal'] = ['method' => 'xpath', 'path' => '//*[contains(@class, "special-price__price")]'];
    }

    public function goToSite()
    {
        // We will visit the site for each product to avoid being detected by bot detection
    }

    public function findProductPrice ($item)
    {
        // Directly search via the get request so that they don't detect chrome driver
        $url = "https://www.shoprite.co.za/search/all?q={$item[$this->storeSlug]['exact-item-names']}";
        $this->driver->get($url);

        // Fetch Price
        try {
            $price = $this->driver->findElement($this->webDriverSearch($this->paths['price-normal']))->getText();
            $promo = $this->driver->findElement($this->webDriverSearch($this->paths['price-special']))->getText();
            $promo = str_replace(' ', '', $promo);
            $this->takeScreenshot('special');
        } catch (\Exception $e) {
            $this->takeScreenshot('no-special');
            $price = $this->driver->findElement($this->webDriverSearch($this->paths['price-normal']))->getText();
        }
        $this->generalWait();

        if (isset($promo)) {
            $this->recordPrice($this->storeName, $item['common-name'], $price, $item['barcode'], $promo);
        } else {
            $this->recordPrice($this->storeName, $item['common-name'], $price, $item['barcode']);
        }
    }
}
