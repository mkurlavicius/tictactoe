<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request         as Request;
use App\Game                        as Game;
use App\Player                      as Player;
use App\Computer                    as Computer;
use Illuminate\Support\Facades\Input;

class GamesController extends Controller
{

    public function show($id)
    {
        return view('games.show', [
            'game'   => Game::findOrFail($id),
            'player' => $this->getPlayer()
        ]);
    }

    public function index(Request $request)
    {
        $player = $this->getPlayer();
        $games  = $player->games()->paginate(10);


        return view('games.index', [
            'games'  => $games,
            'player' => $player
        ]);
    }

    public function create()
    {
        return view('games.create', ['game' => new Game()]);
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
                    $request->session()->flash('success', "Computer started with a move ${computersMove}. Your turn!!!");
                }
            } else {
                $request->session()->flash('success', "Lets play. Your turn!!!");
            }

            $request->session()->reflash();
            return redirect()->action('GamesController@show', ['id' => $game->id]);
        } else {
            $request->session()->flash('warning', 'The was an error while trying to start a game');
            return view('games.show');
        }
    }

}
