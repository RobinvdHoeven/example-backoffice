@extends ('layouts/app')

@section('content')
    <h1>{{ $slider->title_nl  }}</h1>
    <div style="font-size: 18px;">
        <b>Wat is een slider?</b> Op de homepagina heb je een "Slideshow" met plaatjes en teksten erop.<br>
        <b>Slidertitel:</b> Dikgedrukt woord dat bovenaan de slider komt, denk bijvoorbeeld aan de naam van een product.<br>
        <b>Slidertekst:</b> Dit is de tekst dat op de slider komt, bijvoorbeeld de beschrijving van een product. (Zorg dat de tekst maximaal uit 60 letters bestaat)<br>
        <b>Sliderfoto:</b> Dit is de foto van de slider.
    </div>
    <br><br>
    <form method="post" action="{{ route('slider.update', $slider->id) }}" enctype="multipart/form-data">
        @csrf
        <table class="table table-striped table-hover" style="width:100%">
            <tbody>
            <tr>
                <td class="{{ $errors->has('title_nl') ? 'error' : '' }}">Slidertitel (Nederlandse site)</td>
                <td><input type="text" name="title_nl" value="{{ old('title_nl', $slider->title_nl) }}"></td>
            </tr>
            <tr>
                <td class="{{ $errors->has('title_de') ? 'error' : '' }}">Slidertitel (Duitse site)</td>
                <td><input type="text" name="title_de" value="{{ old('title_de', $slider->title_de) }}"></td>
            </tr>
            <tr>
                <td class="{{ $errors->has('title_fr') ? 'error' : '' }}">Slidertitel (Franse site)</td>
                <td><input type="text" name="title_fr" value="{{ old('title_fr', $slider->title_fr) }}"></td>
            </tr>
            <tr>
                <td class="{{ $errors->has('title_en') ? 'error' : '' }}">Slidertitel (Engelse site)</td>
                <td><input type="text" name="title_en" value="{{ old('title_en', $slider->title_en) }}"></td>
            </tr>
            <tr>
                <td class="{{ $errors->has('text_nl') ? 'error' : '' }}">Slidertekst (Nederlandse site)</td>
                <td><textarea id="text_nl" name="text_nl" rows="4" cols="50">{{ old('text_nl', $slider->text_nl) }}</textarea></td>
            </tr>
            <tr>
                <td class="{{ $errors->has('text_de') ? 'error' : '' }}">Slidertekst (Duitse site)</td>
                <td><textarea id="text_de" name="text_de" rows="4" cols="50">{{ old('text_de', $slider->text_de) }}</textarea></td>
            </tr>
            <tr>
                <td class="{{ $errors->has('text_fr') ? 'error' : '' }}">Slidertekst (Franse site)</td>
                <td><textarea id="text_fr" name="text_fr" rows="4" cols="50">{{ old('text_fr', $slider->text_fr) }}</textarea></td>
            </tr>
            <tr>
                <td class="{{ $errors->has('text_en') ? 'error' : '' }}">Slidertekst (Engelste site)</td>
                <td><textarea id="text_en" name="text_en" rows="4" cols="50">{{ old('text_en', $slider->text_en) }}</textarea></td>
            </tr>
            <tr>
                <td class="{{ $errors->has('link_nl') ? 'error' : '' }}">Sliderbutton link (Nederlandse site)</td>
                <td><input type="text" name="link_nl" value="{{ old('link_nl', $slider->link_nl) }}"></td>
            </tr>
            <tr>
                <td class="{{ $errors->has('link_de') ? 'error' : '' }}">Sliderbutton link (Duitse site)</td>
                <td><input type="text" name="link_de" value="{{ old('link_de', $slider->link_de) }}"></td>
            </tr>
            <tr>
                <td class="{{ $errors->has('link_fr') ? 'error' : '' }}">Sliderbutton link (Franse site)</td>
                <td><input type="text" name="link_fr" value="{{ old('link_fr', $slider->link_fr) }}"></td>
            </tr>
            <tr>
                <td class="{{ $errors->has('link_en') ? 'error' : '' }}">Sliderbutton link (Engelse site)</td>
                <td><input type="text" name="link_en" value="{{ old('link_en', $slider->link_en) }}"></td>
            </tr>
            </tbody>
        </table>
        <br><br><br>

        @if(!empty($slider->img))
            <img src="{{ ('/images/') . $slider->img }}" alt="pic" width="700px" height="150px"><br>
            <a id="change">Foto aanpassen</a><br><br>
        @else
            <label for="file">Foto van slider:</label><br>
            <h3>Hoe werkt het?</h3>
            <ul>
                <li>1. Klik op bestand kiezen en kies de gewenste foto.</li>
                <li>2. De blauwe lijn geeft aan welk stuk van de foto je selecteert, sleep de box naar de gewenste positie.</li>
                <li>3. Klik vervolgens op bijsnijden, je ziet nu de bijgesneden foto.</li>
                <li>4. Als je blij bent met de bijgesneden foto klik op "Maak product"</li>
                <li><b>Tip:</b> als je de foto wilt in of uitzoomen, gebruik je scrollwheel op je muis.</li>
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
        @endif
        <div id="hiddendiv">
            <label for="file">Slider foto:</label><br>
            <h3>Hoe werkt het?</h3>
            <ul>
                <li>1. Klik op bestand kiezen en kies de gewenste foto.</li>
                <li>2. De blauwe lijn geeft aan welk stuk van de foto je selecteert, sleep de box naar de gewenste positie.</li>
                <li>3. Klik vervolgens op bijsnijden, je ziet nu de bijgesneden foto.</li>
                <li>4. Als je blij bent met de bijgesneden foto klik op "Update"</li>
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
        </div>
        <br>
        <button type="submit" class="postbtn">Update</button>
    </form>
    <br>
    <form method="post" action="{{ route('slider.delete', $slider->id) }}" enctype="multipart/form-data">
        @csrf

        <button class="postbtn" type="submit" onclick="return confirm('Weet je zeker dat je deze slider wilt verwijderen?');">Verwijder slider</button>
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
    <script>
        $('#change').click(function () {
            $('#hiddendiv').toggle();

        });
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

        #hiddendiv {
            display: none;
        }
    </style>
@endsection
