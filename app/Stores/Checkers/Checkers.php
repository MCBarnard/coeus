<?php

namespace App\Stores\Checkers;

use App\Interfaces\StoreInterface;
use App\Selenium\ScrapeStore;

class Checkers extends ScrapeStore implements StoreInterface
{
    public function setStoreName(): void
    {
        $this->storeName = 'Checkers';
        $this->storeSlug = 'checkers';
    }

    public function initializePaths(): void
    {
        $this->paths['price-extra-savings-normal'] = ['method' => 'xpath', 'path' => '//*[contains(@class, "special-price__price")]'];
        $this->paths['price-extra-savings'] = ['method' => 'class', 'path' => 'special-price__extra__price'];
        $this->paths['price-normal'] = ['method' => 'class', 'path' => 'special-price'];
    }

    public function goToSite()
    {
        // We will visit the site for each product to avoid being detected by bot detection
    }

    public function findProductPrice ($item)
    {
        // Directly search via the get request so that they don't detect chrome driver
        $this->driver->get("https://www.checkers.co.za/search/all?q={$item[$this->storeSlug]['exact-item-names']}");
        $this->generalWait();

        // Fetch Price
        try {
            $price = $this->driver->findElement($this->webDriverSearch($this->paths['price-extra-savings-normal']))->getText();
            $promo = $this->driver->findElement($this->webDriverSearch($this->paths['price-extra-savings']))->getText();

            $this->takeScreenshot('special');

        } catch (\Exception $e) {
            $this->takeScreenshot('no-special');
            $price = $this->driver->findElement($this->webDriverSearch($this->paths['price-normal']))->getText();
        }
        $this->generalWait();

        if (isset($promo)) {
            $promo = str_replace(' ', '', $promo);
            $this->recordPrice($this->storeName, $item['common-name'], $price, $item['barcode'], $promo);
        } else {
            $this->recordPrice($this->storeName, $item['common-name'], $price, $item['barcode']);
        }
    }
}
