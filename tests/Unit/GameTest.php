<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Player as Player;
use App\Game   as Game;
use App\Square as Square;

class GameTest extends TestCase
{

    use RefreshDatabase;

    protected $player;
    protected $game;

    protected function setUp()
    {
        parent::setUp();
        $this->player = new Player();
        $this->player->save();
        $this->game = new Game([
            'size'       => 5,
            'started_by' => Player::Human
        ]);
        $this->player->games()->save($this->game);
    }


    public function testCreatedGameHasAllTheSquares()
    {
        $this->assertTrue($this->game->exists());
        $this->assertTrue($this->game->squares()->count() == 25);
        foreach (range(0, 4) as $coordinateX) {
            foreach (range(0, 4)as $coordinateY) {
                $square = $this->game->findSquareByCoordinates($coordinateX, $coordinateY);
                $this->assertTrue($square->status == Square::Empty);
            }
        }
    }

    public function testPlayingTheGame()
    {
//        foreach (range(1, 25) as $moveIndex) {
//            $this->
//        }
    }


}
