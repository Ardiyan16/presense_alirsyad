@extends('layouts.admin')
@section('content')

<div class="content">
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-title">Detail Presensi <span class="nama-pegawai-judul"></span></h4>
            <a href="{{ url('dashboard/data-presensi') }}" class="btn btn-success btn-modal-tambah"><i class="fa fa-arrow-circle-left"></i> Kembali</a>
        </div>
    </div>
    <div class="row" style="margin-top: 20px">
        <div class="col-sm-12">
            <div class="card-box">
                <div class="card-block">
                    <h6 class="card-title text-bold">Filter Presensi</h6>
                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <label>Bulan</label>
                            <select class="form-control select-filter-bulan" id="filter_bulan">
                                <option selected disabled>Pilih Bulan</option>
                                <option value="01">Januari</option>
                                <option value="02">Februari</option>
                                <option value="03">Maret</option>
                                <option value="04">April</option>
                                <option value="05">Mei</option>
                                <option value="06">Juni</option>
                                <option value="07">Juli</option>
                                <option value="08">Agustus</option>
                                <option value="09">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label>Tahun</label>
                            <select class="form-control select-filter-tahun" id="filter_tahun">
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <button class="btn btn-success btn-filter" style="margin-top: 30px" type="submit"><i class="fa fa-filter"></i> Filter</button>
                            <button class="btn btn-success btn-refresh" style="margin-top: 30px" type="submit"><i class="fa fa-refresh"></i> Refresh</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card-box">
                <h4 class="card-title">Basic tabs</h4>
                <ul class="nav nav-tabs">
                    <li class="nav-item"><a class="nav-link active" href="#basictab1" data-toggle="tab">Absen Masuk</a></li>
                    <li class="nav-item"><a class="nav-link" href="#basictab2" data-toggle="tab">Absen Pulang</a></li>
                    <li class="nav-item"><a class="nav-link" href="#basictab3" data-toggle="tab">Izin</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane show active" id="basictab1">
                        <div class="row" style="margin-top: 20px">
                            <div class="col-sm-12">
                                <div class="card-box">
                                    <div class="card-block">
                                        <h6 class="card-title text-bold">List Detail Presensi Masuk</h6>
                                        <div class="table-responsive">
                                            <table class="datatable table table-stripped tabel-detail-presensi-masuk" style="width: 100%" id="data_detail_presensi_masuk">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Tanggal Absen</th>
                                                        <th>Waktu</th>
                                                        <th>Status</th>
                                                        <th>Lokasi</th>
                                                        {{-- <th></th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="basictab2">
                        <div class="row" style="margin-top: 20px">
                            <div class="col-sm-12">
                                <div class="card-box">
                                    <div class="card-block">
                                        <h6 class="card-title text-bold">List Detail Presensi Keluar</h6>
                                        <div class="table-responsive">
                                            <table class="datatable table table-stripped tabel-detail-presensi-keluar" style="width: 100%" id="data_detail_presensi_keluar">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Tanggal Absen</th>
                                                        <th>Waktu</th>
                                                        <th>Status</th>
                                                        <th>Lokasi</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="basictab3">
                        <div class="row" style="margin-top: 20px">
                            <div class="col-sm-12">
                                <div class="card-box">
                                    <div class="card-block">
                                        <h6 class="card-title text-bold">List Detail Izin</h6>
                                        <div class="table-responsive">
                                            <table class="datatable table table-stripped tabel-detail-izin" style="width: 100%" id="data_detail_izin">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Tanggal</th>
                                                        <th>Waktu</th>
                                                        <th>Jenis Izin</th>
                                                        <th>Keterangan</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('js')

<script>

    $(document).ready(function() {

        $('#filter_bulan').select2();
        var tahun_sekarang = new Date().getFullYear();
        var tahun_batas = '2022';
        let option = `<option selected disabled>Pilih Tahun</option>`;
        for(i = tahun_batas ;i <= tahun_sekarang; i++) {
            option += '<option value="'+ i + '">'+ i +'</option>'
        }
        $('#filter_tahun').html(option).select2()

        var user_id = {{ $user_id }};

        $.ajax({
            url: '{{ url("/api/v1/data-by-userid") }}/' + user_id,
            headers: {
                'Authorization': 'Bearer '+get_cookie('ALD_SESSION')
            },
            type: 'GET',
            dataType: 'JSON',
            success: function(res) {

                if(res.status) {

                    $(".nama-pegawai-judul").html(res.data.full_name);

                }

            }
        })

        function table_presence_in(params = []) {
            let bulan = params['bulan'];
            let tahun = params['tahun'];

            $('table.tabel-detail-presensi-masuk').DataTable().destroy();
            $('table.tabel-detail-presensi-masuk').DataTable({
                ajax: {
                    url: '{{ url("/api/v1/data-detail-presensi-masuk") }}',
                    headers: {
                        'Authorization': 'Bearer '+get_cookie('ALD_SESSION')
                    },
                    data: {
                        "bulan": bulan,
                        "tahun": tahun,
                        "user_id": user_id
                    },
                    type: 'POST',

                },
                serverSide: true,
                processing: true,
                aaSorting:[[0,"desc"]],
                columns: [
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1 + '.';
                        }
                    },
                    {data: 'tanggal_absen', name: 'tanggal_absen'},
                    {data: 'waktu', name: 'waktu'},
                    {data: null, render: res => {
                        let span = '';
                        if(res.status == 'ok') {
                            span = '<span class="badge bg-success" style="color: #FFF">Tepat Waktu</span>'
                        } else {
                            span = '<span class="badge bg-danger" style="color: #FFF">Terlambat</span>'
                        }

                        return span;
                    }},
                    {data: null, render: res => {
                        let span = '';
                        if(res.lokasi == 'in_office') {
                            span = '<span class="badge bg-success" style="color: #FFF">Di Kantor</span>'
                        } else {
                            span = '<span class="badge bg-primary" style="color: #FFF">Di Rumah</span>'
                        }

                        return span;
                    }},
                ]
            })
        }
        table_presence_in();

        function table_presence_out(params = []) {
            let bulan = params['bulan'];
            let tahun = params['tahun'];

            $('table.tabel-detail-izin').DataTable().destroy();
            $('table.tabel-detail-izin').DataTable({
                ajax: {
                    url: '{{ url("/api/v1/data-detail-izin") }}',
                    headers: {
                        'Authorization': 'Bearer '+get_cookie('ALD_SESSION')
                    },
                    data: {
                        "bulan": bulan,
                        "tahun": tahun,
                        "user_id": user_id
                    },
                    type: 'POST',

                },
                serverSide: true,
                processing: true,
                aaSorting:[[0,"desc"]],
                columns: [
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1 + '.';
                        }
                    },
                    {data: 'tanggal', name: 'tanggal'},
                    {data: 'waktu', name: 'waktu'},
                    {data: null, render: res => {
                        let span = '';
                        if(res.type_permit == 'terlambat') {
                            span = '<span class="badge bg-info" style="color: #FFF">Terlambat</span>'
                        } else {
                            span = '<span class="badge bg-warning" style="color: #FFF">Tidak Hadir</span>'
                        }

                        return span;
                    }},
                    {data: 'necessity', name: 'necessity'},
                ]
            })
        }
        table_presence_out();

        function table_permit(params = []) {
            let bulan = params['bulan'];
            let tahun = params['tahun'];

            $('table.tabel-detail-presensi-keluar').DataTable().destroy();
            $('table.tabel-detail-presensi-keluar').DataTable({
                ajax: {
                    url: '{{ url("/api/v1/data-detail-presensi-keluar") }}',
                    headers: {
                        'Authorization': 'Bearer '+get_cookie('ALD_SESSION')
                    },
                    data: {
                        "bulan": bulan,
                        "tahun": tahun,
                        "user_id": user_id
                    },
                    type: 'POST',

                },
                serverSide: true,
                processing: true,
                aaSorting:[[0,"desc"]],
                columns: [
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1 + '.';
                        }
                    },
                    {data: 'tanggal_absen', name: 'tanggal_absen'},
                    {data: 'waktu', name: 'waktu'},
                    {data: null, render: res => {
                        let span = '';
                        if(res.status == 'ok') {
                            span = '<span class="badge bg-success" style="color: #FFF">Tepat Waktu</span>'
                        } else {
                            span = '<span class="badge bg-danger" style="color: #FFF">Terlambat</span>'
                        }

                        return span;
                    }},
                    {data: null, render: res => {
                        let span = '';
                        if(res.lokasi == 'in_office') {
                            span = '<span class="badge bg-success" style="color: #FFF">Di Kantor</span>'
                        } else {
                            span = '<span class="badge bg-primary" style="color: #FFF">Di Rumah</span>'
                        }

                        return span;
                    }},
                ]
            })

        }
        table_permit();

        $(document).on('click', '.btn-filter', function() {
            let filter_bulan = $('#filter_bulan').val();
            let filter_tahun = $('#filter_tahun').val();

            if((filter_bulan == null) || (filter_tahun == null)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan!',
                    text: 'Tahun dan bulan harus diisi'
                });
            }

            table_presence_in({
                bulan: filter_bulan,
                tahun: filter_tahun
            });

            table_presence_out({
                bulan: filter_bulan,
                tahun: filter_tahun
            });

            table_permit({
                bulan: filter_bulan,
                tahun: filter_tahun
            });


            return false;
        })

        $(document).on('click', '.btn-refresh', function() {
            table_presence_in();
            table_presence_out();
            table_permit();
        })

    })

</script>

@endsection
