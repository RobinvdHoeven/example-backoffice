@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card">
                <h1 class="createuser">Gebruiker aanmaken</h1>
            </div>
            <h3>LET OP! Elke gebruiker kan producten, sliders en vertalingen aanpassen.</h3>
            <div class="card-body">
                <form method="POST" action="{{ route('gebruiker.aanmaken') }}">
                    @csrf

                    <div class="createuser" style="margin-bottom: 5px;">
                        <label for="name">Naam</label>

                        <div>
                            <input id="name" type="text" class="@error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>

                    <div class="createuser" style="margin-bottom: 5px;">
                        <label for="email">E-mail</label>

                        <div>
                            <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>

                    <div class="createuser" style="margin-bottom: 5px;">
                        <label for="password">Wachtwoord</label>

                        <div>
                            <input id="password" type="password" class=" @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>

                    <div class="createuser">
                        <label for="password-confirm">Herhaal wachtwoord</label>

                        <div>
                            <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password">
                        </div>
                    </div>
                    <br>
                    <div class="createuser">
                        <div>
                            <button type="submit" class="postbtn">
                                Registreer
                            </button>
                        </div>
                    </div>
                </form>
                <script>
                    var msg = '{{Session::get('alert')}}';
                    var exist = '{{Session::has('alert')}}';
                    if (exist) {
                        alert(msg);
                    }
                </script>
                <div class="users">
                    <h1>Gebruikers:</h1>
                    <div class="user">
                        <table>
                            <tr>
                                <th>ID</th>
                                <th>Naam</th>
                                <th>E-mail</th>
                                <th></th>
                                <th></th>
                            </tr>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{$user->id}}</td>
                                    <td>{{$user->name}}</td>
                                    <td>{{$user->email}}</td>
                                    <td>
                                        <form method="get" action="{{ route('gebruiker.edit', $user->id)}}">
                                            <button style="border: 0; background: none;" type="submit"><i class="fa fa-pencil"></i></button>
                                        </form>
                                    </td>
                                    <td>
                                        <form method="post" action="{{ route('gebruiker.verwijderen', $user->id)}}">
                                            @csrf
                                            <button style="border: 0; background: none;" type="submit" onclick="return confirm('Weet je zeker dat je deze gebruiker wilt verwijderen?')"><i class="fa fa-remove"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                        <br>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
