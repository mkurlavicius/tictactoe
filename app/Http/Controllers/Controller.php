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
        if(session()->has('player_id')) {
            $player = Player::find(intval(session()->get('player_id')));
            if(!empty($player)) {
                return $player;
            }
            return $this->newPlayerInTheSession();
        }
        return $this->newPlayerInTheSession();
    }

    protected function newPlayerInTheSession()
    {
        $player = new Player();
        $player->save();
        session()->put('player_id', $player->id);
        return $player;
    }
}
