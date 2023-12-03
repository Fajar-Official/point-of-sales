<!-- Modal -->
<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <form action="" method="post" class="form-horizontal" data-toggle="validator">
            @csrf
            @method('post')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal Title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="name" class="col-md-3 control-label">Nama</label>
                        <div class="col-md-9">
                            <input type="text" name="name" id="name" class="form-control" required autofocus>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="phone" class="col-md-3 control-label">Telepon</label>
                        <div class="col-md-9">
                            <input type="text" name="phone" id="phone" class="form-control" required>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="address" class="col-md-3 control-label">Alamat</label>
                        <div class="col-md-9">
                            <textarea name="address" id="address" class="form-control" rows="3" required></textarea>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
