@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{asset('vendor/stormyy/b3cod4x/css/b3app.css')}}"/>
    <div id="b3app">
        <vue-toastr ref="toastr"></vue-toastr>
        <div class="container-fluid" style="margin-top:100px;">
            <div class="row">
                <div class="col-sm-12">
                    <h1 style="margin-top:0;margin-bottom:30px;">Scan servers</h1>
                    <div class="panel panel-primary">
                        <div class="panel-heading">Scanning players</div>
                        <div class="panel-body">
                            Total scanning: {{$totalScanned}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
