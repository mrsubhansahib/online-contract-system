@extends('layout.app')
@push('styles')
    <link rel="stylesheet" type="text/css" href="http://keith-wood.name/css/jquery.signature.css">
@endpush
@section('content')
    <div class="row">
        <!-- Modal -->
        <div class="col-md-11 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4>
                        New Contracts
                        {{-- <a href="#signature-modal" class="add-sign" data-value="${fieldData.name}" data-toggle="modal">Add Signature</a> --}}
                    </h4>
                    <a href="{{ route('contract.add') }}" class="btn btn-primary btn-sm">Add New</a>
                </div>
                <div class="card-body py-3 pb-5">
                    @include('layout.alerts')

                    <div class="col-md-10 mx-auto">
                        <div class="form-group col-md-12">
                            <label for="template">Template</label>
                            <select name="template" id="template" class="form-control">
                                <option value="" disabled selected>Select From the Following</option>
                                @foreach ($templates as $template)
                                    <option value="{{ $template->id }}">{{ $template->title }}</option>
                                @endforeach
                            </select>
                            @error('role')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        @error('details')
                            <span class="text-danger fw-bold">{{ $message }}</span>
                        @enderror
                        <div id="fb-editor"></div>
                        <div id="fb-container"></div>
                        <ul id="signs-list"></ul>
                    </div>

                    <form action="{{ route('contract.store') }}" method="POST">
                        @csrf
                        <div class="form-group col-md-12">
                            <label for="title">Contract Title <small class="text-danger">*</small></label>
                            <input type="text" id="title" name="title" placeholder="Enter Contract Title"
                                value="{{ old('title') }}" class="form-control">
                            @error('title')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-12">
                            <label for="user">User <small class="text-danger">*</small></label>
                            <select name="user" id="user" class="form-control">
                                <option value="" disabled selected>Select From the Following</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('role')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-12 d-none">
                            <label for="form-data">Enter The Code Here</label>
                            <input name="details" id="form-data" />
                        </div>
                        <div class="col-md-12">
                            <button class="btn btn-primary btn-block">Save</button>
                        </div>
                </div>
            </div>
            </form>
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
            var formBuilder = $("#fb-editor").formBuilder(fBOptions);
            $(document).ready(function() {
                // Digital Signature Config
                setTimeout(() => {
                    var sig = $('#sign-box').signature({
                        syncField: '#signature',
                        syncFormat: 'PNG'
                    });
                    $('#clear').click(function(e) {
                        e.preventDefault();
                        sig.signature('clear');
                        $("#signature64").val('');
                    });
                }, 2000);

                setTimeout(() => {
                    $(".form-actions").append(`
                    <button class='btn btn-primary copy-button' type='button' onclick='copyData()'>
                        Save Form 
                        <div class="spinner-grow text-primary spinner-grow-sm c-loader" style='display:none;' role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </button>
                `)
                }, 500);
                $("#template").on("change", function() {
                    let template = $(this).val()
                    $.ajax({
                        type: "GET",
                        url: "/templates/" + template,
                        success: function(res) {

                            formBuilder.actions.setData(res);

                        }
                    })
                })
            })

            function copyData() {
                $(".copy-button").prop('disabled', (i, v) => !v).find(".c-loader").show()
                let formData = formBuilder.actions.getData('json', true);
                $("#form-data").val(formData)
                setTimeout(() => {
                    $(".copy-button").prop('disabled', (i, v) => !v).find(".c-loader").hide()
                }, 500);
            }

            function copyImgName(selectedSign) {
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val(selectedSign).select();
                document.execCommand("copy");
                $temp.remove();
            }
        </script>
    @endpush
@endsection
