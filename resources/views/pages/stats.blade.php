@extends ('layouts/app')

@section('content')

    <h1 class="stats">Statistieken</h1>

    <div>
        <label style="font-size: 16px;">Selecteer een pagina:</label>
        <select name="page" onchange="if (this.value) window.location.href=this.value" class="stats">
            @foreach ($pagesOptions as $option)
                <option {{ (app('request')->input('page') == $option['page'] ? 'selected="selected"' : '') }} value="{{ route('stats', ['page' => $option['page']]) }}">{{ $option['title'] }}</option>
            @endforeach
        </select>
    </div>

    @if(!empty($panels))


        @foreach($panels as $i => $panel)

            <div class="grid-item statspage">


                @if (array_keys($panel)[0] == 'main_title')
                    <h1>{{ $panel['main_title'] }}</h1>
                @else

                    <div class="panel">

                        <h2 class="stats" onclick="toggle('{{ $i }}');">{{ $panel['title'] }}</h2>

                        <div id="{{ $i }}" class="body">

                            @if(!empty($panel['data']))
                                <p style="padding: 5px;"><em>{{ $panel['description'] }}</em></p>
                            @endif

                            <table cellpadding="0" cellspacing="0" class="tablewidth">
                                <tr class="stats">

                                    @if(!empty($panel['headers']))

                                        @foreach($panel['headers'] as $header)
                                            <th class="stats" {{ ($header=='Aantal' || $header=='Percentueel'?' width=20%':'') }}>{{ $header }}</th>
                                        @endforeach

                                    @endif

                                </tr>

                                @if(!empty($panel['data']))

                                    @php
                                        $total = 0;
                                        $totalPercentage = 0;
                                    @endphp


                                    @foreach($panel['data'] as $data)

                                        <tr class="stats">

                                            @if(!empty($data))

                                                @foreach($data as $y => $value)

                                                    <td class="stats">
                                                        @if (strtotime($value) && $y == 'date_only')
                                                            {{ date('d-m-Y', strtotime($value)) }}
                                                        @elseif ($y == 'percentage')
                                                            {{ $value }}%
                                                        @else
                                                            {{ $value }}
                                                        @endif
                                                    </td>

                                                    @php
                                                        if ($y == 'totalID')
                                                            $total = ($total + $value);

                                                        if ($y == 'percentage')
                                                            $totalPercentage = ($totalPercentage + $value);
                                                    @endphp

                                                @endforeach

                                            @endif

                                        </tr>

                                    @endforeach


                                    <tr class="total stats">
                                        <td class="stats" colspan="{{ $panel['colspan'] }}"></td>
                                        <td class="stats">{{ $total }}</td>
                                        <td class="stats">{{ number_format($totalPercentage) }}%</td>
                                    </tr>

                                @endif

                            </table>
                        </div>
                    </div>

                @endif

            </div>

        @endforeach

    @endif

    <i class="stats">Statistieken samengesteld om <b><?= date('H:i'); ?></b> op <b><?= date('d-m-Y'); ?></b></i>


    <script>

        setInterval(function () {
            location.reload()
        }, (60000 * 5));

        function toggle(id) {
            var div = document.getElementById(id);
            div.style.display = div.style.display == "none" ? "block" : "none";
        }

    </script>


    <style>


    </style>

@endsection
