@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-4">
            <div class="card-group d-block d-md-flex row">
                <div class="card col-md-12 p-4 mb-0">
                    <div class="card-body">
                        <h1>Login</h1>
                        <p class="text-medium-emphasis">Sign In to your account</p>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="input-group mb-3">
                                <span class="input-group-text">@</span>
                                <input class="form-control" type="email" name="email" placeholder="Email"
                                    value="{{ old('email') }}" required autofocus>
                            </div>
                            <div class="input-group mb-4">
                                <span class="input-group-text">#</span>
                                <input class="form-control" type="password" name="password" placeholder="Password" required>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <button class="btn btn-primary px-4" type="submit">Login</button>
                                </div>
                                <div class="col-6 text-end">
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" class="btn btn-link px-0"
                                            type="button">Forgot password?</a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
