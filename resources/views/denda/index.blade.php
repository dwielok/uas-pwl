@extends('layouts.app')

@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>List Denda</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Components</a></div>
                <div class="breadcrumb-item">Table</div>
            </div>
        </div>
        <div class="section-body">
            <h2 class="section-title">Denda Management</h2>

            <div class="row">
                <div class="col-12">
                    @include('layouts.alert')
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>List Denda</h4>
                            <div class="card-header-action">
                                {{-- <a class="btn btn-icon icon-left btn-primary"
                                    href="{{ route('pengembalian.create') }}">Create New
                                    Pengembalian</a> --}}
                                {{-- <a class="btn btn-info btn-primary active import">
                                    <i class="fa fa-download" aria-hidden="true"></i>
                                    Import Book</a>
                                <a class="btn btn-info btn-primary active" href="{{ route('book.export') }}">
                                    <i class="fa fa-upload" aria-hidden="true"></i>
                                    Export Book</a> --}}
                                <a class="btn btn-info btn-primary active search">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                    Search Pengembalian</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="show-import" style="display: none">
                                <div class="custom-file">
                                    <form action="{{ route('pengembalian.import') }}" method="post"
                                        enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        <label class="custom-file-label" for="file-upload">Choose File</label>
                                        <input type="file" id="file-upload" class="custom-file-input" name="import_file">
                                        <br /> <br />
                                        <div class="footer text-right">
                                            <button class="btn btn-primary">Import File</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="show-search mb-3" style="display: none">
                                <form id="search" method="GET" action="{{ route('denda.index') }}">
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="role">Kode</label>
                                            <input type="text" name="title" class="form-control" id="title"
                                                placeholder="Kode">
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <button class="btn btn-primary mr-1" type="submit">Submit</button>
                                        <a class="btn btn-secondary" href="{{ route('denda.index') }}">Reset</a>
                                    </div>
                                </form>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-md">
                                    <tbody>
                                        <tr>
                                            <th>#</th>
                                            <th>Kode</th>
                                            <th>Nama Peminjam</th>
                                            <th>Judul Buku</th>
                                            <th>Denda</th>
                                            <th>Tanggal Pinjam</th>
                                            <th>Tanggal Batas Kembali</th>
                                            <th>Tanggal Kembali</th>
                                            <th>Status</th>
                                            <th class="text-right">Action</th>
                                        </tr>
                                        @foreach ($dendas as $key => $denda)
                                            <tr>
                                                <td>{{ ($dendas->currentPage() - 1) * $dendas->perPage() + $key + 1 }}
                                                </td>
                                                <td>{{ $denda->kode }}</td>
                                                <td>{{ $denda->nama_peminjam }}</td>
                                                <td>{{ $denda->judul_buku }}</td>
                                                <td>@currency($denda->denda)</td>
                                                <td>{{ date('d-m-Y', strtotime($denda->tanggal_pinjam)) }}</td>
                                                <td>{{ date('d-m-Y', strtotime($denda->tanggal_batas_kembali)) }}</td>
                                                <td>{{ date('d-m-Y', strtotime($denda->tanggal_kembali)) }}</td>
                                                <td>{{ $denda->status == 0?'Belum Lunas':'Lunas'}}</td>
                                                <td class="text-right">
                                                    <div class="d-flex justify-content-end">
                                                        {{-- <a href="{{ route('denda.ubah_status', $denda->id) }}"
                                                            class="btn btn-sm btn-info btn-icon "><i
                                                                class="fas fa-edit"></i>
                                                            Ubah Status</a> --}}
                                                        <form action="{{ route('denda.ubah_status', $denda->id) }}"
                                                            method="POST" class="ml-2">
                                                            <input type="hidden" name="_method" value="PATCH">
                                                            <input type="hidden" name="_token"
                                                                value="{{ csrf_token() }}">
                                                            <button class="btn btn-sm btn-info btn-icon "><i
                                                                    class="fas fa-edit"></i> Ubah Status </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center">
                                    {{ $dendas->withQueryString()->links() }}
                                </div>
                            </div>
                        </div>




                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('customScript')
    <script>
        $(document).ready(function() {
            $('.import').click(function(event) {
                event.stopPropagation();
                $(".show-import").slideToggle("fast");
                $(".show-search").hide();
            });
            $('.search').click(function(event) {
                event.stopPropagation();
                $(".show-search").slideToggle("fast");
                $(".show-import").hide();
            });
            //ganti label berdasarkan nama file
            $('#file-upload').change(function() {
                var i = $(this).prev('label').clone();
                var file = $('#file-upload')[0].files[0].name;
                $(this).prev('label').text(file);
            });
        });
    </script>
@endpush

@push('customStyle')
@endpush
