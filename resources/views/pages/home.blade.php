@extends('layouts/pages')
@section('hero')
<section id="hero" class="hero">
    <div class="container position-relative">
        <div class="row gy-5" data-aos="fade-in">
            <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center text-center text-lg-start">
                @if (!empty(auth()->user()->id))
                    <h2>Assalamualaikum,<span> {{ auth()->user()->full_name }}</span></h2>
                @else
                    <h2>Elektronik Absensi<span> (E-Presence)</span></h2>
                @endif
            </div>
            <div class="col-lg-6 order-1 order-lg-2">
                <img src="{{ url('image/logopas.png') }}" class="img-fluid" alt="" data-aos="zoom-out" data-aos-delay="100">
            </div>
        </div>
    </div>

    <div class="icon-boxes position-relative">
        <div class="container position-relative">
        <div class="row gy-4 mt-5">

            <div class="col-sm-3 col-sm-6" data-aos="fade-up" data-aos-delay="100">
            <div class="icon-box">
                <div class="icon"><i class="fa-solid fa-right-to-bracket"></i></div>
                <h4 class="title"><a href="#pilihlokasi" data-bs-toggle="modal" class="stretched-link">Absen Masuk</a></h4>
            </div>
            </div>
            <!--End Icon Box -->

            <div class="col-sm-3 col-sm-6" data-aos="fade-up" data-aos-delay="200">
            <div class="icon-box">
                <div class="icon"><i class="fa-solid fa-right-from-bracket"></i></div>
                <h4 class="title"><a href="{{ url('/user/absen-keluar') }}" class="stretched-link">Absen Pulang</a></h4>
            </div>
            </div>
            <!--End Icon Box -->

            <div class="col-sm-3 col-sm-6" data-aos="fade-up" data-aos-delay="300">
            <div class="icon-box">
                <div class="icon"><i class="fa-solid fa-list-check"></i></div>
                <h4 class="title"><a href="{{ url('/user/izin') }}" class="stretched-link">Izin</a></h4>
            </div>
            </div>
            <!--End Icon Box -->

            @if (!empty(auth()->user()->id))
                <div class="col-sm-3 col-sm-6" data-aos="fade-up" data-aos-delay="500">
                    <div class="icon-box">
                        <div class="icon"><i class="fa-solid fa-history"></i></div>
                        <h4 class="title"><a href="{{ url('user/riwayat-absen') }}" class="stretched-link">Riwayat Absensi</a></h4>
                    </div>
                </div>
            @else
                <div class="col-sm-3 col-sm-6" data-aos="fade-up" data-aos-delay="500">
                    <div class="icon-box">
                        <div class="icon"><i class="fa-solid fa-lock"></i></div>
                        <h4 class="title"><a href="{{ url('/login') }}" class="stretched-link">Log In</a></h4>
                    </div>
                </div>
            @endif

            <!--End Icon Box -->

        </div>
        </div>
    </div>

    </div>
</section>
@endsection
