<?php

namespace App\Interfaces;

interface StoreCurlInterface
{
    /**
     * Ensure that we set our store name and slug
     */
    public function setStoreName(): void;

    /**
     * Ensure that we set our own store xpaths
     */
    public function initializePaths(): void;

    /**
     * Find a product on store site
     */
    public function fetchProduct($item);

    /**
     * Way to find a product on store site
     */
    public function findProductPrice($item);
}
