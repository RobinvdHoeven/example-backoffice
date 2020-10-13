@extends ('layouts/app')

@section('content')
    <h1>Nieuwe slider aanmaken</h1>
    <div style="font-size: 18px;">
        <b>Wat is een slider?</b> Op de homepagina heb je een "Slideshow" met plaatjes en teksten erop.<br>
        <b>Slidertitel:</b> Dikgedrukt woord dat bovenaan de slider komt, denk bijvoorbeeld aan de naam van een product.<br>
        <b>Slidertekst:</b> Dit is de tekst dat op de slider komt, bijvoorbeeld de beschrijving van een product. (Zorg dat de tekst maximaal uit 60 letters bestaat)<br>
        <b>Sliderfoto:</b> Dit is de foto van de slider.<br>
        <b>Sliderbutton link:</b> Dit is de link waar de roze knop naar toe gaat.
    </div>
    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
    <br><br>
    <form action="{{ route('newslider') }}" method="post" enctype="multipart/form-data">
        @csrf
        <label for="title_nl">Slider titel (NL):</label><br>
        <input type="text" id="title_nl" name="title_nl" value="{{ old('title_nl') }}"><br><br>
        <label for="title_de">Slider titel (DE):</label><br>
        <input type="text" id="title_de" name="title_de" value="{{ old('title_de') }}"><br><br>
        <label for="title_fr">Slider titel (FR):</label><br>
        <input type="text" id="title_fr" name="title_fr" value="{{ old('title_fr') }}"><br><br>
        <label for="title_en">Slider titel (EN):</label><br>
        <input type="text" id="title_en" name="title_en" value="{{ old('title_en') }}"><br><br>
        <label for="text_nl">Slider tekst (NL):</label><br>
        <textarea id="text_nl" name="text_nl" rows="4" cols="50" placeholder="Max 60 letters">{{ old('text_nl') }}
</textarea><br><br>
        <label for="text_de">Slider tekst (DE):</label><br>
        <textarea id="text_de" name="text_de" rows="4" cols="50" placeholder="Max 60 letters">{{ old('text_de') }}
</textarea><br><br>
        <label for="text_fr">Slider tekst (FR):</label><br>
        <textarea id="text_fr" name="text_fr" rows="4" cols="50" placeholder="Max 60 letters">{{ old('text_fr') }}
</textarea><br><br>
        <label for="text_en">Slider tekst (EN):</label><br>
        <textarea id="text_en" name="text_en" rows="4" cols="50" placeholder="Max 60 letters">{{ old('text_en') }}
</textarea><br>
        <label for="link_nl">Sliderbutton link (NL):</label><br>
        <input type="text" id="link_nl" name="link_nl" value="{{ old('link_nl') }}" placeholder="voorbeeld: product/wipstoel"><br><br>
        <label for="link_en">Sliderbutton link (EN):</label><br>
        <input type="text" id="link_en" name="link_en" value="{{ old('link_en') }}" placeholder="voorbeeld: product/bouncechair"><br><br>
        <label for="link_de">Sliderbutton link (DE):</label><br>
        <input type="text" id="link_de" name="link_de" value="{{ old('link_de') }}" placeholder="voorbeeld: product/bouncechair"><br><br>
        <label for="link_fr">Sliderbutton link (FR):</label><br>
        <input type="text" id="link_fr" name="link_fr" value="{{ old('link_fr') }}" placeholder="voorbeeld: product/bouncechair"><br><br>
        <br><br>
        <label for="file">Slider foto:</label><br>
        <h3>Hoe werkt het?</h3>
        <ul>
            <li>1. Klik op bestand kiezen en kies de gewenste foto.</li>
            <li>2. De blauwe lijn geeft aan welk stuk van de foto je selecteert, sleep de box naar de gewenste positie.</li>
            <li>3. Klik vervolgens op bijsnijden, je ziet nu de bijgesneden foto.</li>
            <li>4. Als je blij bent met de bijgesneden foto klik op "Maak slider"</li>
            <li><b>Tip:</b> Als je de foto wilt in of uitzoomen, gebruik het scrollwheel op je muis.</li>
        </ul>
        <br>
        <input type="hidden" name="croppedimage" id="croppedimage">
        <input type="file" id="image" name="image" accept="image/*">
        <input style="width: 100px" type="button" id="btnCrop" value="Bijsnijden"/>
        <input style="width: 100px" type="button" id="btnRestore" value="Reset"/>
        <br><br>
        <div id="cropcontainer">
            <canvas id="canvas">

            </canvas>
        </div>

        <div id="result"></div>
        <br>
        <input type="submit" value="Maak product" class="postbtn" id="postform">
    </form>
    <br>
    <script>
        var canvas = $("#canvas"),
            context = canvas.get(0).getContext("2d"),
            $result = $('#result');

        $('#image').on('change', function () {
                $('#result').html = "";
                $('#image').value = "";
                if (this.files && this.files[0]) {
                    if (this.files[0].type.match(/^image\//)) {
                        var reader = new FileReader();
                        reader.onload = function (evt) {
                            var img = new Image();
                            img.onload = function () {
                                context.canvas.height = 750;
                                context.canvas.width = 1500;
                                context.drawImage(img, 0, 0, 1500, 750);
                                canvas.cropper('destroy');
                                var cropper = canvas.cropper;
                                $result.empty();

                                canvas.cropper({
                                    width: 1500,
                                    height: 550,
                                    maxWidth: 1500,
                                    maxHeight: 550,
                                    autoCropArea: 0,
                                    strict: false,
                                    guides: false,
                                    highlight: false,
                                    dragCrop: false,
                                    scale: false,
                                    cropBoxMovable: true,
                                    cropBoxResizable: false,
                                    data: {
                                        width: 1500,
                                        height: 550,
                                    },
                                });

                                $('#btnCrop').click(function () {
                                    // Get a string base 64 data url
                                    var croppedImageDataURL = canvas.cropper("getCroppedCanvas", {width: 1500, height: 550}).toDataURL("image/png", 1);
                                    $result.html($('<h1>Bijgesneden foto:</h1><img>').attr('src', croppedImageDataURL));


                                    $.ajax({
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        },
                                        type: 'POST',
                                        url: '{{ route('getCroppedImage')}}',
                                        data: {
                                            imageData: croppedImageDataURL,
                                        },
                                        success: function (data) {
                                            console.log(data.imagepath);
                                            $('#croppedimage').val(data.imagepath);
                                        }
                                    });
                                });


                                $('#btnRestore').click(function () {
                                    canvas.cropper('reset');
                                    $('#croppedimage').val('');
                                    $result.empty();
                                });
                            };
                            img.src = evt.target.result;
                        };
                        reader.readAsDataURL(this.files[0]);
                    } else {
                        alert("Invalid file type! Please select an image file.");
                    }
                } else {
                    alert('No file(s) selected.');
                }
            }
        );
    </script>

    <style>
        img {
            max-width: 100%; /* This rule is very important, please do not ignore this! */
        }

        #result > img {
            width: 750px;
            height: 275px;
        }

        #cropcontainer {
            height: 275px;
            width: 750px;
        }

        #canvas {
            height: 275px;
            width: 750px;
            background-color: #ffffff;
            cursor: default;
            border: 1px solid black;
        }
    </style>
@endsection
