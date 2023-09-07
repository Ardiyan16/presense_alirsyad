<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>{{ $title }}</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="{{ url('image/logopas.png') }}" rel="icon">
  <link href="{{ url('pages/assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,600;1,700&family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Raleway:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ url('pages/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ url('pages/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ url('pages/assets/vendor/aos/aos.css') }}" rel="stylesheet">
  <link href="{{ url('pages/assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{ url('pages/assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  <!-- Template Main CSS File -->
  <link href="{{ url('pages/assets/css/main.css') }}" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Impact - v1.2.0
  * Template URL: https://bootstrapmade.com/impact-bootstrap-business-website-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>
<style>
    .btn-login {
        background: #d4d700;
        padding: 8px 20px;
        margin-left: 20px;
        border-radius: 8px;
        color: #028000;
    }

    .select2-selection__rendered {
        line-height: 40px !important;
    }

    .select2-container .select2-selection--single {
        height: 40px !important;
    }

    .select2-selection__arrow {
        height: 40px !important;
    }
</style>

<body>
    @include('sweetalert::alert')
  <!-- ======= Header ======= -->
  <section id="topbar" class="topbar d-flex align-items-center">
    <div class="container d-flex justify-content-center justify-content-md-between">
      <div class="contact-info d-flex align-items-center">
        <i class="bi bi-envelope d-flex align-items-center"><a href="mailto:contact@example.com">lpp.alirsyadjember@gmail.com</a></i>
        <i class="bi bi-phone d-flex align-items-center ms-4"><span>+62 812-3234-2727</span></i>
      </div>
      <div class="social-links d-none d-md-flex align-items-center">
        <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
        <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
        <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
        <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></i></a>
      </div>
    </div>
  </section><!-- End Top Bar -->

  <header id="header" class="header d-flex align-items-center">

    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
      <a href="{{ url('/') }}" class="logo d-flex align-items-center">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <img src="{{ url('image/Logo LPP Web.png') }}" alt="">
        {{-- <h1>Impact<span>.</span></h1> --}}
      </a>
      <nav id="navbar" class="navbar">
        <ul>
            @if (!empty(auth()->user()->username))
                <li><a href="{{ url('/user') }}">Home</a></li>
            @else
                <li><a href="{{ url('/') }}">Home</a></li>
            @endif
            <li><a href="#pilihlokasi" data-bs-toggle="modal">Absen Masuk</a></li>
            <li><a href="{{ url('/user/absen-keluar') }}">Absen Pulang</a></li>
            <li><a href="{{ url('/user/izin') }}">Izin</a></li>
            <li><a href="{{ url('/user/riwayat-absen') }}">Riwayat Absensi</a></li>
            @if (!empty(auth()->user()->username))
                <li class="dropdown"><a class="btn btn-primary" style="color: #FFF" href=""><span>{{ auth()->user()->username }}</span></a>
                    <ul>
                        {{-- <li><a href="#">Profile Saya</a></li> --}}
                        <li><a href="{{ url('/logout-user') }}">Log Out</a></li>
                        </ul>
                </li>
            @else
                <li><a class="btn btn-primary" style="color: #FFF" href="{{ url('/login') }}">Masuk</a></li>
            @endif
        </ul>
      </nav><!-- .navbar -->

      <i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
      <i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>

    </div>
  </header><!-- End Header -->
  <!-- End Header -->

  <!-- ======= Hero Section ======= -->
 @yield('hero')
  <!-- End Hero Section -->

  <main id="main">

    @yield('pages_content')

  </main><!-- End #main -->
  <div class="modal fade" id="pilihlokasi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row text-center">
                    <div class="col-sm-12">
                        <a href="{{ url('/user/absen-masuk?jenis_lokasi=in_office') }}">
                            <img src="{{ url('image/dikantor.png') }}" class="image-location-absen" width="350">
                        </a>
                        <a href="{{ url('/user/absen-masuk?jenis_lokasi=in_home') }}">
                            <img src="{{ url('image/dirumah.png') }}" class="image-location-absen" width="350">
                        </a>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            </div>
        </div>
    </div>
  </div>

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">



    <div class="container mt-4">
      <div class="copyright">
        &copy; Copyright <strong><span>Lajnah Pendidikan & Pengajaran</span></strong> Al Irsyad Al Islamiyyah Jember
      </div>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/impact-bootstrap-business-website-template/ -->
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
      </div>
    </div>

  </footer><!-- End Footer -->
  <!-- End Footer -->

  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="{{ url('assets/plugins/jquery-cookie/index.min.js') }}"></script>
  <script src="{{ url('pages/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ url('pages/assets/vendor/aos/aos.js') }}"></script>
  <script src="{{ url('pages/assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
  <script src="{{ url('pages/assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
  <script src="{{ url('pages/assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
  <script src="{{ url('pages/assets/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
  <script src="{{ url('pages/assets/vendor/php-email-form/validate.js') }}"></script>

  <!-- Template Main JS File -->
  <script src="{{ url('pages/assets/js/main.js') }}"></script>
  <script src="{{ url('pages/sweetalert2-all.js') }}"></script>
  <script src="{{ url('assets/js/main.js') }}"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  @yield('js')
</body>

</html>
