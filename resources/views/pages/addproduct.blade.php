@extends ('layouts/app')

@section('content')
    <h1>Nieuw product aanmaken</h1>
    <div style="font-size: 18px;">
        <b>Productnaam: </b> De naam van een product.<br>
        <b>Producttekst: </b> Elk product heeft een eigen pagina, dit is de tekst dat op die pagina komt en het product beschrijft.<br>
        <b>Productfoto: </b> Dit is de foto van het product.

    </div>
    <br><br>
    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('createproduct') }}" method="post">
        @csrf
        <label for="name_nl">Product naam (NL):</label><br>
        <input type="text" id="name_nl" name="name_nl" value="{{ old('name_nl') }}"><br><br>
        <label for="name_en">Product naam (EN):</label><br>
        <input type="text" id="name_en" name="name_en" value="{{ old('name_en') }}"><br><br>
        <label for="name_fr">Product naam (FR):</label><br>
        <input type="text" id="name_fr" name="name_fr" value="{{ old('name_fr') }}"><br><br>
        <label for="name_de">Product naam (DE):</label><br>
        <input type="text" id="name_de" name="name_de" value="{{ old('name_de') }}"><br><br>
        <label for="price">Product prijs:</label><br>
        <input type="number" id="price" name="price" value="{{ old('price') }}"><br>
        <br>
        <p>Tekst dat op de pagina komt van het product.</p>
        <label for="text_nl">Tekst voor product(NL):</label><br>
        <textarea id="text_nl" name="text_nl" rows="4" cols="50">{{ old('text_nl') }}
</textarea><br><br>
        <label for="text_de">Tekst voor product (DE):</label><br>
        <textarea id="text_de" name="text_de" rows="4" cols="50">{{ old('text_de') }}
</textarea><br><br>
        <label for="text_fr">Tekst voor product(FR):</label><br>
        <textarea id="text_fr" name="text_fr" rows="4" cols="50">{{ old('text_fr') }}
</textarea><br><br>
        <label for="text_en">Tekst voor product(EN):</label><br>
        <textarea id="text_en" name="text_en" rows="4" cols="50">{{ old('text_en') }}
</textarea><br>
        <label for="stock">Hoeveel kunnen er gehuurd worden? (Voorraad):</label><br>
        <input type="number" id="stock" name="stock" min="1" value="{{ old('stock') }}"><br>
        <br>
        <label for="file">Foto van product:</label><br>
        <h3>Hoe werkt het?</h3>
        <ul>
            <li>1. Klik op bestand kiezen en kies de gewenste foto.</li>
            <li>2. De blauwe lijn geeft aan welk stuk van de foto je selecteert, sleep de box naar de gewenste positie.</li>
            <li>3. Klik vervolgens op bijsnijden, je ziet nu de bijgesneden foto.</li>
            <li>4. Als je blij bent met de bijgesneden foto klik op "Maak product"</li>
            <li><b>Tip:</b> Als je de foto wilt in of uitzoomen, gebruik je scrollwheel op je muis.</li>
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
                                context.canvas.height = 450;
                                context.canvas.width = 670;
                                context.drawImage(img, 0, 0, 670, 450);
                                canvas.cropper('destroy');
                                var cropper = canvas.cropper;
                                $result.empty();

                                canvas.cropper({
                                    width: 670,
                                    height: 450,
                                    maxWidth: 670,
                                    maxHeight: 450,
                                    autoCropArea: 0,
                                    strict: false,
                                    guides: false,
                                    highlight: false,
                                    dragCrop: false,
                                    scale: false,
                                    cropBoxMovable: true,
                                    cropBoxResizable: false,
                                });

                                $('#btnCrop').click(function () {
                                    // Get a string base 64 data url
                                    var croppedImageDataURL = canvas.cropper("getCroppedCanvas", {width: 670, height: 450}).toDataURL("image/png", 1);
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
            width: 488px;
            height: 337px;
        }

        #cropcontainer {
            height: 337px;
            width: 488px;
        }

        #canvas {
            height: 337px;
            width: 488px;
            background-color: #ffffff;
            cursor: default;
            border: 1px solid black;
        }
    </style>
@endsection
