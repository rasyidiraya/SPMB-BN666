@extends('main-kepsek')

@section('content-kepsek')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Laporan Pendaftaran</h1>

    <!-- Filter -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Laporan</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('kepsek.laporan') }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="">Semua Status</option>
                                <option value="DRAFT" {{ request('status') == 'DRAFT' ? 'selected' : '' }}>Draft</option>
                                <option value="SUBMIT" {{ request('status') == 'SUBMIT' ? 'selected' : '' }}>Submit</option>
                                <option value="ADM_ACCEPT" {{ request('status') == 'ADM_ACCEPT' ? 'selected' : '' }}>Berkas Diterima</option>
                                <option value="ADM_REJECT" {{ request('status') == 'ADM_REJECT' ? 'selected' : '' }}>Berkas Ditolak</option>
                                <option value="PAYMENT_PENDING" {{ request('status') == 'PAYMENT_PENDING' ? 'selected' : '' }}>Menunggu Verifikasi Pembayaran</option>
                                <option value="PAYMENT_ACCEPT" {{ request('status') == 'PAYMENT_ACCEPT' ? 'selected' : '' }}>Pembayaran Diterima</option>
                                <option value="PAYMENT_REJECT" {{ request('status') == 'PAYMENT_REJECT' ? 'selected' : '' }}>Pembayaran Ditolak</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Jurusan</label>
                            <select name="jurusan_id" class="form-control">
                                <option value="">Semua Jurusan</option>
                                @foreach($jurusan as $j)
                                    <option value="{{ $j->id }}" {{ request('jurusan_id') == $j->id ? 'selected' : '' }}>{{ $j->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Gelombang</label>
                            <select name="gelombang_id" class="form-control">
                                <option value="">Semua Gelombang</option>
                                @foreach($gelombang as $g)
                                    <option value="{{ $g->id }}" {{ request('gelombang_id') == $g->id ? 'selected' : '' }}>{{ $g->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Tanggal Dari</label>
                            <input type="date" name="tanggal_dari" class="form-control" value="{{ request('tanggal_dari') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Tanggal Sampai</label>
                            <input type="date" name="tanggal_sampai" class="form-control" value="{{ request('tanggal_sampai') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search"></i> Filter
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <a href="{{ route('kepsek.laporan') }}" class="btn btn-secondary btn-block">
                                <i class="fas fa-redo"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistik -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Pendaftar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistik['total'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Pembayaran Diterima</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistik['payment_accept'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Menunggu Verifikasi</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistik['submit'] + $statistik['payment_pending'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Ditolak</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistik['adm_reject'] + $statistik['payment_reject'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Pendaftar per Jurusan</h6>
                </div>
                <div class="card-body">
                    <canvas id="chartJurusan"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Pendaftar per Gelombang</h6>
                </div>
                <div class="card-body">
                    <canvas id="chartGelombang"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Data -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Data Pendaftar</h6>
            <form method="POST" action="{{ route('kepsek.laporan.export') }}" style="display: inline;">
                @csrf
                <input type="hidden" name="status" value="{{ request('status') }}">
                <input type="hidden" name="jurusan_id" value="{{ request('jurusan_id') }}">
                <input type="hidden" name="gelombang_id" value="{{ request('gelombang_id') }}">
                <input type="hidden" name="tanggal_dari" value="{{ request('tanggal_dari') }}">
                <input type="hidden" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}">
                <button type="submit" class="btn btn-success btn-sm">
                    <i class="fas fa-file-csv"></i> Export CSV
                </button>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No. Pendaftaran</th>
                            <th>Nama</th>
                            <th>Jurusan</th>
                            <th>Gelombang</th>
                            <th>Tanggal Daftar</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendaftar as $index => $p)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $p->no_pendaftaran }}</td>
                            <td>{{ $p->nama }}</td>
                            <td>{{ $p->nama_jurusan }}</td>
                            <td>{{ $p->nama_gelombang }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal_daftar)->format('d/m/Y') }}</td>
                            <td>
                                @if($p->status == 'DRAFT')
                                    <span class="badge badge-secondary">Draft</span>
                                @elseif($p->status == 'SUBMIT')
                                    <span class="badge badge-info">Submit</span>
                                @elseif($p->status == 'ADM_ACCEPT')
                                    <span class="badge badge-primary">Berkas Diterima</span>
                                @elseif($p->status == 'ADM_REJECT')
                                    <span class="badge badge-danger">Berkas Ditolak</span>
                                @elseif($p->status == 'PAYMENT_PENDING')
                                    <span class="badge badge-warning">Menunggu Verifikasi Pembayaran</span>
                                @elseif($p->status == 'PAYMENT_ACCEPT')
                                    <span class="badge badge-success">Pembayaran Diterima</span>
                                @elseif($p->status == 'PAYMENT_REJECT')
                                    <span class="badge badge-danger">Pembayaran Ditolak</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart Jurusan
const ctxJurusan = document.getElementById('chartJurusan').getContext('2d');
new Chart(ctxJurusan, {
    type: 'bar',
    data: {
        labels: {!! json_encode($perJurusan->pluck('nama')) !!},
        datasets: [{
            label: 'Jumlah Pendaftar',
            data: {!! json_encode($perJurusan->pluck('total')) !!},
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Chart Gelombang
const ctxGelombang = document.getElementById('chartGelombang').getContext('2d');
new Chart(ctxGelombang, {
    type: 'pie',
    data: {
        labels: {!! json_encode($perGelombang->pluck('nama')) !!},
        datasets: [{
            data: {!! json_encode($perGelombang->pluck('total')) !!},
            backgroundColor: [
                'rgba(255, 99, 132, 0.5)',
                'rgba(54, 162, 235, 0.5)',
                'rgba(255, 206, 86, 0.5)',
                'rgba(75, 192, 192, 0.5)',
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true
    }
});
</script>
@endsection
