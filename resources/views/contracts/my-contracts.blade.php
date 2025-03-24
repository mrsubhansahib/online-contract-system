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
                    My Contracts
                </h4>
            </div>
            <div class="card-body py-3">
            
                @include('layout.alerts')
                
                <table class="table table-striped datatable">
                    <thead>
                        <tr>
                            <th>SR no.</th>
                            <th>Contract Title</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($contracts as $contract)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $contract->title }}</td>
                                <td>{{ $contract->status }}</td>
                                <td>
                                    <a href="{{ route('contract.view',$contract->id) }}" class="btn btn-info fa fa-eye"></a>
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