@extends('layouts.app')

@section('content')
    <div class="container" style="padding-top:50px;">
        <div class="row">
            <div class="col-sm-8">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        B3 server
                    </div>
                    <div class="panel-body">
                        <form method="post" action="{{url('/b3/'.$server->id.'/save')}}">
                            {{csrf_field()}}
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" name="name" id="name" value="{{$server->name}}">
                            </div>
                            <div class="form-group">
                                <label for="identifier">Identifier</label>
                                <input type="text" class="form-control" name="identifier" id="identifier"
                                       value="{{$server->identifier}}">
                            </div>
                            <hr>
                            <div class="form-group">
                                <label for="db[host]">DB Host</label>
                                <input type="text" class="form-control" name="db[host]" id="db[host]"
                                       value="{{$server->dbSettings['host']}}">
                            </div>
                            <div class="form-group">
                                <label for="name">DB Port</label>
                                <input type="text" class="form-control" name="db[port]" id="db[port]"
                                       value="{{$server->dbSettings['port']}}">
                            </div>
                            <div class="form-group">
                                <label for="name">DB Database</label>
                                <input type="text" class="form-control" name="db[database]" id="db[database]"
                                       value="{{$server->dbSettings['database']}}">
                            </div>
                            <div class="form-group">
                                <label for="name">DB Username</label>
                                <input type="text" class="form-control" name="db[username]" id="db[username]"
                                       value="{{$server->dbSettings['username']}}">
                            </div>
                            <div class="form-group">
                                <label for="name">DB Password</label>
                                <input type="password" class="form-control" name="db[password]" id="db[password]" value="{{$server->dbSettings['password']}}">
                            </div>

                            <button class="btn btn-primary pull-right" type="submit">Create</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">Server config</div>
                    <div class="panel-body">
                        Add the following to your server.cfg to enable screenshots<br><br>
                        <code>
                            set nehoscreenshot_identkey {{$server->identifier}}<br><br>
                            set nehoscreenshot_url "http://beta.luvclan.nl/b3/screenshot"<br><Br>
                            loadplugin nehoscreenshotuploader<br><br>
                            loadplugin claimplayer
                        </code>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
