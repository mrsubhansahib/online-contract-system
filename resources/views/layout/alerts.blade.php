@if (session()->has('danger'))
<div class="alert alert-danger alert-dismissible fade show my-2" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Close</span>
    </button>
    {{ session()->get('danger') }}
</div>
@endif
@if (session()->has('success'))
<div class="alert alert-success alert-dismissible fade show my-2" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Close</span>
    </button>
    {{ session()->get('success') }}
</div>
@endif