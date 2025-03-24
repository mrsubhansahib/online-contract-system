@extends('layout.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/bundles/datatables/datatables.min.css') }}">
    <style>
        body{
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
                        All Categories
                    </h4>
                    <a href="{{ route('category.add') }}" class="btn btn-primary btn-sm">Add New</a>
                </div>
                <div class="card-body py-3">

                    @include('layout.alerts')

                    <table class="table table-striped datatable">
                        <thead>
                            <tr>
                                <th>SR no.</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ route('category.view',$category->id) }}">
                                            {{ $category->name }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('category.edit',$category->id) }}" class="btn btn-warning fa fa-edit"></a>
                                        <a href="{{ route('category.delete',$category->id) }}" class="btn btn-danger fa fa-trash-alt" onclick="return confirm('Do You Really Want to Delete this Category this will Delete all Data belongs to this Category')"></a>
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
