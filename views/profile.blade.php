@extends('b3::base')

@section('b3content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        B3 profile
                    </div>
                    <div class="panel-body">
                        Your profile is currently active on the following servers:<br>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Server name</th>
                                <th>Playername</th>
                                <th>Rank</th>
                                <th>Connections</th>
                                <th>Last played</th>
                                <th>Banned</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($playsOnServers as $serverid=>$playsOnServer)
                                <tr>
                                    <td>{{$playsOnServer['name']}}</td>
                                    <td><a href="{{url('/b3/'.$serverid.'/player/'.$playsOnServer['player']->guid)}}">{{$playsOnServer['player']->name}}</a></td>
                                    <td>{{$playsOnServer['player']->group->name}}</td>
                                    <td>{{$playsOnServer['player']->connections}}</td>
                                    <td>{{\Carbon\Carbon::createFromTimestamp($playsOnServer['player']->time_edit)->toDateTimeString()}}</td>
                                    <td>{!! $playsOnServer['player']->isBanned ? '<font style="color:red;">Yes</font>' : 'No' !!}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
