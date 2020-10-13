@extends ('layouts/app')
<?php use Carbon\Carbon; ?>
@section('content')
    <div class="participants fullwidth">
        <h1 style="margin-top:0;">Afgekeurd: ({{$orders->total()}})</h1>
        <button class="postbtn" onclick="location.href='{{ route('pending')}}'">In afwachting</button>
        <button class="postbtn" onclick="location.href='{{ route('confirmed')}}'">Goedgekeurd</button>
        <button class="postbtn" style="color:#f7ea4e;" onclick="location.href='{{ route('denied')}}'">Geweigerd</button>
        <br>
        <h3>Zoek op ID, voornaam, achternaam of e-mailadres.</h3>
        <div class="search">
            <form method="post" class="searchbar" action="{{ route('orders.searchdenied') }}">
                {{ csrf_field() }}
                <input class="form-control" value="{{ old('search') }}" name="search" type="search" placeholder="Zoeken" aria-label="Zoeken">
                <button class="btn" type="submit"><i class="fa fa-search"></i></button>
            </form>
            <br><br>
            @if(count($orders) != 0)
                @if(\Request::is('bestellingen/zoekenorders/afgekeurd'))
                    <form class="searchbar" action="{{ route('orders.exportsearch') }}">
                        <input class="form-control" value="{{ old('search') }}" name="search" type="hidden" placeholder="Zoeken" aria-label="Zoeken">
                        <button class="postbtn" type="submit">Download naar Excel</button>
                    </form>
                @else
                    <form class="searchbar" action="{{ route('orders.exportdenied') }}">
                        <button class="postbtn" type="submit">Download naar Excel</button>
                    </form>
                @endif
            @endif
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
                            <th>Voornaam</th>
                            <th>Achternaam</th>
                            <th>Telefoonnummer</th>
                            <th>E-mailadres</th>
                            <th>Bedrag</th>
                        </tr>
                    @else
                        <tr>
                            <th><a href="?orderby=id&type=<?= ($type == 'asc' ? 'desc' : 'asc') ?>">ID</a></th>
                            <th><a href="?orderby=date_start&type=<?= ($type == 'asc' ? 'desc' : 'asc') ?>">Begindatum</a></th>
                            <th><a href="?orderby=date_end&type=<?= ($type == 'asc' ? 'desc' : 'asc') ?>">Einddatum</a></th>
                            <th><a href="?orderby=firstname&type=<?= ($type == 'asc' ? 'desc' : 'asc') ?>">Voornaam</a></th>
                            <th>Achternaam</th>
                            <th>Telefoonnummer</th>
                            <th><a href="?orderby=email&type=<?= ($type == 'asc' ? 'desc' : 'asc') ?>">E-mailadres</a></th>
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
                            <td>{{$order->firstname}} </td>
                            <td>{{$order->lastname}}</td>
                            <td>{{$order->phone}}</td>
                            <td>{{$order->email}}</td>
                            <td>{{$order->total * $days}}</td>
                        </tr>
                    @endforeach
                </table>
                <br>
                {{ $orders->links() }}
                <br>
                <br>
            </div>
        </div>
    </div>
@endsection
