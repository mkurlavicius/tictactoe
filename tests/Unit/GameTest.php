<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Player as Player;
use App\Game   as Game;
use App\Square as Square;
use App\Move   as Move;

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

    protected function getMove($player, $string)
    {
        $move = new Move();
        $move->as_string = $string;
        $move->player = $player;
        return $move;
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

        $this->game->moves()->save($this->getMove(Player::Human,'00'));
        $this->game->moves()->save($this->getMove(Player::Computer,'02'));
        $this->game->moves()->save($this->getMove(Player::Human, '11'));
        $this->game->moves()->save($this->getMove(Player::Computer, '21'));
        $this->game->moves()->save($this->getMove(Player::Human, '22'));
        $this->game->moves()->save($this->getMove(Player::Computer, '31'));
        $this->game->moves()->save($this->getMove(Player::Human, '33'));
        $this->game->moves()->save($this->getMove(Player::Computer, '41'));
        $this->game->moves()->save($this->getMove(Player::Human, '44'));

        $this->assertTrue($this->game->humanHasWon());
    }


}
