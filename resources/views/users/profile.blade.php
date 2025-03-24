@extends('layout.app')

@section('content')
    
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4>
                    User Profile
                </h4>
            </div>
            <div class="card-body py-3">

                @include('layout.alerts')

                <form action="{{ route('user.update_profile') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="name">Name <small class="text-danger">*</small></label>
                            <input type="text" class="form-control" value="{{ $user->name }}" name="name" id="name" placeholder="Enter User Name">
                            @error('name')
                                {{ $message }}
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="password">Password <small class="text-danger">*</small></label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="Enter User New Password">
                            @error('password')
                                {{ $message }}
                            @enderror
                        </div>
                        
                        <div class="col-md-12">
                            <button class="btn btn-primary btn-block">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection