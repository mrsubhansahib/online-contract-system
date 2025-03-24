@extends('layout.app')
@push('styles')
    <link rel="stylesheet" type="text/css" href="http://keith-wood.name/css/jquery.signature.css">
@endpush
@push('scripts')
    <script>
        function disableSubmitButton() {
            var submitButton = document.getElementById('submitButton');
            submitButton.disabled = true;
            submitButton.style.opacity = '0.5';
            submitButton.style.cursor = 'not-allowed';
        }
        window.addEventListener('DOMContentLoaded', (event) => {
            // Flag to check if form is dirty
            let formDirty = false;

            // Listen for changes in form fields
            const formFields = document.querySelectorAll('input, textarea, select');
            formFields.forEach(field => {
                field.addEventListener('input', () => {
                    formDirty = true;
                });
            });

            // Listen for form submission
            const form = document.getElementById('editForm');
            form.addEventListener('submit', () => {
                formDirty = false;
            });

            // Confirm navigation if form is dirty
            window.addEventListener('beforeunload', (event) => {
                if (formDirty) {
                    event.preventDefault();
                    event.returnValue = ''; // Some browsers require a return value
                    return ''; // For modern browsers
                }
            });
        });
    </script>
@endpush
@section('content')
    <div class="row">
        <!-- Modal -->
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4>
                        Edit Category
                    </h4>
                    <a href="{{ route('category') }}" class="btn btn-primary btn-sm">All Categories</a>
                </div>
                <div class="card-body py-3 pb-5">
                    @include('layout.alerts')

                    <form action="{{ route('category.update') }}" method="POST" id="editForm"
                        onsubmit="disableSubmitButton()">
                        @csrf
                        <input type="hidden" value="{{ $category->id }}" name="id">
                        <div class="form-group col-md-12">
                            <label for="name">Category Name <small class="text-danger">*</small></label>
                            <input type="text" id="name" name="name" placeholder="Enter Category Name"
                                value="{{ $category->name }}" class="form-control" required>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <button class="btn btn-primary btn-block" id="submitButton">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
