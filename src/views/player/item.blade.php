@extends('b3::base')

@section('b3content')
    <link rel="stylesheet" href="{{asset('vendor/stormyy/b3cod4x/css/b3app.css')}}"/>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">Player info</div>
                    <div class="panel-body">
                        <h3 style="margin-top:0">{{$player->name}}</h3>
                        <table class="table table-striped">
                            <tr>
                                <td class="heading">Name</td>
                                <td>{{$player->name}}</td>
                                <td class="heading">@id</td>
                                <td>{{$player->id}}</td>
                            </tr>
                            <tr>
                                <td class="heading">Group</td>
                                <td>{{$player->group->name}}</td>
                                <td class="heading">Connections</td>
                                <td>{{$player->connections}}</td>
                            </tr>
                            <tr>
                                <td class="heading">Guid</td>
                                <td>{{$player->guid}}</td>
                                <td class="heading">Ip</td>
                                <td>{!! \Stormyy\B3\Helper\PermissionHelper::ipToFlag($player->ip) !!} {{\Stormyy\B3\Helper\PermissionHelper::ip($player->ip)}}</td>
                            </tr>
                            <tr>
                                <td class="heading">First seen</td>
                                <td>{{\Carbon\Carbon::createFromTimestampUTC($player->time_add)->toDayDateTimeString()}}</td>
                                <td class="heading">Last seen</td>
                                <td>{{\Carbon\Carbon::createFromTimestampUTC($player->time_edit)->toDayDateTimeString()}}</td>
                            </tr>
                        </table>
                        <div class="row">
                            @can('setrank', [$server, $player])
                                <div class="col-sm-4">
                                    <b3setrank :player="{{$player}}"  serverid="{{$server->id}}"></b3setrank>
                                </div>
                            @endcan
                            <div class="col-sm-1 pull-right">
                                @can('ban', [$server, $player])
                                    <b3ban :player="{{$player}}" serverid="{{$server->id}}" :screenshots="{{$screenshots}}" :canBanWithoutProof="{{ Auth::user()->can('banWithoutProof', [$server, $player]) === true  ? 'true' : 'false'}}"></b3ban>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
                @if(count($otherServers) > 1)
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            Otherservers
                        </div>
                        <div class="panel-body">
                            This player is active on the following servers:<br>
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
                                @foreach($otherServers as $serverid=>$playsOnServer)
                                    @if($serverid != $server->id)
                                        <tr>
                                            <td>{{$playsOnServer['name']}}</td>
                                            <td>
                                                <a href="{{url('/b3/'.$serverid.'/player/'.$playsOnServer['player']->guid)}}">{{$playsOnServer['player']->name}}</a>
                                            </td>
                                            <td>{{$playsOnServer['player']->group->name}}</td>
                                            <td>{{$playsOnServer['player']->connections}}</td>
                                            <td>{{\Carbon\Carbon::createFromTimestamp($playsOnServer['player']->time_edit)->toDateTimeString()}}</td>
                                            <td>{!! $playsOnServer['player']->isBanned ? '<font style="color:red;">Yes</font>' : 'No' !!}</td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
                @if($sessions->count())
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            Past play sessions
                        </div>
                        <div class="panel-body">
                            This player is active on the following servers:<br>
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Start</th>
                                    <th>End</th>
                                    <th>Nickname</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($sessions as $session)
                                    <tr>
                                        <td>{{\Carbon\Carbon::createFromTimestampUTC($session->came)->toDayDateTimeString()}}</td>
                                        <td>{{\Carbon\Carbon::createFromTimestampUTC($session->gone)->toDayDateTimeString()}}</td>\
                                        <td>{{$session->nick}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
                <div class="panel panel-primary">
                    <div class="panel-heading">Screenshots</div>
                    <div class="panel-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Playername</th>
                                <th>Taken</th>
                                <th>Taken by</th>
                                <th>Server</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($screenshots as $screenshot)
                                <tr>
                                    <td>{{$screenshot->name}}</td>
                                    <td>{{$screenshot->created_at->toDayDateTimeString()}}</td>
                                    <td>@if($screenshot->takenBy){{$screenshot->takenBy->name}}@endif</td>
                                    <td>{{$screenshot->server->name}}</td>
                                    <td><a class="btn btn-primary"
                                           href="{{$screenshot->url}}"
                                           data-fancybox="gallery">View</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading">Penalties</div>
                    <div class="panel-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Type</th>
                                <th>Admin</th>
                                <th>Reason</th>
                                <th>Added</th>
                                <th>Expires</th>
                                <th>Active</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($player->penalties()->get() as $penalty)
                                @php $active = ($penalty->inactive == 0 && (\Carbon\Carbon::createFromTimestampUTC($penalty->time_expire)->greaterThan(\Carbon\Carbon::now()) || $penalty->time_expire == -1)); @endphp
                                <tr>
                                    <td>{{$penalty->type}}</td>
                                    <td>{!! $penalty->admin ? '<a href="'.url('/b3/'.$server->id.'/player/'.$penalty->admin->guid).'">'.$penalty->admin->name."</a>": $penalty->data !!}</td>
                                    <td>{{$penalty->reason}}</td>
                                    <td>{{\Carbon\Carbon::createFromTimestampUTC($penalty->time_add)->toDayDateTimeString()}}</td>
                                    <td>{{\Carbon\Carbon::createFromTimestampUTC($penalty->time_expire)->toDayDateTimeString()}}</td>
                                    <td>{!! $active ? '<i class="glyphicon glyphicon-ok"></i>' : '<i class="glyphicon glyphicon-remove"></i>'!!}</td>
                                    <td>@if(Auth::check() && Auth::user()->can('unban', [$server, $player]) && $active)
                                            <a href="/b3/{{$server->id}}/unban/{{$penalty->id}}"
                                               class="btn btn-primary">Remove</a> @endif</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel panel-primary" id="/aliases">
                    <div class="panel-heading">Aliases</div>
                    <div class="panel-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Alias</th>
                                <th>Used</th>
                                <th>Last used</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $aliases = $player->aliases()->orderBy('time_edit', 'desc')->paginate(10, ['*'], 'aliases_page'); @endphp
                            @foreach($aliases as $alias)
                                <tr>
                                    <td>{{$alias->alias}}</td>
                                    <td>{{$alias->num_used}}</td>
                                    <td>{{\Carbon\Carbon::createFromTimestampUTC($alias->time_edit)->toDayDateTimeString()}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $aliases->fragment('aliases')->links() }}
                    </div>
                </div>
                <div class="panel panel-primary" id="/ipaliases">
                    <div class="panel-heading">Ip aliases</div>
                    <div class="panel-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Alias</th>
                                <th>Used</th>
                                <th>Last used</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $ipaliases = $player->ipaliases()->orderBy('time_edit', 'desc')->paginate(10, ['*'], 'ipaliases_page'); @endphp
                            @foreach($ipaliases as $ipalias)
                                <tr>
                                    <td>{!! \Stormyy\B3\Helper\PermissionHelper::ipToFlag($ipalias->ip) !!} {{\Stormyy\B3\Helper\PermissionHelper::ip($ipalias->ip)}}</td>
                                    <td>{{$ipalias->num_used}}</td>
                                    <td>{{\Carbon\Carbon::createFromTimestampUTC($ipalias->time_edit)->toDayDateTimeString()}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $ipaliases->fragment('ipaliases')->links() }}
                    </div>
                </div>
                <div class="panel panel-primary" id="/adminactions">
                    <div class="panel-heading">Actions did by admins</div>
                    <div class="panel-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Type</th>
                                <th>Player</th>
                                <th>Reason</th>
                                <th>Added</th>
                                <th>Expires</th>
                                <th>Active</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $penalties = $player->adminpenalties()->paginate(10, ['*'], 'adminpenalties_page'); @endphp
                            @foreach($penalties as $penalty)
                                @php $active = ($penalty->inactive == 0 && (\Carbon\Carbon::createFromTimestampUTC($penalty->time_expire)->greaterThan(\Carbon\Carbon::now()) || $penalty->time_expire == -1)); @endphp
                                <tr>
                                    <td>{{$penalty->type}}</td>
                                    <td>
                                        <a href="{{url('/b3/'.$server->id.'/player/'.$penalty->player->guid)}}">{{$penalty->player ? $penalty->player->name : $penalty->data}}</a>
                                    </td>
                                    <td>{{$penalty->reason}}</td>
                                    <td>{{\Carbon\Carbon::createFromTimestampUTC($penalty->time_add)->toDayDateTimeString()}}</td>
                                    <td>{{\Carbon\Carbon::createFromTimestampUTC($penalty->time_expire)->toDayDateTimeString()}}</td>
                                    <td>{!! $active ? '<i class="glyphicon glyphicon-ok"></i>' : '<i class="glyphicon glyphicon-remove"></i>'!!}</td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                        {{ $penalties->fragment('adminactions')->links() }}
                    </div>
                </div>
                <div class="panel panel-primary" id="/chatlogs">
                    <div class="panel-heading">Chatlogs</div>
                    <div class="panel-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Type</th>
                                <th>Player</th>
                                <th>Reason</th>
                                <th>Type</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $chatlogs = $player->chatlogs()->orderBy('msg_time', 'desc')->paginate(20, ['*'], 'chatlog_page');
                            @endphp
                            @foreach($chatlogs as $chatlog)
                                @php $date = \Carbon\Carbon::createFromTimestampUTC($chatlog->msg_time); @endphp
                                <tr>
                                    <td>{{$date->format('d M ').$date->toTimeString()}}</td>
                                    <td>{{$chatlog->client_name}}</td>
                                    <td>{{$chatlog->msg}}</td>
                                    <td>{{$chatlog->msg_type}}</td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                        {{ $chatlogs->fragment('chatlogs')->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
