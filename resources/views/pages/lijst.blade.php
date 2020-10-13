<?php

use Carbon\Carbon;
use Carbon\CarbonPeriod;

?>

@extends ('layouts/app')

@section('content')
    <div class="participants fullwidth">
        <h1 style="margin-top:0;">Orders: ({{$deliveryday->format('d-m-Y')}})</h1>
        <div class="search">
            <form method="post" class="searchbar" action="{{ route('backoffice.search') }}">
                {{ csrf_field() }}

                <select name="search" class="form-control" onChange='{ this.form.submit(); }'>
                    @foreach($days as $day)
                        <option
                            value="{{ Carbon::parse($day)->format('Y-m-d') }}" <?= (old('search', Carbon::now()->format('Y-m-d')) == Carbon::parse($day)->format('Y-m-d') ? 'selected="selected"' : ''); ?>>{{ ucfirst(Carbon::parse($day)->isoFormat('dddd D MMMM')) }}</option>
                    @endforeach

                </select>

            </form>
            <br>
            <div class="participant">
                <table style="width: 40%;">
                    <tr>
                        <th>Product</th>
                        <th>Aantal</th>
                    </tr>
                    <tr>
                        <td>BBQ Box</td>
                        <td>{{$total->totala}}</td>
                    </tr>
                    <tr>
                        <td>Picnic Geniet Box</td>
                        <td>{{$total->totalb}}</td>
                    </tr>
                    <tr>
                        <td>Borrel Box</td>
                        <td>{{$total->totalc}}</td>
                    </tr>
                    <tr>
                        <td>Sweet Geniet Box</td>
                        <td>{{$total->totald}}</td>
                    </tr>
                    <tr>
                        <td>Saté Geniet Box</td>
                        <td>{{$total->totale}}</td>
                    </tr>
                </table>
            </div>
            <br><br>
            @if(count($deliveryaddress) != 0)
                @if(\Request::is('zoeken'))
                    <form class="searchbar" action="{{ route('participants.exportsearch') }}">
                        <input class="form-control" value="{{ old('search') }}" name="search" type="hidden" placeholder="Zoeken" aria-label="Zoeken">
                        <button class="postbtn" type="submit">Download naar Excel</button>
                    </form>
                @else
                    <form class="searchbar" action="{{ route('participants.export') }}">
                        <input type="hidden" name="date" value="{{$deliveryday->toDateString()}}">
                        <button class="postbtn" type="submit">Download naar Excel</button>
                    </form>
                @endif
            @endif

        </div>
        <div style="overflow-x:scroll;">
            <div class="participant"><br><br>
                <table>
                    @if(\Request::is('zoeken'))
                        <tr>
                            <th>ID</th>
                            <th>Geplaatst op</th>
                            <th>Besteld door</th>
                            <th>Soort</th>
                            <th>Voornaam</th>
                            <th>Achternaam</th>
                            <th>Telefoonnummer</th>
                            <th>Aflever postcode</th>
                            <th>Aflever plaats</th>
                            <th>Aflever straat</th>
                            <th>Aflever huisnr</th>
                            <th>Foodbox A</th>
                            <th>Foodbox B</th>
                            <th>Foodbox C</th>
                            <th>Foodbox D</th>
                            <th>Foodbox E</th>
                            <th>Tekst</th>
                        </tr>
                    @else
                        <tr>
                            <th><a href="?orderby=id&type=<?= ($type == 'asc' ? 'desc' : 'asc') ?>">ID</a></th>
                            <th><a href="?orderby=finished_at&type=<?= ($type == 'asc' ? 'desc' : 'asc') ?>">Geplaatst op</a></th>
                            <th><a href="?orderby=firstname&type=<?= ($type == 'asc' ? 'desc' : 'asc') ?>">Besteld door</a></th>
                            <th>Soort</th>
                            <th>Voornaam</th>
                            <th>Achternaam</th>
                            <th>Telefoonnummer</th>
                            <th>Aflever postcode</th>
                            <th>Aflever plaats</th>
                            <th>Aflever straat</th>
                            <th>Aflever huisnr</th>
                            <th>Foodbox A</th>
                            <th>Foodbox B</th>
                            <th>Foodbox C</th>
                            <th>Foodbox D</th>
                            <th>Foodbox E</th>
                            <th>Tekst</th>
                        </tr>
                    @endif
                    @foreach($deliveryaddress as $daddress)
                        <tr style="cursor:pointer;" onclick="location.href='{{ route('backoffice.participant.show', ['guid' => $daddress->order->guid]) }}';">
                            <td>{{$daddress->order->id}}</td>
                            <td>{{$daddress->order->finished_at->format('d-m-Y H:i:s') }}</td>
                            <td>{{$daddress->order->firstname}} {{$daddress->order->lastname}}</td>
                            @if($daddress->order->is_delivery == 1)
                                <td>Bezorgen</td>
                            @else
                                <td>Afhalen</td>
                            @endif
                            <td>{{$daddress->firstname}}</td>
                            <td>{{$daddress->lastname}}</td>
                            <td>{{$daddress->order->phone}}</td>
                            @if($daddress->order->is_delivery == 1)
                                <td>{{$daddress->postcode}}</td>
                                <td>{{$daddress->city}}</td>
                                <td>{{$daddress->street}}</td>
                                <td>{{$daddress->housenr}}</td>
                            @else
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            @endif
                            <td>{{$daddress->option_a}}</td>
                            <td>{{$daddress->option_b}}</td>
                            <td>{{$daddress->option_c}}</td>
                            <td>{{$daddress->option_d}}</td>
                            <td>{{$daddress->option_e}}</td>
                            <td>{{$daddress->text}}</td>
                        </tr>
                    @endforeach
                </table>
                <br>
                <br>
            </div>
        </div>
    </div>
@endsection
