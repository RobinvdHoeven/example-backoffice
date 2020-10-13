@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div>
                <div class="card">
                    <h1>Inloggen</h1>

                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="email">E-mail</label>

                                <div>
                                    <input id="email" type="email" @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <br>
                            <div class="form-group row">
                                <label for="password">Wachtwoord</label>

                                <div>
                                    <input id="password" type="password" @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <br>
                            <div class="form-group row">
                                <div>
                                    <div>
                                        <input style="width: 15px;" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">Onthoud gegevens</label>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div>
                                <div>
                                    <button type="submit" class="postbtn">
                                        {{ __('Login') }}
                                    </button>
                                    <br><br>
                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            Wachtwoord vergeten?
                                        </a>
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
