<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Board    as Board;
use App\Computer as Computer;
use App\Player   as Player;
use App\Move     as Move;


class Game extends Model
{
    public function board()
    {
        return $this->hasOne('App\Board');
    }

    public function moves()
    {
        return $this->hasMany('App\Move');
    }

    protected $fillable = ['started_by'];

    public function start($size = 3, $goes = 'computer')
    {
        if($this->exists()) {
            echo("The game has already started, continue to play");
            $this->play();
        } else {

        }
    }



    public function movesPlayer($move)
    {
        $playersMove = new Move(['asString' => $move, 'player' => Player::Human]);
        $this->moves()->save($playersMove);
    }

    public function movesComputer()
    {
        $asString = (new Computer($this))->move();
        echo "Computer chose the move (${asString})";
        $computersMove = new Move(['asString' => $asString, 'player' => Player::Computer]);
        $this->moves()->save($computersMove);
    }

    public function whoGoes(string $string)
    {
        switch($string)
        {
            case 'computer':
                return Player::Computer;
            case 'human':
                return Player::Human;
            case 'opponent':
                return Player::Opponent;
            default:
                throw new \Exception("Plaayer ${string} is not supported");

        }
    }

    public function createBoard($size)
    {
        $this->board = new Board(['size' => $size]);
        $this->board->save();
        $this->board->createSquares();
    }




//def start(size = 3, goes = :computer)
//if !self.new_record?
//puts "The game has already started, continue to play"
//play
//else
//self.by = who_goes(goes)
//self.prepare_board(size)
//self.save
//play
//end
//end
}
