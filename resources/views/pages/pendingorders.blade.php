@extends ('layouts/app')
<?php use Carbon\Carbon; ?>
@section('content')
    <div class="participants fullwidth">
        <h1 style="margin-top:0;">In afwachting: ({{$orders->total()}}) </h1>

        <button class="postbtn" style="color:#f7ea4e;" onclick="location.href='{{ route('pending')}}'">In afwachting</button>
        <button class="postbtn" onclick="location.href='{{ route('confirmed')}}'">Goedgekeurd</button>
        <button class="postbtn" onclick="location.href='{{ route('denied')}}'">Geweigerd</button>
        <br>
        <div style="overflow-x:scroll;">
            <div class="participant"><br><br>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Startdatum</th>
                        <th>Einddatum</th>
                        <th>Voornaam</th>
                        <th>Achternaam</th>
                        <th>Telefoonnummer</th>
                        <th>E-mailadres</th>
                        <th>Bedrag</th>
                    </tr>
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
                            <td>{{$order->firstname}} </td>
                            <td>{{$order->lastname}}</td>
                            <td>{{$order->phone}}</td>
                            <td>{{$order->email}}</td>
                            <td>{{$order->total * $days}}</td>
                        </tr>
                    @endforeach
                </table>
                <br>
                <br>
            </div>
        </div>
    </div>
@endsection
