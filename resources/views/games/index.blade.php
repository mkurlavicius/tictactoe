@extends('app')

@section('sidebar')
    Index page

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
                <th>
                    {{ link_to_action('GamesController@create', 'New Game',[], ['class' => 'btn btn-primary btn-sm', 'tabindex' => 1, 'role' => 'button', 'aria-disabled' => "true"]) }}
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($games as $game)
                <tr>
                    <td>{{ $game->id }}</td>
                    <td>{{ $game->size }}</td>
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
    
@endsection
