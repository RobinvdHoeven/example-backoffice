@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card">
                <h1 class="createuser">Gebruiker wijzigen</h1>
            </div>
            <form method="post" action="{{ route('gebruiker.update', $user->id) }}">
                @csrf
                <label for="name">Naam:<br>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"><br><br>
                </label>
                <label for="email">E-mailadres:<br>
                    <input type="text" name="email" value="{{ old('email', $user->email) }}">
                </label><br><br>
                <button type="submit" class="postbtn">Update gebruiker</button>
            </form>
            <br>
            <br>
        </div>
    </div>
@endsection
