@extends('layouts.app')

@section('content')
    <div class="container" style="padding-top:50px;">
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
                                <td>{{\Stormyy\B3\Helper\PermissionHelper::ip($player->ip)}}</td>
                            </tr>
                            <tr>
                                <td class="heading">First seen</td>
                                <td>{{\Carbon\Carbon::createFromTimestampUTC($player->time_add)->toDayDateTimeString()}}</td>
                                <td class="heading">Last seen</td>
                                <td>{{\Carbon\Carbon::createFromTimestampUTC($player->time_edit)->toDayDateTimeString()}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading">Screenshots</div>
                    <div class="panel-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Playername</th>
                                <th>Taken</th>
                                <th>Server</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($player->screenshots()->orderBy('created_at', 'desc')->get() as $screenshot)
                                <tr>
                                    <td>{{$screenshot->name}}</td>
                                    <td>{{$screenshot->created_at->toDayDateTimeString()}}</td>
                                    <td>{{$screenshot->server->server->name}}</td>
                                    <td><a class="btn btn-primary" href="{{\Storage::disk('screenshots')->url($screenshot->filename)}}" data-fancybox="gallery">View</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel panel-primary">
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
                            @foreach($player->aliases()->orderBy('time_edit', 'desc')->get() as $alias)
                                <tr>
                                    <td>{{$alias->alias}}</td>
                                    <td>{{$alias->num_used}}</td>
                                    <td>{{$alias->time_edit}}</td>
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
