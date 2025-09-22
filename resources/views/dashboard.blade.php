@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="card">
        <div class="card-header">
            Dashboard
        </div>
        <div class="card-body">
            You're logged in as **{{ Auth::user()->name }}**!
            <br>
            Your role is: **{{ Auth::user()->getRoleNames()->first() }}**
        </div>
    </div>
@endsection
