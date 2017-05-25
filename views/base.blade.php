@extends('layouts.app')

@section('content')
    <div id="b3app">
        <div style="margin-top:100px">
            @if(Auth::check() && Auth::user()->guid == null)
                <div class="container">
                    <div class="panel panel-danger">
                        <div class="panel-heading">Claimed player</div>
                        <div class="panel-body">
                            To make use of the admin functions you first need to claim a player on any of the servers
                            <a class="btn btn-danger" href="{{url('/b3/claim')}}">Start claim process</a>
                        </div>
                    </div>
                </div>
            @endif
            @yield('b3content')
            @if(isset($myplayer))
                <div class="container">
                    <h4 style="display:inline-block">{{$myplayer->name}}</h4>
                    <h4 class="pull-right">{{$myplayer->group->name}}</h4>
                </div>
            @endif
        </div>
    </div>
@endsection
