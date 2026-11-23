<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GokuWallet - Pro Management</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #667eea; --secondary: #764ba2; --success: #28a745; --danger: #dc3545; --dark: #2d3436; --light: #f4f7f6; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background-color: var(--light); display: flex; min-height: 100vh; }

        /* Sidebar Styling */
        .sidebar { width: 260px; background: linear-gradient(180deg, var(--primary), var(--secondary)); color: white; display: flex; flex-direction: column; position: fixed; height: 100vh; z-index: 100; }
        .sidebar-header { padding: 30px 20px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .menu-item { padding: 15px 25px; cursor: pointer; display: flex; align-items: center; gap: 15px; transition: 0.3s; border-left: 4px solid transparent; text-decoration: none; color: white; }
        .menu-item:hover, .menu-item.active { background: rgba(255,255,255,0.15); border-left: 4px solid #fff; }

        /* Main Content */
        .main-content { margin-left: 260px; flex: 1; padding: 40px; width: calc(100% - 260px); }
        .card { background: white; padding: 25px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); margin-bottom: 25px; }
        .header-page { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }

        /* Tables */
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        
        /* Buttons */
        .btn { padding: 8px 15px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; font-size: 14px; }
        .btn-success { background: var(--success); color: white; }
        .btn-danger { background: var(--danger); color: white; }
        .btn-edit { background: #ffc107; color: #212529; }
        
        /* Modal & Stats */
        .modal { position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: none; align-items: center; justify-content: center; z-index: 1000; }
        .modal.active { display: flex; }
        .auth-card { background: white; padding: 40px; border-radius: 20px; width: 100%; max-width: 400px; box-shadow: 0 15px 35px rgba(0,0,0,0.2); }
        .form-group { margin-bottom: 15px; }
        input, select { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; }
        .hidden { display: none !important; }

        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
        .stat-box { padding: 25px; border-radius: 15px; color: white; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-header"><h2>GokuWallet</h2></div>
    <div id="m-dashboard" class="menu-item active" onclick="changePage('dashboard')"><i class="fa fa-home"></i> Dashboard</div>
    <div id="m-income" class="menu-item" onclick="changePage('income')"><i class="fa fa-arrow-up"></i> Pemasukan</div>
    <div id="m-expense" class="menu-item" onclick="changePage('expense')"><i class="fa fa-arrow-down"></i> Pengeluaran</div>
    <div id="m-report" class="menu-item" onclick="changePage('report')"><i class="fa fa-chart-pie"></i> Laporan</div>
    
    <div class="menu-item" style="margin-top:auto; border-top:1px solid rgba(255,255,255,0.1);" onclick="location.href='/logout'">
        <i class="fa fa-sign-out-alt"></i> Logout
    </div>
</div>

<div class="main-content">
    <div id="p-dashboard" class="content-page">
        <div class="header-page"><h1>Dashboard</h1><h3>Halo, {{ Auth::user()->name }}</h3></div>
        <div class="stats-grid">
            <div class="stat-box" style="background:var(--primary);"><h4>Total Saldo</h4><h2>Rp {{ number_format($saldo) }}</h2></div>
            <div class="stat-box" style="background:var(--success);"><h4>Pemasukan</h4><h2>Rp {{ number_format($totalIncome) }}</h2></div>
            <div class="stat-box" style="background:var(--danger);"><h4>Pengeluaran</h4><h2>Rp {{ number_format($totalExpense) }}</h2></div>
        </div>
    </div>

    <div id="p-income" class="content-page hidden">
        <div class="header-page"><h1>Pemasukan</h1><button class="btn btn-success" onclick="openModal('income')">+ Tambah</button></div>
        <div class="card">
            <table>
                <thead><tr><th>Tanggal</th><th>Ket</th><th>Nominal</th><th>Aksi</th></tr></thead>
                <tbody>
                    @foreach($transactions->where('type', 'income') as $t)
                    <tr>
                        <td>{{ $t->date }}</td>
                        <td>{{ $t->desc }}</td>
                        <td style="color:green; font-weight:bold;">Rp {{ number_format($t->amount) }}</td>
                        <td style="display:flex; gap:5px;">
                            <button class="btn btn-edit" onclick='openEditModal(@json($t))'><i class="fa fa-edit"></i></button>
                            <form action="/delete/{{ $t->id }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="p-expense" class="content-page hidden">
        <div class="header-page"><h1>Pengeluaran</h1><button class="btn btn-danger" onclick="openModal('expense')">+ Tambah</button></div>
        <div class="card">
            <table>
                <thead><tr><th>Tanggal</th><th>Ket</th><th>Nominal</th><th>Aksi</th></tr></thead>
                <tbody>
                    @foreach($transactions->where('type', 'expense') as $t)
                    <tr>
                        <td>{{ $t->date }}</td>
                        <td>{{ $t->desc }}</td>
                        <td style="color:red; font-weight:bold;">Rp {{ number_format($t->amount) }}</td>
                        <td style="display:flex; gap:5px;">
                            <button class="btn btn-edit" onclick='openEditModal(@json($t))'><i class="fa fa-edit"></i></button>
                            <form action="/delete/{{ $t->id }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="p-report" class="content-page hidden">
        <div class="header-page"><h1>Laporan & Analisis</h1></div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="card">
                <h3>Grafik Perbandingan</h3>
                <canvas id="myChart" style="max-height: 250px;"></canvas>
            </div>
            <div class="card">
                <h3>Download Laporan</h3><br>
                <button class="btn btn-success" onclick="exportToExcel()" style="width:100%; justify-content:center;">
                    <i class="fa fa-file-excel"></i> Export ke Excel
                </button>
            </div>
        </div>
        <div class="card">
            <h3>Riwayat Semua Transaksi</h3>
            <table>
                <thead><tr><th>Tanggal</th><th>Tipe</th><th>Ket</th><th>Nominal</th></tr></thead>
                <tbody>
                    @foreach($transactions as $t)
                    <tr>
                        <td>{{ $t->date }}</td>
                        <td><span style="color:{{ $t->type == 'income' ? 'green' : 'red' }}">{{ strtoupper($t->type) }}</span></td>
                        <td>{{ $t->desc }}</td>
                        <td>Rp {{ number_format($t->amount) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="formModal" class="modal">
    <div class="auth-card">
        <form action="/save" method="POST">
            @csrf
            <h3 id="modalTitle">Tambah Data</h3><br>
            <input type="hidden" name="id" id="fId">
            <input type="hidden" name="type" id="fType">
            <div class="form-group"><label>Nominal</label><input type="number" name="amount" id="fAmount" required></div>
            <div class="form-group"><label>Keterangan</label><input type="text" name="desc" id="fDesc" required></div>
            <div class="form-group"><label>Tanggal</label><input type="date" name="date" id="fDate" required value="{{ date('Y-m-d') }}"></div>
            <div style="display:flex; gap:10px;">
                <button type="submit" class="btn btn-success" style="flex:1; justify-content:center;">Simpan</button>
                <button type="button" class="btn btn-danger" onclick="closeModal()" style="flex:1; justify-content:center;">Batal</button>
            </div>
        </form>
    </div>
</div>

<script>
    let myChart = null;

    // Fungsi utama perpindahan halaman
    function changePage(pageId) {
        // Sembunyikan semua halaman
        document.querySelectorAll('.content-page').forEach(p => p.classList.add('hidden'));
        // Tampilkan halaman target
        document.getElementById('p-' + pageId).classList.remove('hidden');
        
        // Atur menu aktif di sidebar
        document.querySelectorAll('.menu-item').forEach(m => m.classList.remove('active'));
        const activeMenu = document.getElementById('m-' + pageId);
        if(activeMenu) activeMenu.classList.add('active');

        // Jika halaman laporan, render grafik
        if(pageId === 'report') initChart();
    }

    // LOGIKA PERBAIKAN: Berjalan saat halaman dimuat ulang (setelah submit form)
    window.onload = function() {
        // Cek apakah ada session 'active_page' dikirim dari Laravel Controller
        // Jika tidak ada, default ke dashboard
        const lastActionType = "@if(session('active_page')){{ session('active_page') }}@endif";
        
        if(lastActionType) {
            changePage(lastActionType);
        }
    };

    function openModal(type) {
        document.getElementById('formModal').classList.add('active');
        document.getElementById('fId').value = '';
        document.getElementById('fType').value = type;
        document.getElementById('fAmount').value = '';
        document.getElementById('fDesc').value = '';
        document.getElementById('modalTitle').innerText = 'Tambah ' + (type == 'income' ? 'Pemasukan' : 'Pengeluaran');
    }

    function openEditModal(data) {
        document.getElementById('formModal').classList.add('active');
        document.getElementById('fId').value = data.id;
        document.getElementById('fType').value = data.type;
        document.getElementById('fAmount').value = data.amount;
        document.getElementById('fDesc').value = data.desc;
        document.getElementById('fDate').value = data.date;
        document.getElementById('modalTitle').innerText = 'Edit Data';
    }

    function closeModal() { document.getElementById('formModal').classList.remove('active'); }

    function initChart() {
        const ctx = document.getElementById('myChart').getContext('2d');
        if (myChart) myChart.destroy();
        myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Pemasukan', 'Pengeluaran'],
                datasets: [{
                    data: [{{ $totalIncome }}, {{ $totalExpense }}],
                    backgroundColor: ['#28a745', '#dc3545']
                }]
            }
        });
    }

    function exportToExcel() {
        let table = document.querySelector("#p-report table");
        let ws = XLSX.utils.table_to_sheet(table);
        const wscols = [ { wch: 15 }, { wch: 10 }, { wch: 30 }, { wch: 20 } ];
        ws['!cols'] = wscols;
        let wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Laporan Keuangan");
        let today = new Date().toISOString().slice(0, 10);
        XLSX.writeFile(wb, `Laporan_GokuWallet_${today}.xlsx`);
    }
</script>
</body>
</html>