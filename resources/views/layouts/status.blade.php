<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
        </div>
    </div>
</div>

