@extends('app')

@section('top-menu')
    <ul class="nav nav-tabs card-header-tabs">
        <li class="nav-item">
            {{ link_to_action('GamesController@index', 'All your games', [], ['class' => 'nav-link active']) }}
        </li>

        <li class="nav-item">
            <a href="https://github.com/mkurlavicius/tictactoe" class="nav-link">Code On Github</a>
        </li>
    </ul>
@endsection


@section('card-title')
    Tic Tac Toe - All Your Games
@endsection

@section('content')
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Id</th>
                <th>Size</th>
                <th>Started At</th>
                <th>
                    {{ link_to_action('GamesController@create', 'New Game',[], ['class' => 'btn btn-primary btn-sm', 'tabindex' => 1, 'role' => 'button', 'aria-disabled' => "true"]) }}
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($games as $game)
                <tr>
                    <td>{{ $game->id }}</td>
                    <td>{{ $game->size . 'x'. $game->size }}</td>
                    <td>{{ $game->created_at->format('Y-m-d H:i:s') }}</td>
                    <td>
                        @if($game->is_finished)
                            {{ link_to_action('GamesController@show', 'Review', ['id' => $game->id]) }}
                        @else
                            {{ link_to_action('GamesController@show', 'Continue to play', ['id' => $game->id]) }}
                        @endif

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $games->links() }}
    
@endsection
