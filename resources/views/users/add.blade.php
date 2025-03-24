@extends('layout.app')
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
            const form = document.getElementById('addForm');
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
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4>
                        New Users
                    </h4>
                    <a href="{{ route('user') }}" class="btn btn-primary btn-sm">All Users</a>
                </div>
                <div class="card-body py-3 pb-5">
                    @include('layout.alerts')

                    <form action="{{ route('user.store') }}" method="POST" id="addForm" onsubmit="disableSubmitButton()">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="name">Name <small class="text-danger">*</small></label>
                                <input type="text" required class="form-control" value="{{ old('name') }}"
                                    name="name" id="name" placeholder="Enter User Name">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="contact">Contact <small class="text-danger">*</small></label>
                                <input type="text" required class="form-control" value="{{ old('contact') }}"
                                    name="contact" id="contact" placeholder="Enter User Contact">
                                @error('contact')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="email">Email <small class="text-danger">*</small></label>
                                <input type="text" required class="form-control" value="{{ old('email') }}"
                                    name="email" id="email" placeholder="Enter User Email">
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="password">Password <small class="text-danger">*</small></label>
                                <input type="password" required class="form-control" value="{{ old('password') }}"
                                    name="password" id="password" placeholder="Enter User Password">
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-12">
                                <label for="role">Role <small class="text-danger">*</small></label>
                                <select name="role" id="role" required class="form-control">
                                    <option value="" disabled selected>Select From the Following</option>
                                    <option>admin</option>
                                    <option value="user">user (client)</option>
                                </select>
                                @error('role')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-primary btn-block get-data" id="submitButton">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
