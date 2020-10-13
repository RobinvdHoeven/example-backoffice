@extends ('layouts/app')

@section('content')
    <h1>Sliders: ({{ count($sliders) }})</h1>
    <h3>Klik op een slider om de slider aan te passen.</h3>
    <div class="participants fullwidth">
        <div style="overflow-x:scroll;">
            <div class="participant"><br>
                <a style="cursor: pointer;" href="/sliders/nieuw"><i class="fa fa-plus"></i> Maak nieuwe slider</a>
                <br><br>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Titel</th>
                    </tr>
                    @foreach($sliders as $slider)
                        <tr style="cursor: pointer;" onclick="location.href='{{ route('slider.show', ['id' => $slider->id]) }}'">
                            <td>{{ $slider->id }}</td>
                            <td>{{ $slider->title_nl }}</td>
                        </tr>
                    @endforeach

                </table>
            </div>

        </div>
    </div>
@endsection
