<x-dashboard-layout>
    <h1 class="mt-4">Data Supplier</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Data Supplier</li>
    </ol>
    <div class="row mb-5">
        <div class="col">
            <a class="btn btn-primary mb-3" href="{{ route('suppliers.create') }}">Tambah Supplier</a>

            <div class="card">
                <div class="card-body">
                    @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                    @endif

                    <table id="datatablesSimple" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Supplier</th>
                                <th>Nama</th>
                                <th>No Telp</th>
                                <th>Nama Produk</th>
                                <th>Merek Produk</th>
                                <th>Jumlah</th>
                                <th>Harga Beli</th> <!-- New column -->
                                <th>Harga Jual</th>
                                <th>Expired</th>
                                <th>Bukti Pembayaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($suppliers as $supplier)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $supplier->kode_supplier }}</td>
                                <td>{{ $supplier->name }}</td>
                                <td>{{ $supplier->phone }}</td>
                                <td>{{ $supplier->product_name }}</td>
                                <td>{{ $supplier->product_brand }}</td>
                                <td>{{ $supplier->quantity }}</td>
                                <td>{{ $supplier->harga_beli }}</td> <!-- New column -->
                                <td>{{ $supplier->price }}</td>
                                <td>{{ $supplier->expired }}</td>
                                <td>
                                    @if ($supplier->payment_proof)
                                    <a href="{{ asset('storage/photos/' . $supplier->payment_proof) }}" target="_blank">Lihat Bukti Pembayaran</a>
                                    @else
                                    Tidak ada bukti pembayaran
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        {{-- @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus supplier ini?')">Hapus</button> --}}
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>
</x-dashboard-layout>
