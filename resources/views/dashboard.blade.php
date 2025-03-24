@extends('layout.app')
@push('styles')
    <style>
        
    .blinking-text {
      color: green; /* Set the initial text color */
      animation: blink-animation 3s 3; /* Apply the blinking animation */
      font-size: 16px;
    }
    @keyframes blink-animation {
      0% { color: green; background-color: white; }
      50% { color: white; background-color: green; }
      100% { color: green; background-color: white; }
    }
    </style>
@endpush
@section('content')
    
<div class="row">
    <div class="col-md-4 mx-auto">
        <div class="card text-center">
            <div class="card-header d-flex justify-content-between"><h4>Registered Users</h4></div>
            <div class="card-body py-3">
                <h1>{{ $users }}</h1>
            </div>
        </div>
    </div>
    <div class="col-md-4 mx-auto">
        <div class="card text-center">
            <div class="card-header d-flex justify-content-between"><h4>Templates</h4></div>
            <div class="card-body py-3">
                <h1>{{ $templates }}</h1>
            </div>
        </div>
    </div>
    <div class="col-md-4 mx-auto">
        <div class="card text-center">
            <div class="card-header d-flex justify-content-between"><h4>Contracts</h4></div>
            <div class="card-body py-3">
                <h1>{{ $contracts->count() }}</h1>
            </div>
        </div>
    </div>
    <div class="col-md-4 mx-auto">
        <div class="card text-center">
            <div class="card-header d-flex justify-content-between"><h4>Pending Contracts</h4></div>
            <div class="card-body py-3">
                <h1><a href="{{ route('contract','pending') }}">{{ $contracts->where('status','pending')->count() }}</a></h1>
            </div>
        </div>
    </div>
    <div class="col-md-4 mx-auto">
        <div class="card text-center">
            <div class="card-header d-flex justify-content-between"><h4>Partially Completed Contracts</h4></div>
            <div class="card-body py-3">
                <h1><a href="{{ route('contract','partially filled') }}">{{ $contracts->where('status','partially filled')->count() }}</a></h1>
            </div>
        </div>
    </div>
    <div class="col-md-4 mx-auto">
        <div class="card text-center">
            <div class="card-header d-flex justify-content-between"><h4>Completed Contracts</h4></div>
            <div class="card-body py-3">
                <h1><a href="{{ route('contract','filled') }}">{{ $contracts->where('status','filled')->count() }}</a></h1>
            </div>
        </div>
    </div>
    
    <div class="col-md-12 mx-auto">
        <div class="card">
            <div class="card-header d-flex justify-content-between"><h4>Process of Creating a Form</h4></div>
            <div class="card-body py-3">
                <ol class="blinking-text">
                    <li>Admin will not sign the form</li>
                    <li>Admin will create users(if not created already) who will all sign example: Landlord (User), Rentor (User), Witness 1 (User), Witness 2 (User)</li>
                    <li>Every user will get notice that they need to sign by the email admin entered while registering them</li>
                    <li>Admin will have a summary which will show that who has signed and who hasn't</li>
                    <li>Once everyone has signed admin will check and complete the form</li>
                    <li>Before anyone signs or initials admin can change the fields in the form</li>
                    <li>Summary - can be shown at the start of form (only visible to Admin)</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection