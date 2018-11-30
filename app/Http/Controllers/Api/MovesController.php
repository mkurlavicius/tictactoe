<?php

namespace App\Http\Controllers\Api;

use App\Computer;
use App\Http\Controllers\Controller as Controller;
use App\Player;
use Illuminate\Http\Request         as Request;
use App\Move                        as Move;
use App\Game                        as Game;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Response        as Response;

class MovesController extends Controller
{

    public function store(Request $request)
    {
        $game = Game::with('squares')->findOrFail(Input::get('game_id'));
        $move = new Move();
        $move->as_string = Input::get('as_string');
        $move->player    = Player::Human;

        if($game->moves()->save($move)) {
            if($game->humanHasWon()) {
                $game->finish();
                return response()->json([
                    'message'   => "Your move is ${move}. Congratulations, you have won!!! ",
                    'game'      => $game,
                    'last_move' => $move
                ], 201);
            } else {
                if($game->hasEndedNoWinner()) {
                    return response()->json([
                        'message'   => "Player moves ${move}. No moves left. Game is finished.",
                        'game'      => $game,
                        'last_move' => $move
                    ], 201);
                } else {
                    $computersMove = (new Computer())->move($game);
                    if($game->moves()->save($computersMove)) {
                        if($game->computerHasWon()) {
                            $game->finish();
                            return response()->json([
                                'message'   => "Player moves ${move}. Computer responds ${computersMove} and wins.",
                                'game'      => $game,
                                'last_move' => $computersMove
                            ], 201);
                        } else {
                            if($game->hasEndedNoWinner()) {
                                return response()->json([
                                    'message'   => "Player moves ${move}. Computer responds ${computersMove}. No moves left. Game is finished.",
                                    'game'      => $game,
                                    'last_move' => $computersMove
                                ]);
                            } else {
                                return response()->json([
                                    'message'   => "Player moves ${move}. Computer responds ${computersMove}. Your turn.",
                                    'game'      => $game,
                                    'last_move' => $computersMove
                                ]);
                            }

                        }
                    } else {
                        return Response::json([
                           'error' => 'There was an error while trying to save computers move',
                           'game'  => $game
                        ], 500);
                    }
                }
            }
        } else {
            return Response::json([
               'error' => 'The was an error while trying to save the players move',
               'game'  => $game
            ], 500);
        }
    }

    protected function playLinkWithText($text)
    {
        return link_to_action('GamesController@create', $text);
    }

}
