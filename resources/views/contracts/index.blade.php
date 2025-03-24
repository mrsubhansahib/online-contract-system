@extends('layout.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/bundles/datatables/datatables.min.css') }}">
    <style>
        body {
            overflow-x: hidden;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4>
                        All Contracts
                    </h4>
                    <a href="{{ route('contract.add') }}" class="btn btn-primary btn-sm">Add New</a>
                </div>
                <div class="card-body py-3">

                    @include('layout.alerts')

                    <table class="table table-striped datatable">
                        <thead>
                            <tr>
                                <th>SR no.</th>
                                <th>Contract Title</th>
                                <th>Created At</th>
                                <th>User(Client)</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contracts as $contract)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $contract->title }}</td>
                                    <td>
                                        @php
                                            $date = explode(' ',$contract->created_at)
                                        @endphp
                                        {{ $date[0] }}
                                    </td>
                                    <td>
                                        @foreach ($contract->contract_users as $user)
                                            <span
                                                class="badge bg-{{ $user->status == 'filled' ? 'success' : 'primary' }} text-capitalize text-white m-1">
                                                {{ $user->user->name }}
                                                {!! $user->status == 'filled' ? "<i class='fa fa-check'></i>" : '' !!}
                                            </span>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if ($contract->contract_users->count() == $contract->contract_users->where('status', 'filled')->count())
                                            <span class="badge bg-success text-white">Completed</span>
                                        @elseif ($contract->contract_users->where('status', 'filled')->count() > 0)
                                            <span class="badge bg-warning">
                                                Partially Complete
                                                <small
                                                    class="badge bg-primary text-white py-1 px-2">{{ $contract->contract_users->where('status', 'filled')->count() }}</small>
                                            </span>
                                        @else
                                            <span class="badge bg-danger text-white">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group mb-2">
                                            <button class="btn btn-warning btn-sm dropdown-toggle" type="button"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                Actions
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right" x-placement="top-start"
                                                style="position: absolute; transform: translate3d(0px, -177px, 0px); top: 0px; left: 0px; will-change: transform;">

                                                <a href="{{ route('contract.view', $contract->id) }}"
                                                    class="dropdown-item">Show</a>
                                                <a href="{{ route('contract.copy', $contract->id) }}"
                                                    class="dropdown-item">Copy</a>
                                                @if ($contract->contract_users->where('status', 'filled')->count() == 0)
                                                    <a href="{{ route('contract.edit', $contract->id) }}"
                                                        class="dropdown-item">Edit</a>
                                                    <a href="{{ route('contract.delete', $contract->id) }}"
                                                        onclick="return confirm('Are You Sure Want to Delete This Contract??')"
                                                        class="dropdown-item">Delete</a>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="{{ asset('assets/bundles/datatables/datatables.min.js') }}"></script>
        <script>
            $(".datatable").dataTable();
        </script>
    @endpush
@endsection
