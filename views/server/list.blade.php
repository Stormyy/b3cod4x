@extends('layouts.app')

@section('content')
    <div class="container" style="padding-top:50px;">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        Current b3 servers
                        <a href="{{url('/b3/add')}}" class="btn btn-primary pull-right">Add server</a>
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
                                    <td><a class="btn btn-primary" href="{{url('/b3/'.$server->id.'/players')}}">View</a> <a class="btn btn-primary" href="{{url('/b3/'.$server->id.'/edit')}}">Edit</a></td>
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
