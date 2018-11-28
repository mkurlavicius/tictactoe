<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Move extends Model
{
    public function game()
    {
        return $this->belongsTo('App\Game');
    }

    protected $fillable = [
        'x', 'y', 'player', 'asString', 'message'
    ];

    public function __toCoordinate()
    {
        return new Coordinate($this->as_string);
    }

    public function updateSquare()
    {
        $coordinate = $this->__toCoordinate();
        $square = $this
            ->game()
            ->board()
            ->findSquareByCoordinate($coordinate);
        $square->status = $this->player;
        $square->save();
    }

    public function setNumber()
    {
        $lastMove = $this->game->board()->moves()->orderBy('number', 'desc')->first();
        if(!empty($lastMove)) {
            $this->number = $lastMove->number + 1;
        } else {
            $this->number = 1;
        }
        return true;
    }

    public function setCoordinate()
    {
        $coordinate = new Coordinate($this->as_string);
        $this->x = $coordinate->x;
        $this->y = $coordinate->y;
        return true;
    }

}
