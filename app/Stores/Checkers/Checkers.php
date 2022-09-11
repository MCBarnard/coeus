<?php

namespace App\Stores\Checkers;

use App\Selenium\ScrapeStore;
use App\Stores\StoreInterface;
use Carbon\Carbon;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Illuminate\Support\Facades\Storage;

class Checkers extends ScrapeStore implements StoreInterface
{
    public function setStoreName(): void
    {
        $this->storeName = 'Checkers';
        $this->storeSlug = 'checkers';
    }

    public function initializePaths(): void
    {
        $this->paths['search-google'] = ['method' => 'xpath', 'path' => '/html/body/div[1]/div[3]/form/div[1]/div[1]/div[1]/div/div[2]/input'];
        $this->paths['google-checkers-link'] = ['method' => 'xpath', 'path' => '//*[@id="rso"]/div[1]/div/div/div/div/div/div/div/div[1]/a'];
        $this->paths['checkers-search'] = ['method' => 'xpath', 'path' => '//*[@id="js-site-search-input"]'];
        $this->paths['price-extra-savings'] = ['method' => 'class', 'path' => 'special-price__extra__price'];
        $this->paths['price-normal'] = ['method' => 'class', 'path' => 'special-price'];
    }

    public function goToSite()
    {
        // Trying to kick off the navigation from Google so that
        // checkers thinks it's a bit more organic
        $this->driver->get("https://www.google.com");
        $this->generalWait(7);

        // Search for checkers in google search field
        $this->driver->findElement($this->webDriverSearch($this->paths['search-google']))
            ->sendKeys("Checkers South Africa")->submit();

        $this->generalWait(7);

        // Click on Checkers Link
        $this->driver->findElement($this->webDriverSearch($this->paths['google-checkers-link']))->click();
        $this->generalWait();
    }

    public function findProductPrice ($item)
    {
        // Focus input and search for product
        $this->driver->findElement($this->webDriverSearch($this->paths['checkers-search']))
            ->sendKeys($item[$this->storeSlug]['exact-item-names'])->submit();
        $this->generalWait();

        // Click on product
        $this->driver->findElement($this->webDriverSearch($item[$this->storeSlug]['product-x-path']))->click();
        $this->generalWait();

        // Fetch Price
        try {
            $price = $this->driver->findElement($this->webDriverSearch($this->paths['price-extra-savings']))->getText();
            $this->takeScreenshot('special');
        } catch (NoSuchElementException $e) {
            $this->takeScreenshot('no-special');
            $price = $this->driver->findElement($this->webDriverSearch($this->paths['price-normal']))->getText();
        }
        $this->generalWait();

        $this->recordPrice($this->storeName, $item['common-name'], $price, $item['barcode']);
    }
}
