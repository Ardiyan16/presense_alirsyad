<!DOCTYPE html>
<html lang="en">
<head>
	<title>Log In</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
<!--===============================================================================================-->
    <link href="{{ url('image/logopas.png') }}" rel="icon">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ url('auth/vendor/bootstrap/css/bootstrap.min.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ url('auth/fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ url('auth/fonts/iconic/css/material-design-iconic-font.min.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ url('auth/vendor/animate/animate.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ url('auth/vendor/css-hamburgers/hamburgers.min.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ url('auth/vendor/animsition/css/animsition.min.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ url('auth/vendor/select2/select2.min.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ url('auth/vendor/daterangepicker/daterangepicker.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ url('auth/css/util.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ url('auth/css/main.css') }}">
<!--===============================================================================================-->
</head>
<body>

	<div class="limiter">
		<div class="container-login100" style="background-image: url('images/bg-01.jpg');">
			<div class="wrap-login100">
				{{-- <form class="login100-form"> --}}
					<span class="login100-form-logo">
						<img src="{{ url('image/logopas.png') }}" height="100" width="150">
					</span>

					<span class="login100-form-title p-b-34 p-t-27">
						Lupa Password
					</span>

					<div class="wrap-input100 validate-input" data-validate = "Enter username">
						<input class="input100 email" type="text" name="email" placeholder="Email">
						<span class="focus-input100" data-placeholder="&#xf207;"></span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="Enter password">
						<input class="input100 password" type="password" name="password" placeholder="Password Baru">
						<span class="focus-input100" data-placeholder="&#xf191;"></span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="Enter password">
						<input class="input100 konfir_password" type="password" name="konfirmasi_password" placeholder="Konfirmasi Password">
						<span class="focus-input100" data-placeholder="&#xf191;"></span>
					</div>

					<div class="container-login100-form-btn">
						<button class="login100-form-btn submit-login">Ubah Password</button>
					</div>

                    <div class="text-center p-t-90">
						<a class="txt1" href="{{ url('/login') }}">
							Sudah memiliki akun, silahkan log in
						</a>
					</div>

				{{-- </form> --}}
			</div>
		</div>
	</div>


	<div id="dropDownSelect1"></div>

<!--===============================================================================================-->
	<script src="{{ url('auth/vendor/jquery/jquery-3.2.1.min.js') }}"></script>
	<script src="{{ url('assets/plugins/jquery-cookie/index.min.js') }}"></script>
<!--===============================================================================================-->
	<script src="{{ url('auth/vendor/animsition/js/animsition.min.js') }}"></script>
<!--===============================================================================================-->
	<script src="{{ url('auth/vendor/bootstrap/js/popper.js') }}"></script>
	<script src="{{ url('auth/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
<!--===============================================================================================-->
	<script src="{{ url('auth/vendor/select2/select2.min.js') }}"></script>
<!--===============================================================================================-->
	<script src="{{ url('auth/vendor/daterangepicker/moment.min.js') }}"></script>
	<script src="{{ url('auth/vendor/daterangepicker/daterangepicker.js') }}"></script>
<!--===============================================================================================-->
	<script src="{{ url('auth/vendor/countdowntime/countdowntime.js') }}"></script>
<!--===============================================================================================-->
	<script src="{{ url('auth/js/main.js') }}"></script>
    <script src="{{url('assets/js/main.js')}}"></script>

    <script src="{{ url('pages/sweetalert2-all.js') }}"></script>

    <script type="text/javascript">
        // $(document).ready(function() {
        //     $('.form-checkbox').click(function() {
        //         if ($(this).is(':checked')) {
        //             $('.password').attr('type', 'text');
        //         } else {
        //             $('.password').attr('type', 'password');
        //         }
        //     });
        // });

        $(document).ready(function() {
            $(document).on('click', '.submit-login', function() {
                let email = $('.email').val();
                    password = $('.password').val();
                    konfir_password = $('.konfir_password').val();
                    token = $("meta[name='csrf-token']").attr("content");

                if(email == "") {

                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan!',
                        text: 'Email harus diisi'
                    });

                } else if(password == "") {

                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan!',
                        text: 'Password harus diisi'
                    });

                } else if(konfir_password == "") {

                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan!',
                        text: 'Konfirmasi password harus diisi'
                    });

                } else if(password != konfir_password) {

                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan!',
                        text: 'Konfirmasi password tidak sesuai'
                    });

                } else {

                    $.ajax({
                        url: '{{url("api/ubah-password")}}',
                        type: 'POST',
                        dataType: "JSON",
                        data: {
                            "email": email,
                            "password": password,
                        },
                        success: function(res) {

                            if(res.success) {

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: res.message,
                                    timer: 3000,
                                    showCancelButton: false,
                                    showConfirmButton: false
                                })
                                .then (function() {
                                    window.location.href = "{{ url('/login') }}";
                                });

                            } else {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Gagal!',
                                    text: res.message
                                });
                            }

                        }
                    })
                }
            })
        })

       

    </script>

</body>
</html>
