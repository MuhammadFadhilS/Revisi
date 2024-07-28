<x-dashboard-layout>
    <h1 class="mt-4">Tambah Produk</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Tambah Produk</li>
    </ol>
    <div class="row mb-5">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="col-12 mb-3">
                            <label for="name" class="form-label">Name Produk</label>
                            <select name="name" id="name" class="form-control @error('name') is-invalid @enderror">
                                <option selected disabled>-- Pilih Produk --</option>
                                @foreach ($availableSuppliers as $supplier)
                                    <option value="{{ $supplier->product_name }}" @selected(old('name'))>
                                        {{ $supplier->product_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label for="kode_barang" class="form-label">Kode Barang</label>
                            <input type="text" class="form-control @error('kode_barang') is-invalid @enderror"
                                placeholder="Kode Barang" name="kode_barang" id="kode_barang" value="{{ old('kode_barang') }}">
                            @error('kode_barang')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label for="category_id" class="form-label">Kategori</label>
                            <select name="category_id" id="category_id"
                                class="form-control @error('category_id') is-invalid @enderror">
                                <option selected disabled>-- Pilih Kategori --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id'))>
                                        {{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label for="brand" class="form-label">Name Brand</label>
                            <input type="text" name="brand" id="brand"
                                class="form-control @error('brand') is-invalid @enderror" readonly>
                            @error('brand')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label for="harga_awal" class="form-label">Harga Awal</label>
                            <input type="number" class="form-control @error('harga_awal') is-invalid @enderror"
                                placeholder="Harga Awal" name="harga_awal" id="harga_awal" value="{{ old('harga_awal') }}">
                            @error('harga_awal')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label for="price" class="form-label">Harga Jual</label>
                            <input type="number" name="price" id="price"
                                class="form-control @error('price') is-invalid @enderror" readonly>
                            @error('price')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label for="expired" class="form-label">Expired</label>
                            <input type="date" name="expired" id="expired"
                                class="form-control @error('expired') is-invalid @enderror" readonly>
                            @error('expired')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label for="stock" class="form-label">Stock</label>
                            <input type="number" name="stock" id="stock"
                                class="form-control @error('stock') is-invalid @enderror" readonly>
                            @error('stock')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label for="description" class="form-label">Keterangan</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                                placeholder="Keterangan">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label for="photo" class="form-label">Gambar</label>
                            <input type="file" class="form-control @error('photo') is-invalid @enderror"
                                placeholder="Gambar" name="photo" id="photo" onchange="previewFile(this)">
                            @error('photo')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-6 mb-3 d-none" id="frame">
                            <img src="" class="img-fluid" width="200" id="previewImage">
                        </div>

                        <div class="col-12 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option selected disabled>-- Pilih Status --</option>
                                <option value="1" @selected(old('status'))>Tampilkan</option>
                                <option value="0" @selected(old('status'))>Tidak Tampilkan</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nameSelect = document.getElementById('name');
            const brandInput = document.getElementById('brand');
            const priceInput = document.getElementById('price');
            const stockInput = document.getElementById('stock');
            const expiredInput = document.getElementById('expired');

            nameSelect.addEventListener('change', function() {
                const productName = this.value;

                if (productName) {
                    fetch(`/get-supplier-product/${productName}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data) {
                                brandInput.value = data.product_brand;
                                priceInput.value = data.price;
                                stockInput.value = data.stock;
                                expiredInput.value = data.expired;
                            } else {
                                console.error('Data not found');
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }
            });
        });

        function previewFile(input) {
            const file = input.files[0];
            const preview = document.getElementById('previewImage');
            const frame = document.getElementById('frame');

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                frame.classList.remove('d-none');
            };
            if (file) {
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-dashboard-layout>
