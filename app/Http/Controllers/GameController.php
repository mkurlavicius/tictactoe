<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request         as Request;
use App\Game                        as Game;

class GameController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return View
     */
    public function show($id)
    {
        return view('games.show', ['game' => Game::findOrFail($id)]);
    }

    public function create(Request $request)
    {
        $size = $request->input('size');
        $game = (new Game())->start($size, 'computer');
        return redirect()->action('GameController@show', ['id' => $game->id]);
    }
}
