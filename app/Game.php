<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Computer as Computer;
use App\Player   as Player;
use App\Move     as Move;
use App\Line     as Line;


class Game extends Model
{

    protected $fillable = ['started_by', 'size'];

    public function player()
    {
        return $this->belongsTo('App\Player');
    }

    public function moves()
    {
        return $this->hasMany('App\Move');
    }

    public function squares()
    {
        return $this->hasMany('App\Square');
    }

    public function bound()
    {
        return $this->size - 1;
    }

    public function axis()
    {
        return range(0, $this->bound());
    }

    public function reversedAxis()
    {
        return array_reverse($this->axis());
    }

    public function finish()
    {
        $this->is_finished = true;
        $this->save();
    }

    public function iterateCoordinates($functionForCoordinates)
    {
        foreach ($this->axis() as $yCoordinate) {
            foreach ($this->axis() as $xCoordinate) {
                $functionForCoordinates($yCoordinate, $xCoordinate);
            }
        }
    }

    public function iterateForTheView($functionForCoordinates)
    {
        foreach ($this->reversedAxis() as $yCoordinate) {
            foreach ($this->axis() as $xCoordinate) {
                $functionForCoordinates($yCoordinate, $xCoordinate);
            }
        }
    }

    public function createSquares()
    {
        $this->iterateCoordinates(function($yCoordinate, $xCoordinate) {
            $this->squares()->save(new Square([
                'x'      => $xCoordinate,
                'y'      => $yCoordinate,
                'status' => Square::Empty
            ]));
        });
    }

    public function randomEmptySquare()
    {
        return $this
            ->squares()
            ->where(['status' => Square::Empty])
            ->get()
            ->random();
    }

    public function findSquareByCoordinate(Coordinate $coordinate)
    {
        return $this
            ->squares()
            ->where(['x' => $coordinate->x, 'y' => $coordinate->y])
            ->first();
    }

    public function findSquareByCoordinates($y, $x)
    {
        return $this
            ->squares()
            ->where(['x' => $x, 'y' => $y])
            ->first();
    }

    public function isInBounds($item, $coordinate)
    {
        if(is_a($coordinate, 'App\Coordinate'))
        {
            return
                $this->inBound($item, $coordinate->x) &&
                $this->inBound($item, $coordinate->y);
        } elseif (is_string($coordinate)) {
            return $this->inBound($item, intval($coordinate));
        } elseif (is_int($coordinate)) {
            return $this->inBound($item, $coordinate);
        } else {
            throw new \Exception("Coordinate type is not supported");
        }

    }

    public function inBound($item, $number)
    {
        if(!($number >= 0 && $number <= $this->bound()))
        {
            throw new \Exception("${item} is out of bounds == ${number}");
        } else {
            return true;
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

    public function humanHasWon()
    {
        foreach ($this->allLines() as $line) {
            if($line->isFullHuman()) {
                $this->updateWinningSquares($line);
                return true;
            }
        }
        return false;
    }

    public function computerHasWon()
    {
        foreach ($this->allLines() as $line) {
            if($line->isFullComputer()) {
                $this->updateWinningSquares($line);
                return true;
            }
        }
        return false;
    }

    public function updateWinningSquares($line) {
        foreach ($line->squares as $square) {
            $square->is_winning = true;
            $square->save();
        }
    }

    public function whoGoes(string $string)
    {
        switch($string)
        {
            case 'computer':
                $this->started_by = Player::Computer;
                break;
            case 'human':
                $this->started_by = Player::Human;
                break;
            case 'opponent':
                $this->started_by = Player::Opponent;
            default:
                throw new \Exception("Player ${string} is not supported");
                break;

        }
    }

    protected function horizontalLines()
    {
        $lines = [];
        foreach ($this->axis() as $coordinateY) {
            $squaresInLine = [];
            foreach ($this->axis() as $coordinateX) {
                $squaresInLine[] = $this->findSquareByCoordinates($coordinateX, $coordinateY);
            }
            $lines[] = new Line($squaresInLine, $this->size, Line::Horizontal);
        }
        return $lines;
    }

    protected function verticalLines()
    {
        $lines = [];
        foreach($this->axis() as $coordinateX) {
            $squaresInLine = [];
            foreach ($this->axis() as $coordinateY) {
                $squaresInLine[] = $this->findSquareByCoordinates($coordinateX, $coordinateY);
            }
            $lines[] = new Line($squaresInLine, $this->size, Line::Vertical);
        }
        return $lines;
    }

    protected function topDownDiagonal()
    {
        $lines = [];
        $squaresInline = [];
        for ($i = 0; $i <= $this->bound(); $i++) {
            $coordinateX = $i;
            $coordinateY = $i;
            $squaresInLine[] = $this->findSquareByCoordinates($coordinateX, $coordinateY);
        }
        $lines[] = new Line($squaresInLine, $this->size, Line::Diagonal);
        return $lines;
    }

    protected function bottomUpDiagonal()
    {
        $lines = [];
        $squaresInline = [];
        for ($i = 0; $i <= $this->bound(); $i++) {
            $coordinateX = $i;
            $coordinateY = $this->bound() - $i;
            $squaresInLine[] = $this->findSquareByCoordinates($coordinateX, $coordinateY);
        }
        $lines[] = new Line($squaresInLine, $this->size, Line::Diagonal);
        return $lines;
    }

    protected function diagonalLines()
    {
        return array_merge($this->topDownDiagonal(), $this->bottomUpDiagonal());
    }

    protected function allLines()
    {
        return array_merge(
            $this->horizontalLines(),
            $this->verticalLines(),
            $this->diagonalLines()
        );
    }

}
