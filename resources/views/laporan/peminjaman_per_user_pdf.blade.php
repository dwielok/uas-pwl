<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ 'Peminjaman ' . $user->name }}</title>
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
        }

        #peminjaman {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #peminjaman td,
        #peminjaman th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #peminjaman tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #peminjaman tr:hover {
            background-color: #ddd;
        }

        #peminjaman th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #04AA6D;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div style="text-align:center">
                    <h1>Peminjaman Per User</h1>
                    <h2>Nama: {{ $user->name }}</h2>
                </div>
                <hr>
                <table id="peminjaman">
                    <tbody>
                        <tr>
                            <th>#</th>
                            <th>Kode Peminjaman</th>
                            <th>Nama Buku</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Batas Kembali</th>
                        </tr>
                        @foreach ($peminjamans as $key => $peminjaman)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $peminjaman->kode }}</td>
                                <td>{{ $peminjaman->judul_buku }}</td>
                                <td>{{ date('d-m-Y', strtotime($peminjaman->tanggal_pinjam)) }}</td>
                                <td>{{ date('d-m-Y', strtotime($peminjaman->tanggal_batas_kembali)) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
