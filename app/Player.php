<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Game as Game;

class Player extends Model
{

    const Computer = 1;
    const Human    = 2;
    const Opponent = 3;

    public function games()
    {
        return $this->hasMany('App\Game');
    }

}
