<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Coordinate as Coordinate;
use App\Player     as Player;

class Square extends Model
{
    /* Possible square status */
    const Empty      = 0;
    const Computer   = Player::Computer;
    const Human      = Player::Human;
    const Opponent   = Player::Opponent;

    protected $fillable = [
        'x', 'y', 'status',
    ];

    public $timestamps = false;


    public function game()
    {
        return $this->belongsTo('App\Game');
    }

    public function __toCoordinate()
    {
        return new Coordinate( $this->x . $this->y);
    }

    public function __toMove()
    {
        return $this->x . $this->y;
    }

    public function __toString()
    {
        switch($this->status) {
            case Square::Empty:
                return " ";
            case Square::Opponent:
            case Square::Computer:
                return "o";
            case Square::Human:
                return "x";
            default:
                throw new \Exception("Square status is not supported");
        }

    }

}
