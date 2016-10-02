@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Dodaj nową aktywność</div>

                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ url()->action('FitnessController@store') }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                                <label for="type" class="col-md-4 control-label">Typ</label>

                                <div class="col-md-6">
                                    <select id="type" name="type" class="form-control" required>
                                        <option @if(old('type') === 'runs') selected="selected" @endif value="runs">Bieganie</option>
                                        <option @if(old('type') === 'walks') selected="selected" @endif value="walks">Chodzenie</option>
                                        <option @if(old('type') === 'bikes') selected="selected" @endif value="bikes">Jazda na rwoerze</option>
                                    </select>

                                    @if ($errors->has('type'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('type') }}</strong>
                                         </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('distance') ? ' has-error' : '' }}">
                                <label for="distance" class="col-md-4 control-label">Dystans (w km)</label>

                                <div class="col-md-6">
                                    <input id="distance" type="text" class="form-control" name="distance" value="{{ old('distance') }}" required>

                                    @if ($errors->has('distance'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('distance') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('calories') ? ' has-error' : '' }}">
                                <label for="calories" class="col-md-4 control-label">Kalorie</label>

                                <div class="col-md-6">
                                    <input id="calories" type="text" class="form-control" name="calories" value="{{ old('calories') }}" required>

                                    @if ($errors->has('calories'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('calories') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('duration') ? ' has-error' : '' }}">
                                <label for="duration" class="col-md-4 control-label">Czas trwania (w minutach)</label>

                                <div class="col-md-6">
                                    <input id="duration" type="text" class="form-control" name="duration" value="{{ old('duration') }}" required>

                                    @if ($errors->has('duration'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('duration') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Dodaj
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection