<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Player as Player;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function getPlayer()
    {
        if($playerId = session()->has('player_id')) {
            $player = Player::findOrFail($playerId);
            return $player;
        } else {
            $player = new player();
            $player->save();
            session()->put('player_id', $player->id);
            return $player;
        }
    }
}
