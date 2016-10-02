@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>Status połączenia z Facebook</strong>
                </div>

                <div class="panel-body">
                    @if(Auth::user()->isFacebookConnected())
                        Twoje konto jest połączone z Facebookiem.
                        <div class="btn-group pull-right">
                            <a id="fb_sync" onclick="fbSync()" href="{{ url('/get/all') }}" class="btn btn-info btn-xs"><i class="fa fa-refresh"></i> Pobierz aktywności z Facebook'a</a>
                        </div>
                    @else
                        Połącz z Facebook, aby rozpocząć korzystanie z aplikacji:
                        <a href="{{ url('auth/facebook') }}" class="btn btn-default"><i class="fa fa-facebook-official"></i> Połącz z FB</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @if(Auth::user()->isFacebookConnected())
        @if(Auth::user()->fitnessActivities->count() > 0)

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <strong>Najbardziej efektywne treningi</strong>
                            <div class="btn-group pull-right">
                                <a href="{{ url('/create') }}" class="btn btn-info btn-xs"><i class="fa fa-plus"></i> Dodaj aktywność</a>
                            </div>
                        </div>

                        <div class="panel-body">
                            Chodzenie: Trening z <b>@if($best_efficiency['walk'] != null) {{ $best_efficiency['walk']->start_time }} @else --- @endif</b><br>
                            Bieganie: Trening z <b>@if($best_efficiency['run'] != null) {{ $best_efficiency['run']->start_time }} @else --- @endif</b><br>
                            Jazda na rowerze: Trening z <b>@if($best_efficiency['bike'] != null) {{ $best_efficiency['bike']->start_time }} @else --- @endif</b>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <strong>Wszystkie aktywności</strong> <small>(od najnowszej)</small>
                            <div class="btn-group pull-right">
                                <a href="{{ url('/create') }}" class="btn btn-info btn-xs"><i class="fa fa-plus"></i> Dodaj aktywność</a>
                            </div>
                        </div>

                        <div class="panel-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Typ aktywności</th>
                                        <th>Kiedy</th>
                                        <th>Czas</th>
                                        <th>Kilometry</th>
                                        <th>Spalone kalorie</th>
                                        <th>kcal / min</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($fitness_activities as $fitness_activity)
                                        <tr>
                                            <td>
                                                @if($fitness_activity->type === 'runs')
                                                    Bieganie
                                                @elseif($fitness_activity->type === 'walks')
                                                    Chodzenie
                                                @elseif($fitness_activity->type === 'bikes')
                                                    Jazda na rowerze
                                                @endif
                                            </td>
                                            <td>{{ $fitness_activity->start_time->format('Y-m-d H:i:s') }}</td>
                                            <td>{{ round($fitness_activity->duration / 60, 2) }} (min)</td>
                                            <td>{{ round($fitness_activity->distance, 2) }}</td>
                                            <td>{{ $fitness_activity->calories }}</td>
                                            <td>{{ round($fitness_activity->calories / ($fitness_activity->duration / 60), 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <strong>Brak aktywności na koncie</strong>
                            <div class="btn-group pull-right">
                                <a href="{{ url('/create') }}" class="btn btn-info btn-xs"><i class="fa fa-plus"></i> Dodaj aktywność</a>
                            </div>
                        </div>

                        <div class="panel-body">
                            Aktualnie na koncie nie ma żadnych aktywności. Połącz z Facebookiem, aby pobrać aktywności lub dodaj aktywność do swojego konta.
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>
@endsection

@if(Auth::user()->isFacebookConnected())
    @section('assets-bottom')
        <script>
            function fbSync() {
                $('#fb_sync').attr('disabled', true);
                $('#fb_sync').text('Proszę czekać');
            }
        </script>
    @endsection
@endif
