<?php

namespace App\Stores\Game;

use App\Selenium\ScrapeStore;
use App\Stores\StoreInterface;
use Facebook\WebDriver\Exception\NoSuchElementException;

class Game extends ScrapeStore implements StoreInterface
{
    public function setStoreName(): void
    {
        $this->storeName = 'Game';
        $this->storeSlug = 'game';
    }

    public function initializePaths(): void
    {
        $this->paths['close-privacy'] = ['method' => 'class', 'path' => 'insider-opt-in-disallow-button'];
        $this->paths['search'] = ['method' => 'xpath', 'path' => '/html/body/div[1]/div/div/div/div/div/div/div[1]/div/div[2]/div[2]/div/div/div/div[2]/div/div/div[1]/div[2]/div/input'];
        $this->paths['submit-search'] = ['method' => 'xpath', 'path' => '/html/body/div[1]/div/div/div/div/div/div/div[1]/div/div[2]/div[2]/div/div/div/div[2]/div/div/div[1]/div[2]/div/div'];
        $this->paths['price-normal'] = ['method' => 'xpath', 'path' => '//*[@id="react-app"]/div/div/div/div/div/div/div[1]/div/div[2]/div[2]/div/div/div/div[1]/div/div/div/div/div[2]/div[3]/div[2]/div[1]'];
    }

    public function goToSite()
    {
        // Open Site
        $this->driver->get("https://www.game.co.za/");
        $this->generalWait();

        // Search for checkers in google search field
        $this->driver->findElement($this->webDriverSearch($this->paths['close-privacy']))->click();
        $this->generalWait();
    }

    public function findProductPrice ($item)
    {
        // Focus the search input and search for product
        $this->driver->findElement($this->webDriverSearch($this->paths['search']))
            ->sendKeys($item[$this->storeSlug]['exact-item-names']);

        $this->driver->findElement($this->webDriverSearch($this->paths['submit-search']))->click();
        $this->generalWait();

        // Click on product
        $this->driver->findElement($this->webDriverSearch($item[$this->storeSlug]['product-x-path']))->click();
        $this->generalWait();

        // Take a screenshot for testing purposes
        $this->takeScreenshot('no-special');

        // Fetch Price
        $price = $this->driver->findElement($this->webDriverSearch($this->paths['price-normal']))->getText();
        $this->generalWait();

        $this->recordPrice($this->storeName, $item['common-name'], $price, $item['barcode']);
    }
}
