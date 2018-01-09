@extends('layouts.users-btn')
@section('title', 'Edit User')

@section('secondary-btn')
    <a id="delete-user-btn" class="btn btn-danger pointer pull-right">
        <i class="fa fa-trash" aria-hidden="true"></i>
        Delete User
    </a>
<script>
    $(document).ready(function() {
        $('#delete-user-btn').click(function() {
            $('#confirm-delete').removeClass('hidden');
        });
        $('.cancel').click(function() {
            $('#confirm-delete').addClass('hidden');
        });
    });
</script>
@endsection
@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div id="confirm-delete"
                     class="alert alert-warning fade in hidden">
                    <button type="button" class="close cancel"
                            aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4>Do you really want to delete this user?</h4>
                    <p>This action cannot be undone.</p>
                    <p>
                        <form action="{{ route('delete-user',
                             ['user_id' => $user->user_id]) }}" method="POST">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                                Delete
                            </button>
                            <button type="button"
                                    class="btn btn-default cancel">
                                <i class="fa fa-ban" aria-hidden="true"></i>
                                Cancel
                            </button>
                        </form>
                    </p>
                </div>
            </div>
        </div>
    </div>

    @include('users.edit-form')

@endsection
