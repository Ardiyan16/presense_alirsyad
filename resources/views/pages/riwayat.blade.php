@extends('layouts/pages')
@section('pages_content')

<style>
    .card-box {
        background-color: #FFF;
        border-radius: 4px;
        margin-bottom: 30px;
        padding: 20px;
        position: relative;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.1);
    }

    .card-title {
        margin-bottom: 1.125rem;
        color: #333;
        font-size: 16px;
    }

    table {
        border: 1px solid #028000
    }
</style>

<section id="services" class="services sections-bg">
    <div class="container" data-aos="fade-up">

        <div class="section-header">
            <h2>Riwayat Absensi</h2>
        </div>

        <div class="row gy-4" data-aos="fade-up" data-aos-delay="100">

            <div class="col-lg-4 col-md-6">
              <div class="service-item  position-relative">
                <div class="icon">
                  <i class="fa-solid fa-right-to-bracket"></i>
                </div>
                <h3>Absen Masuk <span class="jumlah-absen-masuk"></span></h3>
              </div>
            </div><!-- End Service Item -->

            <div class="col-lg-4 col-md-6">
              <div class="service-item position-relative">
                <div class="icon">
                  <i class="fa-solid fa-right-from-bracket"></i>
                </div>
                <h3>Absen Keluar <span class="jumlah-absen-keluar"></span></h3>
              </div>
            </div><!-- End Service Item -->

            <div class="col-lg-4 col-md-6">
              <div class="service-item position-relative">
                <div class="icon">
                  <i class="fa-solid fa-list-check"></i>
                </div>
                <h3>Izin <span class="jumlah-izin"></span></h3>
              </div>
            </div><!-- End Service Item -->
          </div>

            <div class="row" style="margin-top: 50px">
                <div class="col-sm-12">
                    <div class="card-box">
                        <div class="card-block">
                            <h6 class="card-title text-bold">Riwayat Absen Anda</h6>
                            <div class="table-responsive">
                                <table class="datatable table table-stripped" id="data_pegawai">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Tipe Absen</th>
                                            <th>Waktu</th>
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
</section><!-- End Contact Section -->

@endsection
@section('js')

<script>

    $(document).ready(function() {
        var user_id = {{ auth()->user()->id }};

        $.ajax({
            url: '{{ url("/api/v1/dashboard-riwayat") }}/' + user_id,
            headers: {
                'Authorization': 'Bearer '+get_cookie('ALD_SESSION')
            },
            type: 'GET',
            dataType: "JSON",

            success: function(res) {
                let jumlah_absen_masuk = res.jumlah_absen_masuk;
                let jumlah_absen_pulang = res.jumlah_absen_pulang;
                let jumlah_izin = res.jumlah_izin;
                $('.jumlah-absen-masuk').html(`(${jumlah_absen_masuk})`);
                $('.jumlah-absen-keluar').html(`(${jumlah_absen_pulang})`);
                $('.jumlah-izin').html(`(${jumlah_izin})`);

            }
        })

        var tabel = $('#data_pegawai').DataTable({
                ajax: {
                    url: '{{ url('/api/v1/riwayat-absen') }}',
                    headers: {
                        'Authorization': 'Bearer '+get_cookie('ALD_SESSION')
                    },
                    data: {
                        "user_id": user_id
                    },
                },
                serverSide: true,
                processing: true,
                destroy: true,
                aaSorting:[[0,"desc"]],
                columns: [
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1 + '.';
                        }
                    },
                    {data: 'date_presence', name: 'date_presence'},
                    {
                        data:null, render: res => {
                            let tipe = '';
                            if(res.presence_type == 1) {
                                tipe += `<span class="badge bg-success" style="color: #FFF">Masuk</span>`;
                            } else {
                                tipe += `<span class="badge bg-primary" style="color: #FFF">Pulang</span>`
                            }

                            return tipe;
                        }
                    },
                    {data: "time", name: "time"},
                    {

                        data:null, render: res => {
                            let presence_status = '';
                            if(res.presence_status == 'in_office') {
                                presence_status += `<span class="badge bg-success" style="color: #FFF">Di Kantor</span>`;
                            } else {
                                presence_status += `<span class="badge bg-primary" style="color: #FFF">Di Rumah</span>`
                            }

                            return presence_status;
                        }
                    },
                ]
            })

    })

</script>

@endsection
