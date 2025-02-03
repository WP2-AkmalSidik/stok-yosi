<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pergerakan Stok Kain</title>
    <style>
        /* Reset beberapa gaya default */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Gaya untuk keseluruhan body */
        body {
            font-family: 'Roboto', sans-serif;
            margin: 40px;
            background-color: #ffffff;
        }

        h1 {
            text-align: center;
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        /* Gaya untuk tabel */
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
        }

        table th,
        table td {
            padding: 12px 15px;
            text-align: left;
            border: 1px solid #e0e0e0;
        }

        table th {
            background-color: #5c6bc0;
            color: #ffffff;
            font-weight: 600;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        table td {
            color: #555;
        }

        table td:first-child {
            font-weight: bold;
        }

        /* Styling untuk header dan footer */
        .header,
        .footer {
            text-align: center;
            font-size: 12px;
            color: #aaa;
        }

        .footer {
            margin-top: 20px;
        }

        /* Styling untuk halaman cetak */
        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            h1 {
                font-size: 28px;
            }

            table {
                border: 1px solid #ccc;
            }

            .footer {
                position: absolute;
                bottom: 20px;
                width: 100%;
            }

            .header {
                position: absolute;
                top: 20px;
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>Riwayat Pergerakan Stok Kain</h1>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama Kain</th>
                <th>Jumlah</th>
                <th>Jenis Transaksi</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $transaksi)
                <tr>
                    <td>{{ $transaksi->kain->nama_kain }}</td>
                    <td>
                        {{-- Cek jika jumlah bulat, tampilkan tanpa desimal --}}
                        {{ number_format($transaksi->jumlah, 0, '', '') }} Yard
                    </td>
                    <td>{{ $transaksi->jenis_transaksi }}</td>
                    <td>{{ $transaksi->created_at->format('Y-m-d') }}</td>
                    <td>{{ $transaksi->keterangan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Terimakasih telah menggunakan sistem kami.</p>
    </div>

</body>

</html>
