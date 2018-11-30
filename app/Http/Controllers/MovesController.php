<?php

namespace App\Http\Controllers;

use App\Computer;
use App\Http\Controllers\Controller as Controller;
use App\Player;
use Illuminate\Http\Request         as Request;
use App\Move                        as Move;
use App\Game                        as Game;
use Illuminate\Support\Facades\Input;

class MovesController extends Controller
{

    public function store(Request $request)
    {
        $game = Game::findOrfail(Input::get('game_id'));
        $move = new Move();
        $move->as_string = Input::get('as_string');
        $move->player    = Player::Human;

        if($game->moves()->save($move)) {
            $playersMove = $move->__toCoordinate()->__toLetterNumber();
            if($game->humanHasWon()) {
                $game->finish();
                $request->session()->flash('success', "Your move is ${playersMove}. Congratulations, you have won!!!");
                $request->session()->reflash();
            } else {
                $computer          = new Computer();
                $computersMove     = $computer->move($game);
                if($game->moves()->save($computersMove)) {
                    $rawComputerMove   = $computersMove->__toCoordinate()->__toLetterNumber();
                    if($game->computerHasWon()) {
                        $game->finish();
                        $request->session()->flash('warning', "Player moves ${playersMove}. Computer responds ${rawComputerMove} and wins. Try another game");
                    } else {
                        $request->session()->flash('success', "Player moves ${playersMove}. Computer responds ${rawComputerMove}.");
                    }
                }
            }
            $request->session()->reflash();
            return redirect()->action('GamesController@show', ['id' => $game->id]);
        } else {
            $request->session()->flash('warning', 'The was an error while trying to start a game');
            $request->session()->reflash();
            return redirect()->action('GamesController@show', ['id' => $game->id]);
        }
    }

}
