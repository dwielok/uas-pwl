@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Table</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Components</a></div>
                <div class="breadcrumb-item">Table</div>
            </div>
        </div>
        <div class="section-body">
            <h2 class="section-title">Tambah Peminjaman</h2>

            <div class="card">
                <div class="card-header">
                    <h4>Validasi Tambah Data</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('book-user.pinjam_action') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>User</label>
                            <input type="hidden" class="form-control" id="id_user" name="id_user"
                                value="{{ $user->id }}" readonly>
                            <input type="text" class="form-control" value="{{ $user->name }}" readonly>
                        </div>
                        <div class="form-group">
                            <label>Buku</label>
                            <input type="hidden" class="form-control" id="id_buku" name="id_buku"
                                value="{{ $book->id }}" readonly>
                            <input type="text" class="form-control" value="{{ $book->title }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_pinjam">Tanggal Pinjam</label>
                            <input type="date" class="form-control @error('tanggal_pinjam') is-invalid @enderror"
                                id="tanggal_pinjam" name="tanggal_pinjam" style="width:50%">
                            @error('tanggal_pinjam')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group" id="tgl_back">
                            <label for="tanggal_batas_kembali_text">Anda harus mengembalikan pada tanggal </label>
                            <label id="tanggal_batas_kembali_text"></label>
                            <input type="hidden" class="form-control @error('tanggal_batas_kembali') is-invalid @enderror"
                                id="tanggal_batas_kembali" name="tanggal_batas_kembali" style="width:50%">
                        </div>
                </div>
                <div class="card-footer text-right">
                    <button class="btn btn-primary">Submit</button>
                    <a class="btn btn-secondary" href="{{ route('book.index') }}">Cancel</a>
                </div>
                </form>
            </div>
        </div>
    </section>
@endsection
@push('customScript')
    <script src="/assets/js/select2.min.js"></script>
    <script>
        Date.prototype.addDays = function(days) {
            var date = new Date(this.valueOf());
            date.setDate(date.getDate() + days);
            return date;
        }

        function formatDate(date, format = 1) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2)
                month = '0' + month;
            if (day.length < 2)
                day = '0' + day;
            if (format == 1) {

                return [year, month, day].join('-');
            } else {
                return [day, month, year].join('-');
            }
        }
        $(document).ready(function() {
            $('#tgl_back').hide();
            $(document).on("change", "#tanggal_pinjam", function() {
                let date_start = $(this).val();
                const firstDate = new Date(date_start);
                // console.log(firstDate.addDays(7));
                // console.log(formatDate(firstDate.addDays(7)));
                $("#tgl_back").show();
                $('#tanggal_batas_kembali_text').text(formatDate(firstDate.addDays(7), 2));
                $('#tanggal_batas_kembali').val(formatDate(firstDate.addDays(7)));
            });
        });
    </script>
@endpush

@push('customStyle')
    <link rel="stylesheet" href="/assets/css/select2.min.css">
@endpush
