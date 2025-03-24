@extends('layout.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/bundles/datatables/datatables.min.css') }}">
@endpush

@section('content')
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4>
                        All Templates
                    </h4>
                    <a href="{{ route('template.add') }}" class="btn btn-primary btn-sm">Add New</a>
                </div>
                <div class="card-body py-3">

                    @include('layout.alerts')

                    <table class="table table-striped datatable">
                        <thead>
                            <tr>
                                <th>SR no.</th>
                                <th>Template Title</th>
                                <th>Category</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($templates as $template)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $template->title }}</td>

                                    <td>
                                        @if ($template->category_id == null)
                                            Other
                                        @else
                                            @foreach ($categories as $category)
                                                @if ($category->id == $template->category_id)
                                                    {{ $category->name }}
                                                @endif
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('template.edit', $template->id) }}"
                                            class="btn btn-warning fa fa-edit"></a>
                                        <a href="{{ route('template.delete', $template->id) }}"
                                            onclick="return confirm('Are You Sure Want to Delete This Contract Template??')"
                                            class="btn btn-danger fa fa-trash-alt"></a>
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
