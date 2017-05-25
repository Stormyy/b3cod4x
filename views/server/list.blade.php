@extends('b3::base')

@section('b3content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        Current b3 servers
                        @can('manage', \Stormyy\B3\Models\B3Server::class)l<a href="{{url('/b3/add')}}" class="btn btn-primary pull-right">Add server</a>@endcan
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($servers as $server)
                                <tr>
                                    <td>{{$server->name}}</td>
                                    <td><a class="btn btn-primary" href="{{url('/b3/'.$server->id.'/players')}}">View</a> @can('manage', $server)<a class="btn btn-primary" href="{{url('/b3/'.$server->id.'/edit')}}">Edit</a>@endcan</td>
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
