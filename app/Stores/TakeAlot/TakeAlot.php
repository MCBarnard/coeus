<?php

namespace App\Stores\TakeAlot;

use App\Selenium\ScrapeStore;
use App\Stores\StoreInterface;
use Facebook\WebDriver\Exception\NoSuchElementException;

class TakeAlot extends ScrapeStore implements StoreInterface
{
    public function setStoreName(): void
    {
        $this->storeName = 'TakeALot';
        $this->storeSlug = 'takealot';
    }

    public function initializePaths(): void
    {
        $this->paths['search'] = ['method' => 'xpath', 'path' => '//*[@id="shopfront-app"]/header/div/div/div[2]/form/div/div[1]/input'];
        $this->paths['price-normal'] = ['method' => 'class', 'path' => 'currency'];
    }

    public function goToSite()
    {
        // Open Takealot
        $this->driver->get("https://www.takealot.com/");
        $this->generalWait();
    }

    public function findProductPrice ($item)
    {
        // Focus input and search for product
        $this->driver->findElement($this->webDriverSearch($this->paths['search']))
            ->sendKeys($item[$this->storeSlug]['exact-item-names'])->submit();

        $this->generalWait();

        // Click on product
        $this->driver->findElement($this->webDriverSearch($item[$this->storeSlug]['product-x-path']))->click();
        $this->generalWait();

        // Fetch Price
        $price = $this->driver->findElement($this->webDriverSearch($this->paths['price-normal']))->getText();
        $this->takeScreenshot('no-special');
        $this->generalWait();

        $this->recordPrice($this->storeName, $item['common-name'], $price, $item['barcode']);
    }
}
