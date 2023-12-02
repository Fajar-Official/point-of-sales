@extends('layouts.authenticated')
@section('header', 'Produk')

@section('css')
    <!-- Sertakan stylesheet CSS untuk DataTables dan pluginnya -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('content')
    <div id="controller" class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div>
                        <!-- Tombol untuk memicu metode addForm -->
                        <button @click="addForm('{{ route('products.store') }}')" class="btn btn-primary">
                            Tambah Produk
                        </button>
                    </div>
                </div>

                <div class="card-body table-striped table-bordered">
                    <!-- DataTable untuk menampilkan data produk -->
                    <table id="datatable" class="table table-head-fixed text-nowrap">
                        <thead>
                            <!-- Header tabel -->
                            <tr>
                                <th>No.</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                {{-- <th>Kategori</th> --}}
                                <th>Merk</th>
                                <th>Harga Beli</th>
                                <th>Harga Jual</th>
                                <th>Diskon</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Sertakan formulir produk yang bersifat partial -->
    @includeIf('pages.product.form')
@endsection

@section('js')
    <!-- Sertakan perpustakaan JavaScript untuk DataTables dan pluginnya -->
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

    <script type="text/javascript">
        // URL API untuk mengambil data produk
        const apiUrl = "{{ route('products.api') }}";

        // Konfigurasi kolom DataTable
        var columns = [{
                data: 'DT_RowIndex',
                class: 'text-center',
                orderable: true
            },
            {
                data: 'code',
                class: 'text-center',
                orderable: true
            },
            {
                data: 'name',
                class: 'text-center',
                orderable: true
            },
            {
                data: 'brand',
                class: 'text-center',
                orderable: true
            },
            {
                data: 'purchase_price',
                class: 'text-center',
                orderable: true
            },
            {
                data: 'selling_price',
                class: 'text-center',
                orderable: true
            },
            {
                data: 'discount',
                class: 'text-center',
                orderable: true
            },
            {
                data: 'stock',
                class: 'text-center',
                orderable: true
            },
            {
                // Penyusunan kustom untuk tombol aksi
                render: function(data, type, row, meta) {
                    return `
                        <a href="#" class="btn btn-info btn-sm">Detail</a>
                        <a href="#" class="btn btn-warning btn-sm" onclick="controller.editForm(${row.id})">Edit</a>
                        <a href="#" class="btn btn-danger btn-sm" onclick="controller.deleteData(${row.id})">Hapus</a>
                    `;
                },
                orderable: false,
                width: '200px',
                class: 'text-center'
            },
        ];

        // Instance Vue.js untuk mengontrol tampilan
        var controller = new Vue({
            el: '#controller',
            data: {
                data: null, // Instance DataTable
                datas: [], // Data produk
                apiUrl,
            },
            mounted: function() {
                // Inisialisasi DataTable saat komponen dimuat
                this.datatable();
            },

            methods: {
                // Metode untuk menginisialisasi atau mereinisialisasi DataTable
                datatable() {
                    const _this = this;
                    if (_this.data) {
                        _this.data.destroy(); // Hancurkan instance DataTable yang sudah ada
                    }
                    // Buat DataTable baru
                    _this.data = $('#datatable').DataTable({
                        ajax: {
                            url: _this.apiUrl,
                            type: 'GET'
                        },
                        columns: columns, // Gunakan kolom yang sudah dikonfigurasi
                    }).on('xhr', function() {
                        _this.datas = _this.data.ajax.json().data; // Perbarui data produk
                    });
                },

                // Metode untuk memuat kategori produk
                loadCategories() {
                    const _this = this;
                    const categoryDropdown = document.getElementById('category_id');

                    // Reset dropdown dan tambahkan opsi default
                    categoryDropdown.innerHTML = '<option value="">Pilih Kategori</option>';
                    axios.get(`{{ route('categories.api') }}`)
                        .then(response => {
                            const categories = response.data.data;

                            // Tambahkan setiap kategori ke dropdown
                            categories.forEach(category => {
                                const option = document.createElement('option');
                                option.value = category.id;
                                option.text = category.name;
                                categoryDropdown.add(option);
                            });
                        })
                        .catch(error => {
                            console.error('Error loading categories:', error);
                            categoryDropdown.innerHTML = '<option value="">Gagal memuat kategori</option>';
                        });
                },

                // Metode untuk membuka modal formulir tambah produk
                addForm(url) {
                    const _this = this;
                    $('#modal-form').modal('show');
                    $('#modal-form .modal-title').text('Tambah Produk');
                    $('#modal-form').on('shown.bs.modal', function() {
                        _this.loadCategories();
                    });
                    $('#modal-form form')
                        .trigger('reset')
                        .attr('action', url)
                        .validator()
                        .on('submit', function(e) {
                            e.preventDefault();
                            // Kirim permintaan POST untuk membuat produk baru
                            axios.post($(this).attr('action'), $(this).serialize())
                                .then((response) => {
                                    $('#modal-form').modal('hide');
                                    $(this).off('submit');
                                    $('#category_id').empty();
                                    _this.datatable();
                                })
                                .catch((error) => {
                                    alert("Tidak dapat menyimpan data");
                                    console.error('Error creating product:', error);
                                });
                        });
                },

                // Metode untuk membuka modal formulir edit produk
                editForm: function(id) {
                    const _this = this;
                    const updateUrl = `{{ route('products.update', ['product' => ':id']) }}`.replace(':id',
                        id);

                    // Set judul modal
                    $('#modal-form .modal-title').text('Edit Produk');

                    // Ambil data produk berdasarkan ID menggunakan Axios
                    axios.get(`{{ url('products') }}/${id}`)
                        .then((response) => {
                            const product = response.data;

                            if (product) {
                                // Muat kategori setelah data diambil
                                _this.loadCategories();

                                // Set nilai formulir berdasarkan data produk
                                $('#modal-form [name=category_id]').val(product.category_id);
                                $('#modal-form [name=code]').val(product.code);
                                $('#modal-form [name=name]').val(product.name);
                                $('#modal-form [name=brand]').val(product.brand);
                                $('#modal-form [name=purchase_price]').val(product.purchase_price);
                                $('#modal-form [name=selling_price]').val(product.selling_price);
                                $('#modal-form [name=discount]').val(product.discount);
                                $('#modal-form [name=stock]').val(product.stock);

                                // Set tindakan formulir untuk edit
                                $('#modal-form form')
                                    .attr('action', updateUrl)
                                    .validator()
                                    .on('submit', function(e) {
                                        e.preventDefault();
                                        // Kirim permintaan PUT untuk menyimpan perubahan produk
                                        axios.put(updateUrl, $(this).serialize())
                                            .then((response) => {
                                                $('#modal-form').modal('hide');
                                                $(this).off('submit');
                                                $('#category_id').empty();
                                                _this.datatable();
                                            })
                                            .catch((error) => {
                                                alert("Tidak dapat menyimpan data");
                                                console.error('Error updating product:', error);
                                            });
                                    });

                                // Tampilkan modal edit
                                $('#modal-form').modal('show');
                            } else {
                                console.error('Invalid or missing data in the response:', response);
                                alert('Gagal mengambil data Produk. Data tidak valid.');
                            }
                        })
                        .catch((error) => {
                            console.error('Error:', error);
                            alert('Gagal mengambil data Produk');
                        });
                },

                // Metode untuk menghapus produk
                deleteData(id) {
                    if (window.confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
                        const _this = this;
                        const deleteUrl = `{{ route('products.destroy', ['product' => ':id']) }}`.replace(':id',
                            id);

                        // Kirim permintaan DELETE untuk menghapus produk
                        axios.delete(deleteUrl, {
                                data: {
                                    _token: '{{ csrf_token() }}',
                                }
                            })
                            .then(response => {
                                _this.datatable(); // Perbarui DataTable setelah menghapus produk
                            })
                            .catch(error => {
                                console.error('Error deleting product:', error);
                                alert('Gagal menghapus produk');
                            });
                    }
                },
            }
        });
    </script>
@endsection
