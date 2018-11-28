<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Coordinate as Coordinate;
use App\Player;

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


    /* Relations - belongs to board */
    public function board()
    {
        return $this->belongsTo('App\Board');
    }

    public function __toCoordinate()
    {
        return new Coordinate( $this->x . $this->y);
    }

    public function __toMove()
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
