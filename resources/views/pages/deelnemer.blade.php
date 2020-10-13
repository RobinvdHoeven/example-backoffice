@extends ('layouts/app')

@section('content')


    <div class="row">
        <div class="col-12">
            <div class="update">
                <br>
                <h1>Bestelling bekijken: #{{ $order->id }}</h1>

                @if($order->status == 1)
                    <form method="post" action="{{ route('status.accept', $order->id)}}" style="display: inline-block;">
                        @csrf
                        <button class="postbtn" type="submit" onclick="return confirm('Weet je zeker dat je dit order wilt goedkeuren?')">Goedkeuren</button>
                    </form>
                    <form method="post" action="{{ route('status.deny', $order->id)}}" style="display: inline-block;">
                        @csrf
                        <button class="postbtn" type="submit" onclick="return confirm('Weet je zeker dat je dit order wilt weigeren?')">Weigeren</button>
                    </form>
                @elseif($order->status == 2)
                    <form method="post" action="{{ route('status.deny', $order->id)}}" style="display: inline-block;">
                        @csrf
                        <button class="postbtn" type="submit" onclick="return confirm('Weet je zeker dat je dit order wilt weigeren?')">Weigeren</button>
                    </form>
                @else
                    <form method="post" action="{{ route('status.accept', $order->id)}}" style="display: inline-block;">
                        @csrf
                        <button class="postbtn" type="submit" onclick="return confirm('Weet je zeker dat je dit order wilt goedkeuren?')">Goedkeuren</button>
                    </form>
                @endif
                <br><br><br>
                <form method="post" action="{{ route('backoffice.participant.update', $order->id) }}" enctype="multipart/form-data">
                    @csrf
                    <table class="table table-striped table-hover" style="width:100%">
                        <tbody>
                        <tr>
                            <td class="width">Status</td>
                            @if($order->status == 1)
                                <td>In afwachting</td>
                            @elseif($order->status == 2)
                                <td>Goedgekeurd</td>
                            @else
                                <td>Geweigerd</td>
                            @endif
                        </tr>
                        <tr>
                            <td class="width">Startdatum</td>
                            <td>{{ $order->date_start->format('d-m-Y') }}</td>
                        </tr>
                        <tr>
                            <td class="width">Einddatum</td>
                            <td>{{ $order->date_end->format('d-m-Y') }}</td>
                        </tr>
                        <tr>
                            <td class="width">Dagen</td>
                            <td>{{ $days }}</td>
                        </tr>
                        <tr>
                            <td class="width">Taal</td>
                            @if($order->locale == 'nl')
                                <td>Nederlands</td>
                            @elseif($order->locale == 'en')
                                <td>Engels</td>
                            @elseif($order->locale == 'de')
                                <td>Duits</td>
                            @elseif($order->locale == 'fr')
                                <td>Frans</td>
                            @endif
                        </tr>
                        <tr>
                            <td class="width">Land</td>
                            <td>
                                <select name="country" id="country" style="padding: 5px;">
                                    @foreach($countries as $country)
                                        @if($country->name_nl == $order->country)
                                            <option value="{{ $country->name_nl }}" selected>{{ $country->name_nl }}</option>
                                        @else
                                            <option value="{{ $country->name_nl }}">{{ $country->name_nl }}</option>
                                        @endif
                                    @endforeach
                                </select></td>
                        </tr>
                        <tr>
                            <td class="{{ $errors->has('firstname') ? 'error' : '' }}">Voornaam</td>
                            <td><input type="text" name="firstname" value="{{ old('firstname', $order->firstname) }}"></td>
                        </tr>

                        <tr>
                            <td class="{{ $errors->has('lastname') ? 'error' : '' }}">Achternaam</td>
                            <td><input type="text" name="lastname" value="{{ old('lastname', $order->lastname) }}"></td>
                        </tr>

                        <tr>
                            <td class="{{ $errors->has('phone') ? 'error' : '' }}">Telefoonnummer</td>
                            <td><input type="text" name="phone" value="{{ old('phone', $order->phone) }}"></td>
                        </tr>
                        <tr>
                            <td class="{{ $errors->has('email') ? 'error' : '' }}">E-mailadres</td>
                            <td><input type="email" name="email" value="{{ old('email', $order->email) }}"></td>
                        </tr>
                        <tr>
                            <td class="{{ $errors->has('street') ? 'error' : '' }}">Straat</td>
                            <td><input type="text" name="street" value="{{ old('street', $order->street) }}"></td>
                        </tr>
                        <tr>
                            <td class="{{ $errors->has('housenr') ? 'error' : '' }}">Huisnummer</td>
                            <td><input type="text" name="housenr" value="{{ old('housenr', $order->housenr) }}"></td>
                        </tr>
                        <tr>
                            <td class="{{ $errors->has('zipcode') ? 'error' : '' }}">Postcode</td>
                            <td><input type="text" name="zipcode" value="{{ old('zipcode', $order->zipcode) }}"></td>
                        </tr>
                        <tr>
                            <td class="{{ $errors->has('city') ? 'error' : '' }}">Woonplaats</td>
                            <td><input type="text" name="city" value="{{ old('city', $order->city) }}"></td>
                        </tr>
                        <tr>
                            <td class="{{ $errors->has('comment') ? 'error' : '' }}">Opmerking</td>
                            <td><textarea id="comment" name="comment" rows="4" cols="50">{{ old('comment', $order->comment) }}</textarea></td>
                        </tr>
                        </tbody>
                    </table>

                    <br>
                    <button type="submit" class="postbtn">Update</button>
                </form>

                <br><br><br>
                <div class="participants" style="width: 100%;">
                    <div style="overflow-x:scroll;">
                        <div class="participant">
                            <table class="table table-striped table-hover" style="width:100%">
                                <tbody>
                                <tr>
                                    <th>Product</th>
                                    <th>Aantal</th>
                                    <th>Prijs</th>
                                    <th>Totaal</th>
                                </tr>
                                @foreach($products as $product)
                                    <tr>
                                        <td>{{ $product->name_nl }}</td>
                                        <td>{{ $product->amount }}</td>
                                        <td>{{ $product->price }}</td>
                                        <td>{{ $product->price * $product->amount}}</td>
                                    </tr>
                                @endforeach
                                <?php $total = 0; ?>
                                @foreach($products as $product)
                                    <?php $total += ($product->amount * $product->price) ?>
                                @endforeach
                                <tr>
                                    <td>Dagen</td>
                                    <td>{{$days}}</td>
                                    <td>{{$total}}</td>
                                    <td><b>{{$total * $days}}</b></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <br><br>
            </div>
        </div>
    </div>

@endsection
