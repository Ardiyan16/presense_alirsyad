@extends('layouts/pages')
@section('pages_content')

<section id="contact" class="contact">
    <div class="container" data-aos="fade-up">

    <div class="section-header">
        <h2>Absen Pulang</h2>
    </div>

    <div class="row gx-lg-0 gy-4">

        <div class="col-lg-12">
            <div id="mapcanvas"></div>
        </div>

        <div class="col-lg-12">
            {{-- <form action="#"> --}}
                <div class="row">
                    <div class="col-md-2 form-group">
                        <button class="btn btn-primary" type="button" onclick="getLocation()">Lokasi</button>
                    </div>
                    <div class="col-md-5 form-group mt-3 mt-md-0">
                        {{-- <input type="file" class="form-control" id="" placeholder="Longitude" disabled> --}}
                        <input type="text" class="form-control" id="longitude" placeholder="Longitude" disabled>
                        {{-- <input type="hidden" class="form-control" id="presence_status" value="{{ $jenis_lokasi }}" placeholder="Longitude" disabled> --}}
                    </div>
                    <div class="col-md-5 form-group mt-3 mt-md-0">
                        <input type="text" class="form-control" id="latitude" placeholder="Latitude" disabled>
                    </div>
                </div>
                <br>
                <div class="text-center"><button type="submit" class="btn btn-primary btn-simpan-absen">Simpan</button></div>
            {{-- </form> --}}
        </div><!-- End Contact Form -->

    </div>

    </div>
</section><!-- End Contact Section -->

@endsection
@section('js')
<script src="https://maps.googleapis.com/maps/api/js?&key=AIzaSyAFTimIhQoFCg8bF7PAMgDWi38QqqvaCx8" async defer></script>
    <script>
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, showError);
            } else {
                view.innerHTML = "Yah browsernya ngga support Geolocation bro!";
            }
        }

        function showPosition(position) {
            lat = position.coords.latitude;
            lon = position.coords.longitude;
            $('#longitude').val(position.coords.longitude)
            $('#latitude').val(position.coords.latitude)
            $('#detail_location').val(position.coords.place)
            latlon = new google.maps.LatLng(lat, lon)
            mapcanvas = document.getElementById('mapcanvas')
            mapcanvas.style.height = '400px';
            mapcanvas.style.width = 'auto';

            var myOptions = {
                center: latlon,
                zoom: 19,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            }

            var map = new google.maps.Map(document.getElementById("mapcanvas"), myOptions);
            var marker = new google.maps.Marker({
                position: latlon,
                map: map,
                title: "You are here!"
            });
        }

        function showError(error) {
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    view.innerHTML = "Yah, mau deteksi lokasi tapi ga boleh :("
                    break;
                case error.POSITION_UNAVAILABLE:
                    view.innerHTML = "Yah, Info lokasimu nggak bisa ditemukan nih"
                    break;
                case error.TIMEOUT:
                    view.innerHTML = "Requestnya timeout bro"
                    break;
                case error.UNKNOWN_ERROR:
                    view.innerHTML = "An unknown error occurred."
                    break;
            }
        }

    </script>

    <script>
            $(document).ready(function() {

                $(document).on('click', '.btn-simpan-absen', function() {
                    let user_id = {{ auth()->user()->id }};
                    let longitude = $('#longitude').val();
                    let latitude = $('#latitude').val();


                    if(longitude == "" || latitude == "") {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Peringatan!',
                            text: 'silahkan klik button lokasi terlebih dahulu'
                        });
                    } else {

                        $.ajax({
                            url: '{{ url("/api/v1/simpan-absen-keluar") }}',
                            headers: {
                                'Authorization': 'Bearer '+get_cookie('ALD_SESSION')
                            },
                            type: 'POST',
                            dataType: "JSON",
                            data: {
                                "user_id": user_id,
                                "longitude": longitude,
                                "latitude": latitude
                            },

                            success: function(res) {

                                if(res.status) {

                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: res.message,
                                        timer: 2000,
                                        showCancelButton: false,
                                        showConfirmButton: false
                                    })
                                    .then (function() {
                                        window.location.href = "{{ url('/user/absen-keluar') }}";
                                    });

                                } else {

                                    if(res.info) {
                                        Swal.fire({
                                            icon: 'info',
                                            title: 'Informasi!',
                                            text: res.message
                                        });

                                    } else {
                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'Gagal!',
                                            text: res.message
                                        });
                                    }

                                }

                            }
                        })

                    }
                })

            });

    </script>
@endsection
