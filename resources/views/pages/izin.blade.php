@extends('layouts/pages')
@section('pages_content')
<section id="contact" class="contact">
    <div class="container" data-aos="fade-up">

        <div class="section-header">
            <h2>Izin</h2>
        </div>


    <div class="row gx-lg-0 gy-4">

        <div class="col-lg-12">
            @if (count($errors) > 0)
                <div class="alert alert-danger" style="color: #000">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ url('user/simpan-izin') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-12 form-group mt-3 mt-md-0">
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                        <select class="form-control type_permit" name="type_permit" id="type_permit">
                            <option selected disabled>Pilih Jenis Izin</option>
                            <option value="terlambat">Terlambat</option>
                            <option value="tidak hadir">Tidak Hadir</option>
                        </select>
                    </div>
                    <div class="form-group mt-3">
                        <input type="file" accept="image/*" id="imgInp" placeholder="photo" name="photo">
                        <p>Ukuran maksimal 3MB</p>
                        <img id="blah" class="mt-2 mb-3" height="150" width="150" src="#" alt="preview gambar" />
                    </div>
                    <div class="form-group mt-3">
                        <textarea class="form-control" name="necessity" rows="7" placeholder="Alasan Izin"></textarea>
                    </div>
                </div>
                <br>
                <div class="text-center"><button type="submit" class="btn btn-primary btn-simpan-izin">Simpan</button></div>
            </form>
        </div><!-- End Contact Form -->

    </div>
    </div>
</section>

@endsection
@section('js')
<script>

    $('#blah').hide();
    imgInp.onchange = evt => {
		const [file] = imgInp.files
		if (file) {
            $('#blah').show();
			blah.src = URL.createObjectURL(file)
		}
	}

    $(document).ready(function() {
        $('#type_permit').select2();
    })

</script>
@endsection
