@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
            <div class="dash-widget">
                <span class="dash-widget-bg1"><i class="fa fa-users" aria-hidden="true"></i></span>
                <div class="dash-widget-info text-right">
                    <h3 class="jumlah-pegawai"></h3>
                    <span class="widget-title1">Jumlah Pegawai/Guru<i class="fa fa-check" aria-hidden="true"></i></span>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
            <div class="dash-widget">
                <span class="dash-widget-bg2"><i class="fa fa-user-check"></i></span>
                <div class="dash-widget-info text-right">
                    <h3 class="jumlah-absen-masuk"></h3>
                    <span class="widget-title2">Jumlah Absen Masuk <i class="fa fa-check" aria-hidden="true"></i></span>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
            <div class="dash-widget">
                <span class="dash-widget-bg3"><i class="fa fa-user-check" aria-hidden="true"></i></span>
                <div class="dash-widget-info text-right">
                    <h3 class="jumlah-absen-keluar"></h3>
                    <span class="widget-title3">Jumlah Absen Keluar <i class="fa fa-check" aria-hidden="true"></i></span>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
            <div class="dash-widget">
                <span class="dash-widget-bg4"><i class="fa fa-file-lines" aria-hidden="true"></i></span>
                <div class="dash-widget-info text-right">
                    <h3 class="jumlah-izin"></h3>
                    <span class="widget-title4">Jumlah Izin <i class="fa fa-check" aria-hidden="true"></i></span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')

<script>

    $(document).ready(function() {

        $.ajax({
            url: '{{ url("/api/v1/data-dashboard") }}',
            headers: {
                'Authorization': 'Bearer '+get_cookie('ALD_SESSION')
            },
            type: 'GET',
            dataType: 'JSON',
            success: function(res) {

                if(res.status) {

                    $('.jumlah-pegawai').html(res.data.jumlah_pegawai);
                    $('.jumlah-absen-masuk').html(res.data.jumlah_absen_masuk);
                    $('.jumlah-absen-keluar').html(res.data.jumlah_absen_keluar);
                    $('.jumlah-izin').html(res.data.jumlah_izin);
                }

            }
        })

    })

</script>

@endsection
