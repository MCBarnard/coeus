<?php

namespace App\Http\Controllers;

use App\Stores\Checkers\Checkers;
use App\Stores\Game\Game;
use App\Stores\Shoprite\Shoprite;
use App\Stores\PnP\PnP;
use App\Stores\Shoprite\Shoprite2;
use App\Stores\TakeAlot\TakeAlot;
use App\Stores\Woolworths\Woolworths;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ScraperController extends Controller
{
    public function scrape()
    {

//        Could be blocked at any time
//        $checkers = app()->make(Checkers::class);
//        $checkers->process();

//        Could be blocked at any time
//        $shoprite = app()->make(Shoprite::class);
//        $shoprite->process();
        $shoprite = app()->make(Shoprite2::class);
        $shoprite->process();


//        Ready to use
//        $woolworths = app()->make(Woolworths::class);
//        $woolworths->process();
//
//        $pnp = app()->make(PnP::class);
//        $pnp->process();
//
//        $game = app()->make(Game::class);
//        $game->process();

//        $takeAlot = app()->make(TakeAlot::class);
//        $takeAlot->process();

        // For demo purposes only
        // sleep(10);

        return response()->json(['data' => 'Success'], ResponseAlias::HTTP_OK);
    }
}

