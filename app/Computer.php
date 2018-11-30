<?php

namespace App;

use App\Game   as Game;
use App\Move   as Move;
use App\Player as Player;


class Computer
{
    public $game;

    public function move(Game $game)
    {
        $this->game = $game;
        $rawMove = $this->game->randomEmptySquare()->__toMove();
        $move = new Move();
        $move->as_string = $rawMove;
        $move->player    = Player::Computer;
        return $move;
    }

}
