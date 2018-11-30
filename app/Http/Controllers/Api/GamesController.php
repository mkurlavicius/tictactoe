<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request         as Request;
use App\Game                        as Game;
use App\Player                      as Player;
use App\Computer                    as Computer;
use App\Square                      as Square;
use Illuminate\Support\Facades\Input;

class GamesController extends Controller
{

    public function show($id)
    {
        $game = Game::with('squares', 'moves')->findOrFail($id);
        return response()->json([
            'message'   => "Continuing to play...",
            'game'      => $game,
        ], 200);
    }

    public function index(Request $request)
    {
        $games = Game::with('squares')->paginate(10);
        return response()->json(['games' => $games]);
    }


    public function store(Request $request)
    {
        $player = $this->getPlayer();
        $game = new Game();
        $game->size = Input::get('size');
        $game->whoGoes(Input::get('started_by'));

        if($player->games()->save($game)) {
            if($game->started_by == Player::Computer) {
                $computersMove = (new Computer())->move($game);
                if($game->moves()->save($computersMove)) {
                    return response()->json([
                        'message'   => "Computer started with a move ${computersMove}. Your turn!!!",
                        'game'      => $game,
                        'last_move' => $computersMove
                    ], 201);
                }
            } else {
                return response()->json([
                    'message'   => "Lets play. Your turn!!!",
                    'game'      => $game,
                    'last_move' => null,
                ], 201);
            }

            $request->session()->reflash();
            return redirect()->action('GamesController@show', ['id' => $game->id]);
        } else {
            $request->session()->flash('warning', 'The was an error while trying to start a game');
            return view('games.show');
        }
    }

}
