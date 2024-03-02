@extends('layouts.authenticated')
@section('header', 'Transaksi Pembelian')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('content')
    <div id="controller" class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <table>
                        <tr>
                            <td>ID Pembelian</td>
                            <td> : {{ $supplier->id }}</td>
                        </tr>
                        <tr>
                            <td>Supplier</td>
                            <td> : {{ $supplier->name }}</td>
                        </tr>
                        <tr>
                            <td>Telepon</td>
                            <td> : {{ $supplier->phone }}</td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td> : {{ $supplier->address }}</td>
                        </tr>
                    </table>
                </div>

                <div class="card-body table-striped table-bordered">
                    <form class="form-product">
                        @csrf
                        <div class="form-group">
                            <label for="product_code">Kode Produk </label>
                            <div class="input-group">
                                <input type="hidden" class="w-full py-2 px-4 mb-4 border rounded border-gray-300"
                                    name="purchase_id" id="purchase_id" value="{{ $purchase_id }}">
                                <input type="hidden" class="w-full py-2 px-4 mb-4 border rounded border-gray-300"
                                    name="product_id" id="product_id">
                                <input type="text" class="w-full py-2 px-4 mb-4 border rounded border-gray-300"
                                    name="product_code" id="product_code">
                                <span class="input-group-btn btn btn-info btn-flat">
                                    <button @click="showProduct()" type="button" class="">
                                        <i class="fa fa-arrow-right"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </form>
                    <table id="datatable" class="table table-head-fixed text-nowrap">
                        <thead>
                            <th>No.</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                            <th width="15%"><i class="fa fa-cog" /></th>
                        </thead>
                    </table>

                    <div class="flex flex-col lg:flex-row">
                        <!-- Bagian Kiri -->
                        <div class="lg:w-4/6 lg:mr-4 ">
                            <div class="total-num text-5xl text-center h-32 bg-primary flex justify-center items-center">
                            </div>
                            <div class="total-text p-4 text-left bg-gray-200"></div>
                        </div>
                        <!-- End Bagian Kiri -->

                        <!-- Bagian Kanan -->
                        <div class="lg:w-2/6 flex flex-col">
                            <form action="" class="form-purchase" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $purchase_id }}">
                                <input type="hidden" name="total" value="total">
                                <input type="hidden" name="total_item" value="total_item">
                                <input type="hidden" name="purchase" value="purchase">

                                <div class="form-group flex">
                                    <label for="totalrp" class="font-bold text-gray-700 flex-shrink-0 w-1/3">Total</label>
                                    <input type="text" name="totalrp" id="totalrp" readonly
                                        class="w-full py-2 px-4 mb-4 border rounded border-gray-300">
                                </div>
                                <div class="form-group flex">
                                    <label for="discount"
                                        class="font-bold text-gray-700 flex-shrink-0 w-1/3">Diskon(%)</label>
                                    <input type="number" name="discount" id="discount"
                                        class="w-full py-2 px-4 mb-4 border rounded border-gray-300">
                                </div>
                                <div class="form-group flex">
                                    <label for="payment" class="font-bold text-gray-700 flex-shrink-0 w-1/3">Bayar</label>
                                    <input type="number" name="payment" id="payment"
                                        class="w-full py-2 px-4 mb-4 border rounded border-gray-300">
                                </div>
                                <div class="box-footer btn btn-primary btn-sm  pull-right text-center">
                                    <button type="submit" class="btn-simpan">
                                        <i class="fa fa-floppy-o"></i> Simpan Transaksi
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- End Bagian Kanan -->
                    </div>
                </div>
            </div>
        </div>
        @includeIf('pages.purchase_detail.product')
    </div>
@endsection

@section('js')
    <!-- DataTables  & Plugins -->
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
        const purchaseId = document.getElementById('purchase_id').value;
        const apiUrl = "{{ route('purchase_details.api', ['id' => ':id']) }}".replace(':id', purchaseId);

        $(document).on('input', '.updatequantity', function() {
            let id = $(this).data('id');
            let quantity = $(this).val();
            if (quantity < 1) {
                alert('Item tidak boleh kurang dari 1')
                $(this).val(1);
                return;
            }
            if (quantity > 10000) {
                alert('Item tidak boleh melewati 10.000')
                $(this).val(10000);
                return;
            }

            $.post(`{{ url('/purchase_details') }}/${id}`, {
                    '_token': '{{ csrf_token() }}',
                    '_method': 'PUT',
                    'quantity': quantity
                })
                .done(response => {
                    controller.calculateTotal();
                    controller.logUpdate();
                    // Memperbarui total tanpa refresh halaman
                    $.get(apiUrl, function(data) {
                        controller.datas = data.data;
                        controller.calculateTotal();
                    });
                })
                .fail(error => {
                    alert('Tidak dapat Update Quantity Product');
                    controller.handleAjaxError(error);
                    return
                })
        });

        var columns = [{
                data: 'DT_RowIndex',
                class: 'text-center',
                orderable: true
            },
            {
                data: 'product.code',
                class: 'text-center',
                orderable: true
            },
            {
                data: 'product.name',
                class: 'text-center',
                orderable: true
            },
            {
                data: 'product.purchase_price',
                class: 'text-center',
                orderable: true,
                render: function(data, type, row, meta) {
                    return 'Rp. ' + data;
                }
            },
            {
                data: 'quantity',
                class: 'text-center',
                orderable: true,
                render: function(data, type, row, meta) {
                    return '<input type="number" class="updatequantity w-full py-2 px-4 mb-4 border rounded border-gray-300 input-sm quantity-input" data-id="' +
                        row.id + '" value="' + data + '">';
                }
            },
            {
                data: 'subtotal',
                class: 'text-center',
                orderable: true,
                render: function(data, type, row, meta) {
                    return 'Rp. ' + data;
                }
            },
            {
                render: function(data, type, row, meta) {
                    return `
            <a href="#" class="btn btn-danger btn-sm" onclick="controller.deleteData(${row.id})">Delete</a>
        `;
                },
                orderable: false,
                width: '200px',
                class: 'text-center'

            },
        ];

        var controller = new Vue({
            el: '#controller',
            data: {
                data: null,
                datas: [],
                apiUrl,
            },
            mounted: function() {
                this.datatable();
            },
            methods: {
                datatable() {
                    const _this = this;
                    if (_this.data) {
                        _this.data.destroy();
                    }
                    _this.data = $('#datatable').DataTable({
                        ajax: {
                            url: _this.apiUrl,
                            type: 'GET'
                        },
                        columns: columns,
                        dom: 'brt',
                        bSort: false,
                    }).on('xhr', function() {
                        _this.datas = _this.data.ajax.json().data;
                        _this.calculateTotal();
                        console.log('Received data:', _this.datas);
                    });
                },
                handleAjaxError(error) {
                    if (error.response) {
                        console.error('Error creating purchase. Server responded with:', error.response
                            .status,
                            error.response.data);
                    } else if (error.request) {
                        console.error('Error creating purchase. No response received from server.');
                    } else {
                        console.error('Error creating purchase:', error.message);
                    }

                    alert("Tidak dapat menyimpan data");
                },
                showProduct() {
                    const _this = this;
                    $('#modal-product').modal('show');
                },
                hideProduct() {
                    const _this = this;
                    $('#modal-product').modal('hide');
                },
                selectProduct(id, code) {
                    console.log('select product klik');
                    const _this = this;
                    $('#product_id').val(id);
                    $('#product_code').val(code);
                    this.hideProduct();
                    this.addProduct();
                },
                addProduct() {
                    const _this = this;
                    axios.post("{{ route('purchase_details.store') }}", $('.form-product').serialize())
                        .then(response => {
                            _this.datatable();
                            $('#product_code').val('');
                            $('.form-product')[0].reset();
                            $('#product_code').focus();
                            alert('Data Product berhasil disimpan.');
                        })
                        .catch(error => {
                            _this.handleAjaxError(error);
                            alert('Tidak dapat Menyimpan Data Product. Silakan coba lagi.');
                        });
                },
                calculateTotal() {
                    const _this = this;
                    let total = 0;
                    this.datas.forEach(item => {
                        total += item.quantity * item.product.purchase_price;
                    });
                    document.querySelector('.total-num').innerText = 'Rp. ' + total;

                    console.log('total-num now: ' + total);
                    terbilang = this.terbilang(total) + ' RUPIAH';
                    document.querySelector('.total-text').innerText = terbilang.toUpperCase();

                    // Menampilkan total di elemen dengan id 'totalrp'
                    document.getElementById('totalrp').value = total;
                },
                deleteData(id) {
                    const index = this.datas.findIndex(item => item.id === id);
                    this.datas.splice(index, 1);
                    this.calculateTotal();
                    console.log('Data dengan ID', id, 'telah dihapus');
                },
                logUpdate() {
                    return console.log('Update Log');
                },

                terbilang(total) {
                    const bilangan = [
                        '', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan',
                        'sepuluh',
                        'sebelas', 'dua belas', 'tiga belas', 'empat belas', 'lima belas', 'enam belas',
                        'tujuh belas',
                        'delapan belas', 'sembilan belas'
                    ];

                    if (total < 20) {
                        return bilangan[total];
                    } else if (total < 100) {
                        return bilangan[Math.floor(total / 10)] + ' puluh ' + bilangan[total % 10];
                    } else if (total < 200) {
                        return 'seratus ' + this.terbilang(total - 100);
                    } else if (total < 1000) {
                        return bilangan[Math.floor(total / 100)] + ' ratus ' + this.terbilang(total % 100);
                    } else if (total < 2000) {
                        return 'seribu ' + this.terbilang(total - 1000);
                    } else if (total < 1000000) {
                        return this.terbilang(Math.floor(total / 1000)) + ' ribu ' + this.terbilang(total % 1000);
                    } else if (total < 1000000000) {
                        return this.terbilang(Math.floor(total / 1000000)) + ' juta ' + this.terbilang(total %
                            1000000);
                    } else if (total < 1000000000000) {
                        return this.terbilang(Math.floor(total / 1000000000)) + ' milyar ' + this.terbilang(total %
                            1000000000);
                    } else if (total < 1000000000000000) {
                        return this.terbilang(Math.floor(total / 1000000000000)) + ' trilyun ' + this.terbilang(
                            total % 1000000000000);
                    } else if (total < 1000000000000000000) {
                        return this.terbilang(Math.floor(total / 1000000000000000)) + ' kuadriliun ' + this
                            .terbilang(total % 1000000000000000);
                    } else {
                        return 'Data terlalu besar';
                    }
                }
            }
        });
        $(document).on('input', '#discount', function() {
            // Panggil metode calculatePayment saat nilai diskon berubah
            calculatePayment();
        });

        // Metode untuk menghitung pembayaran berdasarkan total belanja dan diskon
        function calculatePayment() {
            // Mengambil nilai total belanja dari input totalrp
            let total = parseFloat($('#totalrp').val());
            // Mengambil nilai diskon dari input diskon
            let discountPercentage = parseFloat($('#discount').val());
            // Menghitung nilai diskon berdasarkan persentase
            let discount = (discountPercentage / 100) * total;
            // Menghitung pembayaran setelah diskon
            let payment = total - discount;
            // Menampilkan nilai pembayaran pada input payment
            $('#payment').val(payment);
        }
    </script>
@endsection
