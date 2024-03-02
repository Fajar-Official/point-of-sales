<div class="modal fade" id="modal-product" tabindex="-1" role="dialog" aria-labelledby="modal-product" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Harga Beli</th>
                            <th><i class="fa fa-cog"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($product as $key => $item)
                            <tr>
                                <td width="5%">{{ $key + 1 }}</td>
                                <td><span class="label label-success">{{ $item->code }}</span></td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->purchase_price }}</td>
                                <td>
                                    <a class="btn btn-primary btn-xs btn-flat"
                                        @click="selectProduct('{{ $item->id }}','{{ $item->code }}')">
                                        <i class="fa fa-check-circle"></i> Pilih
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
