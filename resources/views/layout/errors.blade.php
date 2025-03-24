@if ($errors->any())
    
<div class="alert alert-danger alert-dismissible fade show mt-5 d-block" style="width: 100%;" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Close</span>
    </button>
    @foreach ($errors->all() as $item)
        <p class="m-0"> *{{ $item }}</p>
    @endforeach
</div>

@endif