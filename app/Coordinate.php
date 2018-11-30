<?php

namespace App;

class Coordinate
{
    const ZeroNine     = "/[0-9][0-9]/";
    const LetterOneTen = "/[a-zA-Z][0-9]/";

    const Letters = [
        'a' => 0,
        'b' => 1,
        'c' => 2,
        'd' => 3,
        'e' => 4,
        'f' => 5,
        'g' => 6,
        'h' => 7,
        'i' => 8,
        'j' => 9,
        'A' => 0,
        'B' => 1,
        'C' => 2,
        'D' => 3,
        'E' => 4,
        'F' => 5,
        'G' => 6,
        'H' => 7,
        'I' => 8,
        'J' => 9
    ];

    public $x;
    public $y;

    public static function letterOfNumber($number)
    {
        return array_search($number,self::Letters);
    }

    public function __toLetterNumber()
    {
        return strtoupper(self::letterOfNumber($this->x)) . ($this->y + 1);
    }

    public function __construct(string $from_string)
    {
        if (preg_match(self::LetterOneTen, $from_string)) {
            list($letter, $number) = preg_split('//u', $from_string,-1, PREG_SPLIT_NO_EMPTY);
            $this->x = self::Letters[$letter];
            $this->y = intval($number);
        } elseif (preg_match(self::ZeroNine, $from_string)) {
            list($x, $y) = preg_split('//u', $from_string,-1, PREG_SPLIT_NO_EMPTY);
            $this->x = $x;
            $this->y = $y;
        } else {
            throw new \Exception('The format of the coordinate is not supported');
        }
    }

}
