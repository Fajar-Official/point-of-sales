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
                                <input type="hidden" name="total" id="total" value="total">
                                <input type="hidden" name="total_item" id="total_item" value="total_item">
                                <input type="hidden" name="payment" id="payment" value="payment">

                                <div class="form-group flex">
                                    <label for="totalrp" class="font-bold text-gray-700 flex-shrink-0 w-1/3">Total</label>
                                    <input type="text" name="totalrp" id="totalrp" readonly
                                        class="w-full py-2 px-4 mb-4 border rounded border-gray-300">
                                </div>
                                <div class="form-group flex">
                                    <label for="discount"
                                        class="font-bold text-gray-700 flex-shrink-0 w-1/3">Diskon(%)</label>
                                    <input type="number" name="discount" id="discount" value="0"
                                        class="w-full py-2 px-4 mb-4 border rounded border-gray-300">
                                </div>
                                <div class="form-group flex">
                                    <label for="paymentrp" class="font-bold text-gray-700 flex-shrink-0 w-1/3">Bayar</label>
                                    <input type="text" name="paymentrp" id="paymentrp"
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

            // Validasi kuantitas
            if (quantity < 1) {
                alert('Item tidak boleh kurang dari 1');
                $(this).val(1);
                return;
            }
            if (quantity > 10000) {
                alert('Item tidak boleh melewati 10.000');
                $(this).val(10000);
                return;
            }

            // Simpan referensi this
            let $this = $(this);

            $.ajax({
                url: `/purchase_details/${id}`,
                type: 'PUT',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'quantity': quantity
                },
                success: function(response) {
                    console.log(response.subtotal);
                    // Perbarui subtotal di tabel
                    $this.closest('tr').find('.subtotal').text('Rp. ' + parseFloat(response.subtotal)
                        .toFixed(0));

                    // Perbarui total dan pembayaran
                    controller.loadForm($('#discount').val());
                },
                error: function(error) {
                    alert('Tidak dapat Update Quantity Product');
                    controller.handleAjaxError(error);
                }
            });
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
                class: 'text-center subtotal',
                orderable: true,
                render: function(data, type, row, meta) {
                    return 'Rp. ' + parseFloat(data).toFixed(0);
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
                $('#discount').on('input', () => {
                    this.loadForm($('#discount').val());
                });
            },
            methods: {
                terbilang(total) {
                    const _this = this;
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
                },
                loadForm(discount = 0) {
                    const _this = this;
                    if (discount < 0) {
                        discount = 0;
                    } else if (discount > 100) {
                        discount = 100;
                    }
                    const totalPrice = _this.calculateTotalPrice();
                    const totalItem = _this.calculateTotalItem();
                    const payment = totalPrice - (totalPrice * discount / 100);
                    const totalFormatted = 'Rp. ' + totalPrice.toFixed(0);
                    const paymentFormatted = 'Rp. ' + payment.toFixed(0);
                    const terbilangPayment = _this.terbilang(payment);
                    $('#total').val(totalPrice);
                    $('#total_item').val(totalItem);
                    $('#discount').val(discount);
                    $('#payment').val(payment);
                    $('#totalrp').val(totalFormatted);
                    $('#paymentrp').val(paymentFormatted);

                    $('.total-num').text(paymentFormatted);
                    $('.total-text').text(terbilangPayment);
                },
                // Fungsi untuk menghitung total harga belanja
                calculateTotalPrice() {
                    let totalPrice = 0;
                    $('.subtotal').each(function() {
                        let subtotalText = $(this).text().replace('Rp. ', '').replace(',', '');
                        if (!isNaN(subtotalText)) {
                            totalPrice += parseFloat(subtotalText);
                        }
                    });
                    return totalPrice;
                },
                // Fungsi untuk menghitung total item yang dibeli
                calculateTotalItem() {
                    const _this = this;
                    let totalItem = 0;
                    $('.quantity-input').each(function() {
                        totalItem += parseInt($(this).val());
                    });
                    return totalItem;
                },
                calculateTotalPrice() {
                    let totalPrice = 0;
                    $('.subtotal').each(function() {
                        let subtotalText = $(this).text().replace('Rp. ', '').replace(',', '');
                        if (!isNaN(subtotalText)) {
                            totalPrice += parseFloat(subtotalText);
                        }
                    });
                    console.log('Total Price:', totalPrice);
                    return totalPrice;
                },

                calculateTotalItem() {
                    const _this = this;
                    let totalItem = 0;
                    $('.quantity-input').each(function() {
                        totalItem += parseInt($(this).val());
                    });
                    return totalItem;
                },
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
                        console.log('Received data:', _this.datas);
                    }).on('draw.dt', function() {
                        _this.loadForm($('#discount').val());
                    });
                },
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

        });
    </script>
@endsection
