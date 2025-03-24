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
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4>
                        Edit User
                    </h4>
                    <a href="{{ route('user') }}" class="btn btn-primary btn-sm">All Users</a>
                </div>
                <div class="card-body py-3">

                    @include('layout.alerts')

                    <form action="{{ route('user.update') }}" method="POST" id="editForm" onsubmit="disableSubmitButton()">
                        @csrf
                        <input type="hidden" name="id" value="{{ $user->id }}">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="name">Name <small class="text-danger">*</small></label>
                                <input type="text" class="form-control" value="{{ $user->name }}" name="name"
                                    id="name" placeholder="Enter User Name" required>
                                @error('name')
                                    {{ $message }}
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="contact">Contact <small class="text-danger">*</small></label>
                                <input type="text" class="form-control" value="{{ $user->contact }}" name="contact"
                                    id="contact" placeholder="Enter User Contact" required>
                                @error('contact')
                                    {{ $message }}
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="email">Email <small class="text-danger">*</small></label>
                                <input type="text" class="form-control" value="{{ $user->email }}" name="email"
                                    id="email" placeholder="Enter User Email" required>
                                @error('email')
                                    {{ $message }}
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="password">Password <small class="text-danger">*</small></label>
                                <input type="password" class="form-control" name="password" id="password"
                                    placeholder="Enter User New Password">
                                @error('password')
                                    {{ $message }}
                                @enderror
                            </div>
                            <div class="form-group col-md-12">
                                <label for="role">Role {{ $user->role }} <small class="text-danger">*</small></label>
                                <select name="role" id="role" class="form-control" required>
                                    <option {{ $user->role == 'admin' ? 'selected' : '' }}>admin</option>
                                    <option {{ $user->role == 'user' ? 'selected' : '' }} value="user">user (client)
                                    </option>
                                </select>
                                @error('role')
                                    {{ $message }}
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <button class="btn btn-primary btn-block" id="submitButton">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
