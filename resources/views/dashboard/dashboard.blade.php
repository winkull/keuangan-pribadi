@extends('layout.sidebar')
@section('dashboard', 'active')
@section('content')
    <h2 class="mb-4 font-weight-bold">Dashboard</h2>
    <center>
        <h3>&#128151; Untuk calon istriku Siti Nur Falah &#128151;</h3>
        <p>Ketika aku mengatakan aku mencintaimu, aku tidak mengatakannya dengan santai. Aku mengatakannya untuk
            mengingatkanmu bahwa kamu adalah segalanya bagiku, dan hal terbaik yang pernah terjadi padaku dalam hidup.</p>
    </center>
    <div class="row mb-4">
        <div class="col-md-4 mb-4">
            <div class="col-md m-1 bg-primary" style="border-radius: 5px; padding: 20px">
                <center>
                    <p class="h6 text-white font-weight-bold">Saldo</p>
                    <h4 class="text-white font-weight-bold">{{ $saldo }}</h4>
                </center>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="col-md m-1 bg-info" style="border-radius: 5px; padding: 20px">
                <center>
                    <p class="h6 text-white font-weight-bold">Transaksi Hari Ini</p>
                    <h4 class="text-white font-weight-bold">{{ $transaction_today }}</h4>
                </center>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="col-md m-1 bg-success" style="border-radius: 5px; padding: 20px">
                <center>
                    <p class="h6 text-white font-weight-bold">Profit Hari Ini</p>
                    <h4 class="text-white font-weight-bold">{{ $profit_today }}</h4>
                </center>
            </div>
        </div>
        <div class="col-md-4  mb-4">
            <div class="col-md m-1 bg-secondary" style="border-radius: 5px; padding: 20px">
                <center>
                    <p class="h6 text-white font-weight-bold">Transaksi Lunas</h6>
                    <h4 class="text-white font-weight-bold">{{ $transaction_lunas }}</h4>
                </center>
            </div>
        </div>
        <div class="col-md-4  mb-4">
            <div class="col-md m-1 bg-warning" style="border-radius: 5px; padding: 20px">
                <center>
                    <h6 class="text-white font-weight-bold">Transaksi Hutang</h6>
                    <h4 class="text-white font-weight-bold">{{ $transaction_hutang }}</h4>
                </center>
            </div>
        </div>
        <div class="col-md-4  mb-4">
            <div class="col-md m-1 bg-dark" style="border-radius: 5px; padding: 20px">
                <center>
                    <h6 class="text-white font-weight-bold">Total Customer</h6>
                    <h4 class="text-white font-weight-bold">{{ $total_customer }}</h4>
                </center>
            </div>
        </div>
    </div>
    <div class="col-md m-1 bg-dark" style="border-radius: 10px; padding: 20px">
        <center>
            <h6 class="text-white font-weight-bold">Daftar Hutang</h6>
        </center>
        @foreach ($daftar_hutang as $i => $item)
            <span class="badge bg-info m-1"
                style="font-size: 1em">{{ $item['name'] . ' ( Rp. ' . number_format($item['selling_price']) . ')' }}</span>
        @endforeach
    </div>
@endsection
