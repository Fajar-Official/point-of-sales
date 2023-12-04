@extends('layouts.authenticated')
@section('header', 'Supplier')

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
                    <div>
                        <button @click="addForm('{{ route('suppliers.store') }}')" class="btn btn-primary">
                            Tambah Supplier
                        </button>
                    </div>

                </div>

                <div class="card-body table-striped table-bordered">
                    <table id="datatable" class="table table-head-fixed text-nowrap">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama</th>
                                <th>Telepon</th>
                                <th>Alamat</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>


    @includeIf('pages.supplier.form')
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
        const apiUrl = "{{ route('suppliers.api') }}";;

        var columns = [{
                data: 'DT_RowIndex',
                class: 'text-center',
                orderable: true
            },
            {
                data: 'name',
                class: 'text-center',
                orderable: true
            },
            {
                data: 'phone',
                class: 'text-center',
                orderable: true
            },
            {
                data: 'address',
                class: 'text-center',
                orderable: true
            },
            {
                render: function(data, type, row, meta) {
                    return `
            <a href="#" class="btn btn-info btn-sm">Detail</a>
            <a href="#" class="btn btn-warning btn-sm" onclick="controller.editForm(${row.id})">Edit</a>
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
                    }).on('xhr', function() {
                        _this.datas = _this.data.ajax.json().data;
                        console.log('Received data:', _this.datas);
                    });
                },
                handleAjaxError(error) {
                    if (error.response) {
                        // The request was made and the server responded with a status code
                        console.error('Error creating supplier. Server responded with:', error.response.status,
                            error.response.data);
                    } else if (error.request) {
                        // The request was made but no response was received
                        console.error('Error creating supplier. No response received from server.');
                    } else {
                        // Something happened in setting up the request that triggered an Error
                        console.error('Error creating supplier:', error.message);
                    }

                    alert("Tidak dapat menyimpan data");
                },
                addForm(url) {
                    const _this = this;
                    $('#modal-form').modal('show');
                    $('#modal-form .modal-title').text('Tambah Supplier');

                    $('#modal-form form')
                        .trigger('reset')
                        .attr('action', url)
                        .validator()
                        .on('submit', async function(e) {
                            e.preventDefault();
                            try {
                                const response = await axios.post($(this).attr('action'), $(this)
                                    .serialize());
                                $('#modal-form').modal('hide');
                                $(this).off('submit');
                                _this.datatable();
                            } catch (error) {
                                _this.handleAjaxError(error);
                            }
                        });
                },
                editForm: function(id) {
                    const _this = this;
                    const updateUrl = `{{ route('suppliers.update', ['supplier' => ':id']) }}`.replace(':id',
                        id);
                    $('#modal-form .modal-title').text('Edit Supplier');

                    axios.get(`{{ url('suppliers') }}/${id}`)
                        .then((response) => {
                            const supplier = response.data;
                            console.log(response);
                            if (supplier) {
                                $('#modal-form [name=name]').val(supplier.name);
                                $('#modal-form [name=phone]').val(supplier.phone);
                                $('#modal-form [name=address]').val(supplier.address);

                                $('#modal-form form')
                                    .attr('action', updateUrl)
                                    .validator()
                                    .on('submit', function(e) {
                                        e.preventDefault();
                                        axios.put($(this).attr('action'), $(this).serialize())
                                            .then(() => {
                                                // Handle success
                                                $('#modal-form').modal('hide');
                                                $(this).off('submit');
                                                _this.datatable();
                                            })
                                            .catch((error) => {
                                                // Handle error
                                                _this.handleAjaxError(error);
                                            });
                                    });

                                $('#modal-form').modal('show');
                            } else {
                                console.error('Invalid or missing data in the response:', response);
                                alert('Gagal mengambil data supplier. Data tidak valid.');
                            }
                        })
                        .catch((error) => {
                            // Handle error
                            _this.handleAjaxError(error);
                        });
                },
                deleteData(id) {
                    if (window.confirm('Are you sure you want to delete this supplier?')) {
                        const _this = this;
                        const deleteUrl = `{{ route('suppliers.destroy', ['supplier' => ':id']) }}`.replace(':id',
                            id);

                        axios.delete(deleteUrl, {
                            data: {
                                _token: '{{ csrf_token() }}',
                            }
                        }).then(response => {
                            _this.datatable();
                        }).catch(error => {
                            _this.handleAjaxError(error);
                        });
                    }
                },
            }
        });
    </script>
@endsection
