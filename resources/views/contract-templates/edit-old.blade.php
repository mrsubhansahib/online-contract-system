@extends('layout.app')
@push('styles')
<style>
    .main-loader{
        position: absolute;
        top: 20%;
        left: 30%;
        z-index: 1000;
        width: 200px;
        height: 200px;
        border-width: 20px
    }
</style>
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
                    Edit Template
                </h4>
                <a href="{{ route('template') }}" class="btn btn-primary btn-sm">All Templates</a>
            </div>
            <div class="card-body py-3 pb-5">
                @include('layout.alerts')

                <div class="col-md-10 mx-auto pb-5">
                    <div id="fb-editor" class="pt-5"></div>
                </div>
                <form action="{{ route('template.update') }}" method="POST">
                    @csrf
                    <input type="hidden" value="{{ $template->id }}" name="id" id="template-id">
                        <div class="form-group col-md-12">
                            <label for="title">Template Title <small class="text-danger">*</small></label>
                            <input type="text" id="title" name="title" placeholder="Enter Template Title" value="{{ $template->title }}" class="form-control">
                            @error('title')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-12 d-none">
                            <label for="form-data">Enter The Code Here</label>
                            <input name="details" value="{{ ($template->form_data) }}" id="form-data"/>
                        </div>
                        <div class="col-md-12">
                            <button class="btn btn-primary btn-block">Update</button>
                        </div>
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
        var fields = [{
            label: 'Signature',
            attrs: {
            type: 'signature'
            },
            icon: '🌟'
        }];
        var templates = {
            signature: function(fieldData) {
                return {
                    field: '<p id="' + fieldData.name + '">',
                    onRender: function() {
                        let value = this.config.value
                        $(document.getElementById(fieldData.name)).html();
                    }
                };
            }
        };
        var formBuilder = $("#fb-editor").formBuilder({
           scrollToFieldOnAdd: false,
           templates,
           fields,
        }); 
        $(document).ready(function(){
            
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
    <script type='text/javascript'>
        <?php
            $js_array = $template->form_data;
            echo "var javascript_array = ". $js_array . ";\n";
        ?>
        let data = javascript_array;
        setTimeout(() => {
            formBuilder.actions.setData(data);
            $(".main-loader").hide()
        }, 2000);
    </script>

@endpush
@endsection