<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventaris Kain</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <header class="text-center mb-12">
            <div class="flex justify-center items-center space-x-4">
                <i class="fas fa-warehouse text-5xl text-blue-600"></i>
                <h1 class="text-5xl font-extrabold text-blue-600">Inventaris Kain</h1>
            </div>
            <p class="mt-4 text-gray-600 max-w-2xl mx-auto">
                Manajemen stok kain dengan pelacakan real-time dan analitik canggih.
            </p>
        </header>

        <div class="grid md:grid-cols-3 gap-6">
            @foreach($fabrics as $fabric)
                @php
                    $availableStock = $fabric->stok * $fabric->panjang_per_roll - $fabric->transaksis()->where('jenis_transaksi', 'keluar')->sum('jumlah');
                    $isOutOfStock = $availableStock <= 0;
                @endphp
                <div
                    class="bg-white shadow-lg rounded-lg overflow-hidden p-6 transform hover:scale-105 transition {{ $isOutOfStock ? 'bg-red-100 border border-grey-500' : '' }}">
                    <h2 class="text-2xl font-bold text-blue-800">{{ $fabric->nama_kain }}</h2>
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <div>
                            <p class="text-gray-500">Total Gulungan</p>
                            <p class="text-xl font-semibold">{{ $fabric->stok }} gulungan</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Panjang per Gulungan</p>
                            <p class="text-xl font-semibold">{{ number_format($fabric->panjang_per_roll, 2) }} yard</p>
                        </div>
                    </div>
                    <div
                        class="p-3 mt-4 {{ $isOutOfStock ? 'bg-red-50 border-l-4 border-red-500' : 'bg-green-50 border-l-4 border-green-500' }}">
                        <p class="font-bold {{ $isOutOfStock ? 'text-red-700' : 'text-green-700' }}">
                            {{ $isOutOfStock ? 'Stok Habis' : 'Stok Tersedia: ' . $availableStock . ' yard' }}
                        </p>
                    </div>
                    <p class="text-sm text-gray-600 mt-4">Terakhir Diperbarui:
                        {{ $fabric->updated_at->format('d M Y H:i') }}
                    </p>
                </div>
            @endforeach
        </div>
        <section class="mt-12 bg-white shadow-xl rounded-lg p-8">
            <h2 class="text-3xl font-bold text-center mb-8 text-gray-800">Wawasan Inventaris</h2>
            <div class="grid md:grid-cols-2 gap-8">
                <div class="w-full">
                    <canvas id="stockChart" class="w-full"></canvas>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-4">Statistik Cepat</h3>
                    <div class="space-y-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <p class="text-blue-800 font-bold">Total Jenis Kain: {{ $fabrics->count() }}</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <p class="text-green-800 font-bold">Total Stok Tersedia: {{ $totalAvailableStock }} yard</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script>
        const ctx = document.getElementById('stockChart').getContext('2d');
        const fabricChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($fabrics->pluck('nama_kain')),
                datasets: [{
                    label: 'Stok Tersedia (Yard)',
                    data: @json($fabrics->map(function ($fabric) {
    return $fabric->stok * $fabric->panjang_per_roll -
        $fabric->transaksis()->where('jenis_transaksi', 'keluar')->sum('jumlah');
})),
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    hoverBackgroundColor: 'rgba(54, 162, 235, 0.8)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Yard'
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return `Stok: ${context.formattedValue} yard`;
                            }
                        }
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeOutQuad'
                }
            }
        });

        document.querySelectorAll('.fabric-card').forEach(card => {
            card.addEventListener('click', function () {
                const fabricId = this.dataset.fabricId;
            });
        });
    </script>
</body>

</html>
