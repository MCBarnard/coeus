<?php

namespace App\Interfaces;

interface StoreInterface
{
    /**
     * Ensure that we set our store name and slug
     */
    public function setStoreName(): void;

    /**
     * Ensure that we set our paths to follow
     */
    public function initializePaths(): void;

    /**
     * Go to the store
     */
    public function goToSite();

    /**
     * Way to find a product on store site
     */
    public function findProductPrice($item);
}
