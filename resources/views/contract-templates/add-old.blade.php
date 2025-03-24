@extends('layout.app')
@section('content')
    
<div class="row">
    <div class="col-md-11 mx-auto">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4>
                    New Template
                </h4>
            </div>
            <div class="card-body py-3 pb-5">
                @include('layout.alerts')

                <div class="col-md-10 mx-auto pt-5">
                    @error('details')
                        <span class="text-danger fw-bold">{{ $message }}</span>
                    @enderror
                    <div id="fb-editor"></div>
                    <div id="fb-container"></div>
                </div>

                <form action="{{ route('template.store') }}" method="POST">
                    @csrf
                    <div class="form-group col-md-12">
                        <label for="title">Template Title <small class="text-danger">*</small></label>
                        <input type="text" id="title" name="title" placeholder="Enter Template Title" value="{{ old('title') }}" class="form-control" required>
                        @error('title')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-12 d-none">
                        <label for="form-data">Enter The Code Here</label>
                        <input name="details" id="form-data"/>
                    </div>
                    <div class="col-md-12">
                        <button class="btn btn-primary btn-block">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="{{ asset('assets/bundles/form-builder/form-builder.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/form-builder/form-render.min.js') }}"></script>
    
    <script>


        var formBuilder = $("#fb-editor").formBuilder(fBOptions);
        $(document).ready(function(){
            // Digital Signature Config
            
            setTimeout(() => {
                $(".form-actions button").remove()
                $(".form-actions").append(`
                    <button class='btn btn-primary copy-button' type='button' onclick='copyData()'>
                        Save Form 
                        <div class="spinner-grow text-primary spinner-grow-sm c-loader" style='display:none;' role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </button>
                `)
            }, 500);
        })
        function copyData(){
            $(".copy-button").prop('disabled', (i, v) => !v).find(".c-loader").show()
            let formData = formBuilder.actions.getData('json', true);
            console.log(formData);
            $("#form-data").val(formData)
            setTimeout(() => {
                $(".copy-button").prop('disabled', (i, v) => !v).find(".c-loader").hide()            
            }, 500);
        }
        
    </script>
@endpush
@endsection