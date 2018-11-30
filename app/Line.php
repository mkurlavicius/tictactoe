<?php

namespace App;

class Line
{
    const Horizontal = 1;
    const Vertical   = 2;
    const Diagonal   = 3;

    public $squares;
    public $size;
    public $type;
    public $emptySquares;
    public $computerSquares;
    public $humanSquares;

    public function __construct($squares, $size, $type)
    {
        $this->squares = $squares;
        $this->size    = $size;
        $this->type    = $type;
        $this->emptySquares    = 0;
        $this->computerSquares = 0;
        $this->humanSquares   = 0;
        foreach ($this->squares as $square) {
            switch($square->status)
            {
                case Square::Empty:
                    $this->emptySquares += 1;
                    break;
                case Square::Computer:
                    $this->computerSquares += 1;
                    break;
                case Square::Human:
                    $this->humanSquares +=1;
                    break;
                default:
                    throw new \Exception("Square type is not supported");
            }
        }
    }

    public function isFullComputer()
    {
        return $this->size == $this->computerSquares;
    }

    public function isFullHuman()
    {
        return $this->size == $this->humanSquares;
    }


}
