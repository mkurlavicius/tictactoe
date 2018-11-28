<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    public function squares()
    {
        return $this->hasMany('App\Square');
    }


    public function game()
    {
        return $this->belongsTo('App\Game');
    }


    protected $fillable = [
        'size'
    ];

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
        if($this->exists()) {
            $this->iterateCoordinates(function($yCoordinate, $xCoordinate) {
                $this->squares()->save(new Square([
                    'x'      => $xCoordinate,
                    'y'      => $yCoordinate,
                    'status' => Square::Empty
                ]));
            });
        } else {
            throw new \Exception("Board is not created");
        }
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
            ->first()
            ->get();
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

}
