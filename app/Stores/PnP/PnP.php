<?php

namespace App\Stores\PnP;

use App\Interfaces\StoreInterface;
use App\Selenium\ScrapeStore;
use Facebook\WebDriver\Exception\NoSuchElementException;

class PnP extends ScrapeStore implements StoreInterface
{
    public function setStoreName(): void
    {
        $this->storeName = 'Pick n Pay';
        $this->storeSlug = 'pnp';
    }

    public function initializePaths(): void
    {
        $this->paths['close-privacy'] = ['method' => 'xpath', 'path' => '//*[@id="myModal"]/div/div/div[3]/button[1]'];
        $this->paths['pnp-search'] = ['method' => 'xpath', 'path' => '//*[@id="js-site-search-input"]'];
        $this->paths['price-savings'] = ['method' => 'class', 'path' => 'product-price'];
        $this->paths['price-normal'] = ['method' => 'class', 'path' => 'normalPrice'];
        $this->paths['bundle'] = ['method' => 'class', 'path' => 'promotion'];
    }

    public function goToSite()
    {
        // Open Site
        $this->driver->get("https://www.pnp.co.za/pnpstorefront/pnp/en/");
        $this->generalWait();

        // Search for checkers in google search field
        $this->driver->findElement($this->webDriverSearch($this->paths['close-privacy']))->click();
        $this->generalWait();
    }

    public function findProductPrice ($item)
    {
        // Focus the search input and search for product
        $this->driver->findElement($this->webDriverSearch($this->paths['pnp-search']))
            ->sendKeys($item[$this->storeSlug]['exact-item-names'])->submit();
        $this->generalWait();

        // Click on product
        $this->driver->findElement($this->webDriverSearch($item[$this->storeSlug]['product-x-path']))->click();
        $this->generalWait();

        $promo = null;

        // Fetch Price
        try {
            $promo = $this->driver->findElement($this->webDriverSearch($this->paths['bundle']))->getText();
            $priceText = $this->driver->findElement($this->webDriverSearch($this->paths['price-savings']))->getText();
            $priceCents = intval(trim(str_replace('R', '', $priceText)));
            $price = "R" . $priceCents / 100;
            $this->takeScreenshot('special');
        } catch (NoSuchElementException $e) {
            $this->takeScreenshot('no-special');
            $priceText = $this->driver->findElement($this->webDriverSearch($this->paths['price-normal']))->getText();
            $priceCents = intval(trim(str_replace('R', '', $priceText)));
            $price = "R" . $priceCents / 100;
        }

        if ($price === "R0") {
            $priceText = $this->driver->findElement($this->webDriverSearch($this->paths['price-normal']))->getText();
            $priceCents = intval(trim(str_replace('R', '', $priceText)));
            $price = "R" . $priceCents / 100;
        }

        $this->generalWait();

        $this->recordPrice($this->storeName, $item['common-name'], $price, $item['barcode'], $promo);
    }
}
