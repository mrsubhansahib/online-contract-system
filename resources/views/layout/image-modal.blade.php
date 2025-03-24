<div class="modal fade" id="image-modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="alert alert-success alert-dismissible fade show" role="alert" style="display: none;"
                        id="image-alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">Close</span>
                        </button>
                        <div class="message">Image Added Successfully Thank You !!!</div>
                    </div>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="display: none;"
                        id="image-error">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">Close</span>
                        </button>
                        <div class="message">Please Select an Image</div>
                    </div>
                </div>
                <input type="hidden" id="field-image-id" class="new-class">
                <div class="row">
                    <div class="col-md-8 mx-auto">
                        <label>Preview:</label>
                        <img src="" alt="" class="image-preview w-100 rounded img-thumbnail">
                        <input type="file" accept=".jpg, .jpeg, .png, .gif, .bmp" id="uploading-image" class="form-control mt-3">
                    </div>
                    <div class="col-12">
                        <button id="save-image" type="button" class="btn btn-primary my-2 btn-block">Save</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
