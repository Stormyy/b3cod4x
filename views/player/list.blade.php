@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{asset('vendor/stormyy/b3cod4x/css/b3app.css')}}" />
    <div id="b3app" v-pre="">
        <vue-toastr ref="toastr"></vue-toastr>
        <div class="container" style="padding-top:50px;">
            <div class="row">
                <div class="col-sm-12">
                    <h1 style="margin-top:0;margin-bottom:30px;">{{$server->name}}</h1>
                    <div class="panel panel-primary">
                        <div class="panel-heading">Current players</div>
                        <div class="panel-body">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Playername</th>
                                    <th>Guid</th>
                                    <th>Ip</th>
                                    <th>Screenshots</th>
                                    <th>Latest screenshot</th>
                                    @if(Auth::check())
                                        <th></th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody is="b3players" serverid="{{$server->id}}"></tbody>
                            </table>
                        </div>
                    </div>

                    <div class="panel panel-primary">
                        <div class="panel-heading">Find players</div>
                        <div class="panel-body">
                            <b3playersearch serverid="{{$server->id}}"></b3playersearch>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
