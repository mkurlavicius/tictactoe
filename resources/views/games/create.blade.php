@extends('app')

@section('card-title')
    Tic Tac Toe - Game Options
@endsection

@section('content')
    {{ Form::model($game, ['route' => array('games.store')]) }}

        <div class="form-group">
            {{ Form::label('size', 'Size of the board') }}
            {{ Form::select('size', ['3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8'],[], ['class' => 'form-control']) }}
        </div>

        <div class="form-group">
            {{ Form::label('started_by', 'Who starts the game?') }}
            {{ Form::select('started_by', ['human' => 'You', 'computer' => 'Computer'],[], ['class' => 'form-control']) }}
        </div>



        
        {{ Form::submit('Start to play', ['class' => 'btn btn-primary']) }}

    {{ Form::close() }}
@endsection
