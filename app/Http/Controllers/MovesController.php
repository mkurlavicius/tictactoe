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
            if($game->humanHasWon()) {
                $game->finish();
                $request->session()->flash('success', "Your move is ${move}. Congratulations, you have won!!!");
                $request->session()->reflash();
            } else {
                if($game->hasEndedNoWinner()) {
                    $request->session()->flash('warning', "Player moves ${move}. No moves left. Game is finished.");
                } else {
                    $computersMove = (new Computer())->move($game);
                    if($game->moves()->save($computersMove)) {
                        if($game->computerHasWon()) {
                            $game->finish();
                            $request->session()->flash('warning', "Player moves ${move}. Computer responds ${computersMove} and wins. Try another game");
                        } else {
                            if($game->hasEndedNoWinner()) {
                                $request->session()->flash('success', "Player moves ${move}. Computer responds ${computersMove}. No moves left. Game is finished.");
                            } else {
                                $request->session()->flash('success', "Player moves ${move}. Computer responds ${computersMove}. Your turn.");
                            }

                        }
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
