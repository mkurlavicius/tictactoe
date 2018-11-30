<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>TicTacToe</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">


</head>

    <body>
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    {{ link_to_action('GamesController@index', 'All your games', [], ['class' => 'nav-link active']) }}
                </li>
            </ul>
        </div>
        <div class="card-body">
            <h5 class="card-title">
                @section('card-title')
                    Tic Tac Toe
                @show
            </h5>

            @foreach (['danger', 'warning', 'success', 'info'] as $key)

                @if (Session::has($key))
                    <p class="alert alert-{{ $key }}">{{ Session::get($key) }}</p>
                @endif

            @endforeach

            @yield('content')
        </div>
    </body>


</html>
