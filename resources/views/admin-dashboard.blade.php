    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Administration</div>

                    <div class="panel-body">

                        <a href="{{ route('admin-users') }}"
                           class="btn-large square relative">
                            <div class="centered full-width">
                                <i class="fa fa-user fa-large"
                                   aria-hidden="true"></i>
                                <br>
                                <span class="full-width">
                                    Manage Users
                                </span>
                            </div>
                        </a>

                        <a href="{{ route('admin-books') }}"
                           class="btn-large square relative">
                            <div class="centered full-width">
                                <i class="fa fa-book fa-large"
                                   aria-hidden="true"></i>
                                <br>
                                <span class="full-width">
                                    Manage Books
                                </span>
                            </div>
                        </a>

                        <a href="{{ route('admin-royalties') }}"
                           class="btn-large square relative">
                            <div class="centered full-width">
                                <i class="fa fa-gbp fa-large"
                                   aria-hidden="true"></i>
                                <br>
                                <span class="full-width">
                                    Manage Royalties
                                </span>
                            </div>
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </div>
