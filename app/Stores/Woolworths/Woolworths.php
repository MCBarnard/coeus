<?php

namespace App\Stores\Woolworths;

use App\Interfaces\StoreInterface;
use App\Selenium\ScrapeStore;
use Exception;

class Woolworths extends ScrapeStore implements StoreInterface
{

    /**
     * Housekeeping
     */
    public function setStoreName(): void
    {
        $this->storeName = 'Woolworths';
        $this->storeSlug = 'woolworths';
    }

    /**
     * Scaffolds the x-paths
     */
    public function initializePaths(): void
    {
        $this->paths['search'] = ['method' => 'xpath', 'path' => '//*[@id="fldSearch"]'];
        $this->paths['search-btn'] = ['method' => 'class', 'path' => 'icon--search-black'];
        $this->paths['price-special'] = ['method' => 'class', 'path' => 'buySavePrice'];
        $this->paths['price-normal'] = ['method' => 'class', 'path' => 'price'];
    }

    /**
     * Gets used to open up the store
     */
    public function goToSite()
    {
        // Open store
        $this->driver->get("https://www.woolworths.co.za/");
        $this->generalWait();
    }

    /**
     * Finds products for this specific site structure
     */
    public function findProductPrice($item)
    {
        $this->generalWait(10);
        // Focus the search input and type product name
        try {
            $this->driver->findElement($this->webDriverSearch($this->paths['search']))
                ->sendKeys($item[$this->storeSlug]['exact-item-names']);
        } catch (Exception $e) {
            // Get the store to display the search field
            $this->driver->executeScript('
                let element = document.querySelector(".search-bar.fly-out");
                element.className += " is-open";
            ');
            $this->driver->findElement($this->webDriverSearch($this->paths['search']))
                ->sendKeys($item[$this->storeSlug]['exact-item-names']);
        }
        $this->generalWait(2);

        try {
            // Don't send because Woolworths has some animations going on
            $this->driver->findElement($this->webDriverSearch($this->paths['search-btn']))->click();
        } catch(Exception $e) {
            $this->driver->findElement($this->webDriverSearch($this->paths['search']))
                ->submit();
        }
        $this->generalWait(2);

        // Hide the Shop By department drop down
        // This is in the way when we submit and causes the rest to be un-clickable
        $this->driver->executeScript('
            let element = document.querySelector(".main-nav__list.main-nav__list--primary");
            element.style.display = "none";
            let element2 = document.getElementById("frmSiteSearch");
            element2.style.display = "none";
        ');
        $this->generalWait(2);

        // Click on product
        $this->driver->findElement($this->webDriverSearch($item[$this->storeSlug]['product-x-path']))->click();
        $this->generalWait();

        // No Specials
        $price = $this->driver->findElement($this->webDriverSearch($this->paths['price-normal']))->getText();
        $promo = null;

        try {
            $promo = $this->driver->findElement($this->webDriverSearch($this->paths['price-special']))->getText();
            $this->takeScreenshot('special');
        } catch (Exception $e) {
            $this->takeScreenshot('no-special');
        }
        $this->generalWait();

        // Record the price
        $this->recordPrice($this->storeName, $item['common-name'], $price, $item['barcode'], $promo);
    }
}
