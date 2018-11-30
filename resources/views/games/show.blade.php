@extends('app')


@section('content')

    <div class="card">
        <div class="card-body" style="width: 10rem">
            <table class="table">
                    <div class="row">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th></th>
                                    @foreach($game->axis() as $coordinate)
                                        <th>{{ strtoupper(App\Coordinate::letterOfNumber($coordinate)) }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($game->axis() as $coordinateY)
                                <tr>
                                    <th>{{ $coordinateY + 1 }}</th>
                                    @foreach($game->axis() as $coordinateX)

                                        @php($square = $game->findSquareByCoordinates($coordinateY, $coordinateX))

                                        <td>
                                            @if($square->status == \App\Square::Empty)

                                                @php($move = new \App\Move())

                                                {{ Form::model($move, ['route' => array('moves.store')]) }}
                                                {{ Form::text('as_string', $square->__toMove(), ['class' => 'd-none']) }}
                                                {{ Form::hidden('game_id', $game->id) }}
                                                {{ Form::number('player',  App\Square::Human, ['class' => 'd-none']) }}

                                                @if($game->is_finished)
                                                    {{ Form::submit(' ', ['class' => 'btn btn-sq btn-primary', 'disabled' => true]) }}
                                                @else
                                                    {{ Form::submit(' ', ['class' => 'btn btn-sq btn-primary']) }}
                                                @endif

                                                {{ Form::close() }}


                                            @elseif($square->status == \App\Square::Human)

                                                @if($square->is_winning)
                                                    <button type="button" class="btn btn-sq btn-success" disabled>{{ $square }}</button>
                                                @else
                                                    <button type="button" class="btn btn-sq btn-primary" disabled>{{ $square }}</button>
                                                @endif


                                            @elseif($square->status == \App\Square::Computer)

                                                @if($square->is_winning)
                                                    <button type="button" class="btn btn-sq btn-danger" disabled>{{ $square }}</button>
                                                @else
                                                    <button type="button" class="btn btn-sq btn-primary" disabled>{{ $square }}</button>
                                                @endif
                                                
                                            @endif

                                        </td>

                                    @endforeach

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
            </table>
        </div>
    </div>

@endsection
