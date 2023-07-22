@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('user_token'))
                        <div class="alert alert-success" role="alert">
                            {{ __('You are logged in! Welcome ') }} {{session('user_name')}}
                        </div>
                    @endif
                    <div id="dogs" data-user='{"id":"{{session('user_id')}}","token":"{{session('user_token')}}"}'></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/app.js') }}"></script>
@endsection
