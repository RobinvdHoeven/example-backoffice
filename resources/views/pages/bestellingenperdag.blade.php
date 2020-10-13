@extends ('layouts/app')
<?php use Carbon\Carbon; ?>
@section('content')
    <div class="participants fullwidth">
        <h1 style="margin-top:0;">Bestellingen: </h1>
        <h2>Klik op een datum om de bestellingen te zien met de gekozen startdatum.</h2>
        <div class="search">

            <form method="post" class="searchbar" action="{{ route('orders.searchbyday') }}">
                @csrf
                <label for="date_start">Zoek op startdatum:</label><br>
                <input type="date" id="date_start" name="date_start" min="2020-01-01" style="width: 200px;">
                <input type="submit" value="Zoek" style="width: 100px; padding: 8px;">
            </form>
            <br><br>
        </div>
        <br>
        <div style="overflow-x:scroll;">
            <div class="participant"><br><br>
                <table>
                    @if(\Request::is('zoekenorders'))
                        <tr>
                            <th>ID</th>
                            <th>Startdatum</th>
                            <th>Einddatum</th>
                            <th>Naam</th>
                            <th>Telefoonnummer</th>
                            <th>E-mailadres</th>
                            <th>Bedrag</th>
                        </tr>
                    @else
                        <tr>
                            <th>ID</th>
                            <th>Startdatum</th>
                            <th>Einddatum</th>
                            <th>Naam</th>
                            <th>Telefoonnummer</th>
                            <th>E-mailadres</th>
                            <th>Bedrag</th>
                        </tr>
                    @endif
                    @foreach($orders as $order)
                        <?php
                        $start = Carbon::parse($order->date_start);
                        $end = Carbon::parse($order->date_end);
                        $days = $start->diffInDays($end);
                        ?>
                        <tr style="cursor: pointer" onclick="location.href='{{ route('backoffice.participant.show', ['id' => $order->id]) }}';">
                            <td>{{$order->id}}</td>
                            <td>{{$order->date_start->format('d-m-Y') }}</td>
                            <td>{{$order->date_end->format('d-m-Y') }}</td>
                            <td>{{$order->firstname}} {{$order->lastname}}</td>
                            <td>{{$order->phone}}</td>
                            <td>{{$order->email}}</td>
                            <td>{{$order->total * $days }}</td>
                        </tr>
                    @endforeach
                </table>
                <br>
                <br>
                <br>
            </div>
        </div>
    </div>
@endsection
