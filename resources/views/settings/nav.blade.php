@section('scripts')
    <script>
        $(function() {
            let path = window.location.pathname.split('/')[2];
            $('[data-url='+path+']').addClass('active');
        });
    </script>
@endsection
<div class="nav">
    <div class="nav-item">
        <button class="btn btn-sm btn-outline-secondary" data-url="users">
            {{ __('Users management') }}
        </button>
    </div>
</div>
<hr>
