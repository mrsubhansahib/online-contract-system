@extends('layout.app')
@push('styles')
    <link rel="stylesheet" type="text/css" href="http://keith-wood.name/css/jquery.signature.css">
@endpush
@push('styles')
    {{-- @if (auth()->user()->role == 'admin')
        <style>
            button[type='submit']{
                display: none !important;
            }
        </style>
    @endif --}}
@endpush
@section('content')
    <div class="row">
        <div class="col-md-11 mx-auto position-relative">
            <div class="spinner-border text-black spinner-border-sm main-loader" role="status">
                <span class="sr-only">Loading...</span>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4>
                        View Contract
                    </h4>
                    <a href="{{ route('contract') }}" class="btn btn-primary btn-sm">All Contracts</a>
                </div>
                <div class="card-body py-3 pb-5">
                    @include('layout.alerts')
                    <div class="row">
                        <div class="col-md-6 my-4">
                            <h5>Title : {{ $contract->title }}</h5>
                        </div>
                        <div class="col-md-6 my-4 {{ auth()->user()->role != 'admin' ? 'd-none' : '' }}">
                            <h5>User (client) : {{ $contract->user->name }}</h5>
                        </div>
                        <div class="col-md-10 mx-auto pb-5">
                            <div id="fb-editor"></div>
                            <div id="fb-container"></div>
                            <div id="render-container"></div>
                            <ul id="signs-list"></ul>
                        </div>

                        <div class="col-md-12">

                            <form action="{{ route('contract.saveDetails') }}" method="POST" id="view-form"
                                enctype="multipart/form-data">
                                <div class="row">
                                    @csrf
                                    <input type="hidden" value="{{ $contract->id }}" name="id" id="contract-id">
                                    <div class="form-group col-md-12 d-none">
                                        <label for="form-data">Enter The Code Here</label>
                                        <input name="details" value="{{ $contract->form_data }}" id="form-data" />
                                    </div>
                                </div>
                                {{-- <button class="btn btn-primary" type="submit">Submit</button> --}}
                            </form>
                        </div>

                        {{-- @if ($contract->status == 'pending')
                            <div class="col-md-12 mb-4">      
                                <label>Signature:</label>
                                <br/>
                                <div id="sig"></div>
                                <br/><br/>
                                <button id="clear" type="button" class="btn btn-danger btn-sm">Clear</button>
                                <button id="save-signature" type="button" class="btn btn-primary btn-sm">Save</button>
                                <textarea id="signature" name="signed" style="display: none"></textarea>
                            </div>
                        @endif --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layout.sign-modal')
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        <script src="{{ asset('assets/bundles/form-builder/form-builder.min.js') }}"></script>
        <script src="{{ asset('assets/bundles/form-builder/form-render.min.js') }}"></script>
        <script type="text/javascript" src="http://keith-wood.name/js/jquery.signature.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
        <script src="{{ asset('assets/js/signature.js') }}"></script>

        <script>
            var sig = $('#sign-box').signature({
                syncField: '#signature',
                syncFormat: 'PNG'
            });
            $('#clear').click(function(e) {
                e.preventDefault();
                sig.signature('clear');
                $("#signature64").val('');
            });
            $("#view-form").on("submit", function(e) {
                e.preventDefault();
                $("#form-data").val(JSON.stringify(formBuilder.formData))
                console.log((formBuilder.formData));
                // Remove the event listener
                $(this).off('submit');
                // Submit the form manually
                $(this).submit()
            })
            let typeUserDisabledAttrs = {
                'signature': [
                    'placeholder',
                    'className',
                    'helpText',
                    'access',
                    'required',
                ]
            }
            <?php
            $js_array = $contract->form_data;
            echo 'var javascript_array = ' . $js_array . ";\n";
            ?>
            data = javascript_array;
            var status = "{{ $contract->status }}";
            var disabledButton = ['data', 'clear']
            status == 'pending' ? '' : disabledButton.push('save');

            if (status == 'pending') {
                var formBuilder = $("#fb-editor").formBuilder({
                    ...fBOptions,
                    allowStageSort: false,
                    disableFields: disableFieldsForView,
                    disabledFieldButtons,
                    typeUserDisabledAttrs,
                    disabledActionButtons: disabledButton,
                    onOpenFieldEdit: function(editPanel) {

                        // $(editPanel).prev().css('display','block')
                        let previousElement = $(editPanel).prev();
                        setTimeout(() => {
                            if (
                                $(previousElement).find('textarea').length > 0 ||
                                $(previousElement).find('img').attr("src") != ""
                            ) {
                                $(editPanel).empty()
                                $(previousElement).show();
                                $(previousElement).find('textarea').addClass('bg-danger')
                            }
                        }, 500);
                        return false;
                    }
                });
            } else {
                $("#fb-container").formRender({
                    formData: data,
                    fields,
                    templates
                });
            }



            setTimeout(() => {
                formBuilder ? formBuilder.actions.setData(data) : '';
                $(".main-loader").hide()
                $(".form-builder *").off("dblclick")
            }, 2000);

            // var formRenderInstance = $('#fb-container').formRender({
            //                 formData: data,
            //                 templates,
            //                 fields,typeUserDisabledAttrs
            //             });
        </script>
    @endpush
@endsection
