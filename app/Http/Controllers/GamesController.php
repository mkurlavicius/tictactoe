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
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return View
     */


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
        $games  = $player->games()->get();


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
                $computer          = new Computer();
                $computersMove     = $computer->move($game);
                $rawComputerMove   = $computersMove->__toCoordinate()->__toLetterNumber();
                if($game->moves()->save($computersMove)) {
                    $request->session()->flash('success', "Computer started with a move ${rawComputerMove}. Your turn!!!");
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

    public function edit(Request $request, Game $game)
    {

    }
}
