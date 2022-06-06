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
            <h2 class="section-title">Edit Peminjaman</h2>

            <div class="card">
                <div class="card-header">
                    <h4>Validasi Edit Data</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('peminjaman.update', $peminjaman) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        @method('put')

                        <div class="form-group">
                            <label>Peminjam</label>
                            <select class="form-control select2" name="id_user">
                                <option value="">Choose User</option>
                                @foreach ($users as $item)
                                    <option value="{{ $item->id }}"
                                        {{ $peminjaman->id_user == $item->id ? 'selected' : '' }}>{{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Buku</label>
                            <select class="form-control select2" name="id_buku">
                                <option value="">Choose Book</option>
                                @foreach ($books as $item)
                                    <option value="{{ $item->id }}"
                                        {{ $peminjaman->id_buku == $item->id ? 'selected' : '' }}>{{ $item->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_pinjam">Tanggal Pinjam</label>
                            <input type="date" class="form-control @error('tanggal_pinjam') is-invalid @enderror"
                                id="tanggal_pinjam" name="tanggal_pinjam"
                                value="{{ date('Y-m-d', strtotime($peminjaman->tanggal_pinjam)) }}">
                            @error('tanggal_pinjam')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="tanggal_batas_kembali">Tanggal Batas Kembali</label>
                            <input type="date" class="form-control @error('tanggal_batas_kembali') is-invalid @enderror"
                                id="tanggal_batas_kembali" name="tanggal_batas_kembali"
                                value="{{ date('Y-m-d', strtotime($peminjaman->tanggal_batas_kembali)) }}">
                            @error('tanggal_batas_kembali')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
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

        function formatDate(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2)
                month = '0' + month;
            if (day.length < 2)
                day = '0' + day;

            return [year, month, day].join('-');
        }
        $(document).ready(function() {
            $(document).on("change", "#tanggal_pinjam", function() {
                let date_start = $(this).val();
                const firstDate = new Date(date_start);
                // console.log(firstDate.addDays(7));
                // console.log(formatDate(firstDate.addDays(7)));
                $("#tanggal_batas_kembali").val(formatDate(firstDate.addDays(7)));
            });
        });
    </script>
@endpush

@push('customStyle')
    <link rel="stylesheet" href="/assets/css/select2.min.css">
@endpush
