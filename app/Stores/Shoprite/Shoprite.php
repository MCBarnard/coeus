<?php

namespace App\Stores\Shoprite;

use App\Selenium\ScrapeStore;
use App\Stores\StoreInterface;
use Facebook\WebDriver\Exception\NoSuchElementException;

class Shoprite extends ScrapeStore implements StoreInterface
{
    public function setStoreName(): void
    {
        $this->storeName = 'Shoprite';
        $this->storeSlug = 'shoprite';
    }

    public function initializePaths(): void
    {
        $this->paths['search-google'] = ['method' => 'xpath', 'path' => '/html/body/div[1]/div[3]/form/div[1]/div[1]/div[1]/div/div[2]/input'];
        $this->paths['search'] = ['method' => 'xpath', 'path' => '//*[@id="js-site-search-input"]'];
        $this->paths['google-shoprite-link'] = ['method' => 'xpath', 'path' => '//h3[text()[contains(., "Shoprite ZA | Homepage")]]'];
        $this->paths['price-special'] = ['method' => 'class', 'path' => 'special-price--promotion'];
        $this->paths['price-normal'] = ['method' => 'class', 'path' => 'now'];
    }

    public function goToSite()
    {
        // Trying to kick off the navigation from Google so that
        // checkers thinks it's a bit more organic
        $this->driver->get("https://www.google.com");
        $this->generalWait();

        // Search for checkers in google search field
        $this->driver->findElement($this->webDriverSearch($this->paths['search-google']))
            ->sendKeys("Shoprite South Africa")->submit();
        $this->generalWait();

        // Click on Checkers Link
        $this->driver->findElement($this->webDriverSearch($this->paths['google-shoprite-link']))->click();
        $this->generalWait();
    }

    public function findProductPrice ($item)
    {
        // Focus the search input and search for product
        $this->driver->findElement($this->webDriverSearch($this->paths['search']))
            ->sendKeys($item[$this->storeSlug]['exact-item-names'])
            ->submit();

        // Click on product
        $this->driver->findElement($this->webDriverSearch($item[$this->storeSlug]['product-x-path']))->click();
        $this->generalWait();

        // Fetch Price
        try {
            $price = $this->driver->findElement($this->webDriverSearch($this->paths['price-special']))->getText();
            $this->takeScreenshot('special');
        } catch (NoSuchElementException $e) {
            $this->takeScreenshot('no-special');
            $price = $this->driver->findElement($this->webDriverSearch($this->paths['price-normal']))->getText();
        }
        $this->generalWait();

        $this->recordPrice($this->storeName, $item['common-name'], $price, $item['barcode']);
    }
}
