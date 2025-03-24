<div class="modal fade" id="signature-modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Note Your Signature</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="alert alert-success alert-dismissible fade show" role="alert" style="display: none;"
                        id="signature-alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">Close</span>
                        </button>
                        <div class="message">Signature Added Successfully Thank You !!!</div>
                    </div>
                </div>
            <input type="hidden" id="field-id" class="new-class">

                <div class="col-md-12 mb-4">
                    <label>Signature:</label>
                    <br />
                    <div id="sign-box"></div>
                    <br /><br />
                    <button id="clear" type="button" class="btn btn-danger btn-sm">Clear</button>
                    <button id="save-signature2" type="button" class="btn btn-primary btn-sm">Save</button>
                    <textarea id="signature" name="signed" style="display: none"></textarea>
                </div>
            </div>
        </div>
    </div>
</div>
