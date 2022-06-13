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
            <h2 class="section-title">Edit Pengembalian</h2>

            <div class="card">
                <div class="card-header">
                    <h4>Validasi Edit Data</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('pengembalian.update', $pengembalian) }}" method="post">
                    @csrf
                    @method('put')
                        <div class="form-group">
                            <label>Kode Peminjaman</label>
                            <input type="text" class="form-control" value="{{$peminjamans->kode}}" readonly>
                        </div>
                        {{-- <div class="form-group">
                            <label>Buku</label>
                            <select class="form-control select2" name="id_buku">
                                <option value="">Choose Book</option>
                                @foreach ($books as $item)
                                    <option value="{{ $item->id }}">{{ $item->title }}</option>
                                @endforeach
                            </select>
                        </div> --}}
                        <div class="form-group">
                            <label for="tanggal_kembali">Tanggal Kembali</label>
                            <input type="date" class="form-control @error('tanggal_kembali') is-invalid @enderror"
                                id="tanggal_kembali" name="tanggal_kembali" value="{{ date('Y-m-d', strtotime($pengembalian->tanggal_kembali)) }}">
                            @error('tanggal_kembali')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <input type="hidden" name="status" id="status" value="{{$pengembalian->status}}">
                        <input type="hidden" name="total_hari" id="total_hari">
                        <input type="hidden" name="id_peminjaman" id="id_peminjaman" value="{{$peminjamans->id}}">
                        <input type="hidden" name="tanggal_batas_kembali" id="tanggal_batas_kembali" value="{{$peminjamans->tanggal_batas_kembali}}">
                        {{-- <div class="form-group">
                            <label for="tanggal_batas_kembali">Tanggal Batas Kembali</label>
                            <input type="date" class="form-control @error('tanggal_batas_kembali') is-invalid @enderror"
                                id="tanggal_batas_kembali" name="tanggal_batas_kembali">
                            @error('tanggal_batas_kembali')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div> --}}
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
        $(function() {
            let date_end = $("#tanggal_kembali").val();
                let date_start_a = $("#tanggal_batas_kembali").val();
                let date_start = date_start_a;
                const oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
                const firstDate = new Date(date_start);
                const secondDate = new Date(date_end);

                let diffDays = Math.round((secondDate - firstDate) / oneDay);

                let actual = diffDays < 0 ? 0 : diffDays;

                var status = actual > 0 ? 0 : 1
                var total_hari = actual
                $("#status").val(status);
                $("#total_hari").val(total_hari);
            $(document).on("change", "#tanggal_kembali", function() {
                let date_end = $(this).val();
                let date_start_a = $("#tanggal_batas_kembali").val();
                let date_start = date_start_a;
                const oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
                const firstDate = new Date(date_start);
                const secondDate = new Date(date_end);

                let diffDays = Math.round((secondDate - firstDate) / oneDay);

                let actual = diffDays < 0 ? 0 : diffDays;

                var status = actual > 0 ? 0 : 1
                var total_hari = actual
                $("#status").val(status);
                $("#total_hari").val(total_hari);
            });
        });
    </script>
@endpush

@push('customStyle')
    <link rel="stylesheet" href="/assets/css/select2.min.css">
@endpush
