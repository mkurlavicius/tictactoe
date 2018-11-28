<?php

namespace App;


class Computer
{
    public $board;
    public $game;


    public function move($game)
    {
        $this->game  = $game;
        $this->board = $game->board;
        return $this->board->randomEmptySquare()->to_move();
    }

}
