@extends('layouts.admin')
@section('content')

<div class="content">
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-title">Data Presensi</h4>
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
                            <button class="btn btn-success btn-filter-presensi" style="margin-top: 30px" type="submit"><i class="fa fa-filter"></i> Filter</button>
                            <button class="btn btn-success btn-refresh" style="margin-top: 30px" type="submit"><i class="fa fa-refresh"></i> Refresh</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top: 20px">
        <div class="col-sm-12">
            <div class="card-box">
                <div class="card-block">
                    <h6 class="card-title text-bold">List Data Presensi</h6>
                    <div class="table-responsive">
                        <table class="datatable table table-stripped tabel-data-presensi" id="data_presensi">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Lengkap</th>
                                    <th>Unit</th>
                                    <th>Jumlah Absen Masuk</th>
                                    <th>Jumlah Absen Keluar</th>
                                    <th>Jumlah Izin</th>
                                    {{-- <th>Jumlah Cuti</th> --}}
                                    <th>Opsi</th>
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

        function load_tabel(params = []) {
            let bulan = params['bulan'];
            let tahun = params['tahun'];

            $("table.tabel-data-presensi").DataTable().destroy();
            $("table.tabel-data-presensi").DataTable({
                ajax: {
                    url: '{{ url('/api/v1/data-presensi') }}',
                    headers: {
                        'Authorization': 'Bearer '+get_cookie('ALD_SESSION')
                    },
                    data: {
                        "bulan": bulan,
                        "tahun": tahun
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
                    {data: 'nama_lengkap', name: 'nama_lengkap'},
                    {data: 'unit', name: 'unit'},
                    {data: 'jml_absen_masuk', name: 'jml_absen_masuk'},
                    {data: 'jml_absen_keluar', name: 'jml_absen_keluar'},
                    {data: 'jml_izin', name: 'jml_izin'},
                    {data: null, render: res => {
                        return `
                            <a href="{{ url('dashboard/detail-presensi?user_id=') }}${res.id}" class="btn btn-success btn-sm btn-detail-presensi" data-id="${res.id}" style="color: #FFF;" title="detail"><i class="fa fa-eye"</i></a>`
                    }},
                ]
            })

        }

        load_tabel();

        $(document).on('click', '.btn-filter-presensi', function() {
            let filter_bulan = $('#filter_bulan').val();
            let filter_tahun = $('#filter_tahun').val();

            if((filter_bulan == null) || (filter_tahun == null)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan!',
                    text: 'Tahun dan bulan harus diisi'
                });
            }

            load_tabel({
                bulan: filter_bulan,
                tahun: filter_tahun
            });

            return false;
        })

        $(document).on('click', '.btn-refresh', function() {
            load_tabel();
        })

    })

</script>

@endsection
