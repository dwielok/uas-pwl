@extends('layouts.app')

@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Book List</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Components</a></div>
                <div class="breadcrumb-item">Table</div>
            </div>
        </div>
        <div class="section-body">
            <h2 class="section-title">Book Management</h2>

            <div class="row">
                <div class="col-12">
                    @include('layouts.alert')
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>Book List</h4>
                            <div class="card-header-action">
                                {{-- <a class="btn btn-icon icon-left btn-primary" href="{{ route('book.create') }}">Create New
                                    Book</a> --}}
                                {{-- <a class="btn btn-info btn-primary active import">
                                    <i class="fa fa-download" aria-hidden="true"></i>
                                    Import Book</a>
                                <a class="btn btn-info btn-primary active" href="{{ route('book.export') }}">
                                    <i class="fa fa-upload" aria-hidden="true"></i>
                                    Export Book</a> --}}
                                <a class="btn btn-info btn-primary active search">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                    Search Book</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="show-import" style="display: none">
                                <div class="custom-file">
                                    <form action="{{ route('book.import') }}" method="post"
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
                                <form id="search" method="GET" action="{{ route('book.index') }}">
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="role">Book</label>
                                            <input type="text" name="title" class="form-control" id="title"
                                                placeholder="Book Name">
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <button class="btn btn-primary mr-1" type="submit">Submit</button>
                                        <a class="btn btn-secondary" href="{{ route('book.index') }}">Reset</a>
                                    </div>
                                </form>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-md">
                                    <tbody>
                                        <tr>
                                            <th>#</th>
                                            <th>ISBN</th>
                                            <th>Title</th>
                                            <th>Author</th>
                                            <th>Publication Date</th>
                                            <th>User</th>
                                            <th>Gambar</th>
                                            <th>Status</th>
                                            <th class="text-right">Action</th>
                                        </tr>
                                        @foreach ($books as $key => $book)
                                            <tr>
                                                <td>{{ ($books->currentPage() - 1) * $books->perPage() + $key + 1 }}</td>
                                                <td>{{ $book->isbn }}</td>
                                                <td>{{ $book->title }}</td>
                                                <td>{{ $book->author }}</td>
                                                <td>{{ date('d-m-Y', strtotime($book->publication_date)) }}</td>
                                                <td>{{ $book->name }}</td>
                                                <td><a href="{{ url($book->image) }}" target="_blank"
                                                        rel="noopener noreferrer">
                                                        <img src="{{ url($book->image) }}" alt="asd"
                                                            style="height: 50px;width:40px"></a></td>
                                                <td>{{ $book->status == 1 ? 'Tersedia' : 'Dipinjam' }}</td>
                                                <td class="text-right">
                                                    <div class="d-flex justify-content-end">
                                                        @if ($book->status == 1)
                                                            <a href="{{ route('book-user.pinjam', $book->id) }}"
                                                                class="btn btn-sm btn-info btn-icon "><i
                                                                    class="fas fa-hand-holding"></i>
                                                                Pinjam</a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center">
                                    {{ $books->withQueryString()->links() }}
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
