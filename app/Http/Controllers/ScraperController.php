<?php

namespace App\Http\Controllers;

use App\Stores\Checkers\Checkers;
use App\Stores\Game\Game;
use App\Stores\Shoprite\Shoprite;
use App\Stores\PnP\PnP;
use App\Stores\Woolworths\Woolworths;

class ScraperController extends Controller
{
    public function scrape() {
//        $checkers = app()->make(Checkers::class);
//        $checkers->process();
//
//        $pnp = app()->make(PnP::class);
//        $pnp->process();
//
//        $game = app()->make(Game::class);
//        $game->process();
//
//        $shoprite = app()->make(Shoprite::class);
//        $shoprite->process();

        $woolworths = app()->make(Woolworths::class);
        $woolworths->process();

        return view("scraped", ['data' => 'All good in the hood!']);
    }
}
