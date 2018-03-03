@extends('b3::base')

@section('b3content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        Current b3 servers
                        @can('manage', \Stormyy\B3\Models\B3Server::class)<a href="{{url('/b3/add')}}" class="btn btn-primary pull-right">Add server</a>@endcan
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th width="50%"></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($servers as $server)
                                <tr>
                                    <td>{{$server->name}} <a href="cod4://{{$server->host}}:{{$server->port}}" title="Join {{$server->name}} server"><i class="fa fa-sign-in"></i></a> </td>
                                    <td>@if($server->slots == null)
                                            <font color="red">Offline</font>
                                        @else
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="{{$server->online}}"
                                                     aria-valuemin="0" aria-valuemax="{{$server->slots}}" style="width:{{round($server->online/$server->slots*100)}}%">
                                                    {{$server->online}}/{{$server->slots}}
                                                </div>
                                            </div>
                                        @endif </td>
                                    <td style="text-align:right;"><a class="btn btn-primary" href="{{url('/b3/'.$server->id.'/players')}}">View</a> @can('manage', $server)<a class="btn btn-primary" href="{{url('/b3/'.$server->id.'/edit')}}">Edit</a>@endcan</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @if(Auth::user() != null && Auth::user()->isSuperAdmin)
                    <a class="btn btn-block btn-primary btn-lg" href="/b3/scan">Scan all servers</a>
                @endif
            </div>
        </div>
    </div>

@endsection
