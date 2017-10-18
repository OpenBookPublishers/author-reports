    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Welcome</div>

                    <div class="panel-body">

                        <p>
                            Welcome to Open Book Publishers reporting interface. If you are an author or a contributor of one of our books, please <a href="mailto:{{ env('SUPPORT_EMAIL') }}?subject=Author reports user request&from={{ Auth::user()->email }}">contact us</a> and we will shortly provide you access to this service.
                        </p>

                    </div>
                </div>
            </div>
        </div>
    </div>
