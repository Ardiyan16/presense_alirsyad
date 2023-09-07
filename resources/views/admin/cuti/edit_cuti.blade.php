@extends('layouts.admin')
@section('content')

<div class="content">
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-title">Edit Cuti Pegawai</h4>
            <a href="{{ url('dashboard/data-cuti') }}" class="btn btn-success btn-modal-tambah"><i class="fa fa-arrow-circle-left"></i> Kembali</a>
        </div>
    </div>
    <div class="row" style="margin-top: 20px">
        <div class="col-lg-12">
            <div class="card-box">
                <h4 class="card-title">Form Edit Cuti Pegawai</h4>
                <form action="#">
                    <div class="form-group row">
                        <label class="col-form-label col-md-2">Nama Pegawai</label>
                        <div class="col-md-10">
                            <select class="form-control select-user-id" id="user_id">
                            </select>
                            <span class="text-danger user_id_err"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2">Tanggal Awal</label>
                        <div class="col-md-10">
                            <input type="date" id="start_date" class="form-control">
                            <span class="text-danger start_date_err"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2">Tanggal Akhir</label>
                        <div class="col-md-10">
                            <input type="date" id="end_date" class="form-control">
                            <span class="text-danger end_date_err"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2">Jenis Cuti</label>
                        <div class="col-md-10">
                            <select class="form-control select-reason-id" id="reason_id">
                            </select>
                            <span class="text-danger reason_id_err"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2">Keterangan</label>
                        <div class="col-md-10">
                            <input type="text" id="information" class="form-control">
                            <span class="text-danger information_err"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2"></label>
                        <div class="col-md-10">
                            <button class="btn btn-success btn-update-cuti-pegawai" type="button"><i class="fa fa-save"></i> Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
@section('js')

<script>

    $(document).ready(function() {
        var id_cuti = {{ $id_cuti }};

        $.ajax({
            url: '{{ url("/api/v1/data-cuti-edit") }}/' + id_cuti,
            headers: {
                'Authorization': 'Bearer '+get_cookie('ALD_SESSION')
            },
            type: 'GET',
            dataType: 'JSON',
            success: function(res) {

                if(res.status) {
                    let user_id = res.data.user_id;
                    let start_date = res.data.start_date;
                    let end_date = res.data.end_date;
                    let reason_id = res.data.reason_id;
                    let information = res.data.information;

                    $('#start_date').val(start_date);
                    $('#end_date').val(end_date);
                    $('#information').val(information);

                    get_select_pegawai(user_id);
                    get_select_reason(reason_id);

                }

            }
        })

        function get_select_pegawai(id_user) {
            $.ajax({
                url: '{{ url("/api/v1/data-pegawai-select") }}',
                headers: {
                    'Authorization': 'Bearer '+get_cookie('ALD_SESSION')
                },
                type: 'GET',
                dataType: 'JSON',
                success: function(res) {

                    if(res.status) {

                        let option = '<option value="" disable selected>Pilih Pegawai</option>';
                        $.each(res.data, function(index, val) {

                            if(val.id == id_user) {
                                option += '<option value="'+ val.id +'" title="'+ val.full_name +'" selected>'+ val.full_name +'</option>';
                            } else {
                                option += '<option value="'+ val.id +'" title="'+ val.full_name +'">'+ val.full_name +'</option>';
                            }

                        });

                        $('select.select-user-id').html(option).select2();

                    }

                }
            })
        }

        function get_select_reason(id_reason) {
            $.ajax({
                url: '{{ url("/api/v1/data-jenis-cuti-select") }}',
                headers: {
                    'Authorization': 'Bearer '+get_cookie('ALD_SESSION')
                },
                type: 'GET',
                dataType: 'JSON',
                success: function(res) {

                    if(res.status) {

                        let option = '<option value="" disable selected>Pilih Jenis Cuti</option>';
                        $.each(res.data, function(index, val) {

                            if(val.id == id_reason) {
                                option += '<option value="'+ val.id +'" title="'+ val.reason +'" selected>'+ val.reason +'</option>';
                            } else {
                                option += '<option value="'+ val.id +'" title="'+ val.reason +'">'+ val.reason +'</option>';
                            }

                        });

                        $('select.select-reason-id').html(option).select2();

                    }

                }
            })
        }

        $(document).on('click', '.btn-update-cuti-pegawai', function() {
            let user_id = $('#user_id').val();
            let start_date = $('#start_date').val();
            let end_date = $('#end_date').val();
            let reason_id = $('#reason_id').val();
            let information = $('#information').val();

            $.ajax({
                url: '{{ url("/api/v1/update-cuti-pegawai") }}',
                headers: {
                    'Authorization': 'Bearer '+get_cookie('ALD_SESSION')
                },
                type: 'POST',
                dataType: 'JSON',
                data: {
                    "id": id_cuti,
                    "user_id": user_id,
                    "start_date": start_date,
                    "end_date": end_date,
                    "reason_id": reason_id,
                    'information': information,
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
                            window.location.href = "{{ url('/dashboard/data-cuti') }}";
                        });

                    } else {

                        if(res.info_error) {
                            tampikan_error(res.message);
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

        })

        function tampikan_error(message){
            $.each(message, function(key,val) {
                $('.'+key+'_err').text(val);
            })
        }

    })

</script>

@endsection
