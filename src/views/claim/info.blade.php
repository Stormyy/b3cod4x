@extends('b3::base')

@section('b3content')
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        Claim process
                    </div>
                    <div class="panel-body">
                        To claim a player type the following command in to any server linked to b3:<br><br>

                        /claimplayer {{Auth::user()->claimCode}}<br>
                        <strong>Run the command above in chat and NOT the console!!!!!!!</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
