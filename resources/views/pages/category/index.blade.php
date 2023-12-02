@extends('layouts.authenticated')
@section('header', 'Category')

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
                        <button @click="addForm('{{ route('categories.store') }}')" class="btn btn-primary">
                            Tambah Kategori
                        </button>
                    </div>

                </div>

                <div class="card-body table-striped table-bordered">
                    <table id="datatable" class="table table-head-fixed text-nowrap">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>


    @includeIf('pages.category.form')
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
        const apiUrl = "{{ route('categories.api') }}";;

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
                    });
                },

                addForm(url) {
                    const _this = this;
                    $('#modal-form').modal('show');
                    $('#modal-form .modal-title').text('Tambah Kategori');

                    $('#modal-form form')
                        .trigger('reset')
                        .attr('action', url)
                        .validator()
                        .on('submit', function(e) {
                            e.preventDefault();
                            axios.post($(this).attr('action'), $(this).serialize())
                                .then((response) => {
                                    $('#modal-form').modal('hide');
                                    $(this).off('submit');
                                    _this.datatable();
                                })
                                .catch((error) => {
                                    alert("tidak dapat menyimpan data");
                                    console.error('Error creating category:', error);
                                });
                        });
                },

                editForm: function(id) {
                    const _this = this;
                    const updateUrl = `{{ route('categories.update', ['category' => ':id']) }}`.replace(':id',
                        id);

                    // Set judul modal
                    $('#modal-form .modal-title').text('Edit Kategori');

                    // Ambil data kategori berdasarkan ID menggunakan Axios
                    axios.get(`{{ url('categories') }}/${id}`)
                        .then((response) => {
                            const category = response.data;

                            if (category) {
                                // Isi nilai-nilai form dengan data yang diambil
                                $('#modal-form [name=name]').val(category.name);

                                // Set action form untuk edit
                                $('#modal-form form')
                                    .attr('action', updateUrl)
                                    .validator()
                                    .on('submit', function(e) {
                                        if (!e.preventDefault()) {
                                            $.ajax({
                                                    url: $(this).attr('action'),
                                                    type: 'put',
                                                    data: $(this).serialize(),
                                                })
                                                .done((response) => {
                                                    $('#modal-form').modal('hide');
                                                    _this.datatable();
                                                })
                                                .fail((errors) => {
                                                    alert("Tidak dapat menyimpan data");
                                                    return;
                                                });
                                        }
                                    });

                                // Tampilkan modal edit
                                $('#modal-form').modal('show');
                            } else {
                                console.error('Invalid or missing data in the response:', response);
                                alert('Gagal mengambil data kategori. Data tidak valid.');
                            }
                        })
                        .catch((error) => {
                            console.error('Error:', error);
                            alert('Gagal mengambil data kategori');
                        });
                },
                deleteData(id) {
                    if (window.confirm('Are you sure you want to delete this category?')) {
                        const _this = this;
                        const deleteUrl = `{{ route('categories.destroy', ['category' => ':id']) }}`.replace(':id',
                            id);

                        axios.delete(deleteUrl, {
                                data: {
                                    _token: '{{ csrf_token() }}',
                                }
                            })
                            .then(response => {
                                _this.datatable();
                            })
                            .catch(error => {
                                console.error('Error deleting category:', error);
                                alert('Failed to delete category');
                            });
                    }
                },
            }
        });
    </script>
@endsection
