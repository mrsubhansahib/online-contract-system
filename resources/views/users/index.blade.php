@extends('layout.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/bundles/datatables/datatables.min.css') }}">
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4>
                        All Users
                    </h4>
                    <a href="{{ route('user.add') }}" class="btn btn-primary btn-sm">Add New</a>
                </div>
                <div class="card-body py-3 table-responsive">

                    @include('layout.alerts')

                    <table class="table table-striped datatable">
                        <thead>
                            <tr>
                                <th>SR no.</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->contact }}</td>
                                    <td>{{ $user->role }}</td>
                                    <td>
                                        <a href="{{ route('user.edit', $user->id) }}" class="btn btn-warning fa fa-edit"></a>
                                        @if ($user->role != 'admin')
                                            <a href="{{ route('user.delete', $user->id) }}"
                                                class="btn btn-danger fa fa-trash-alt" onclick="return confirm('Are You Sure Want to Delete This User. ')"></a>
                                        @endif
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
