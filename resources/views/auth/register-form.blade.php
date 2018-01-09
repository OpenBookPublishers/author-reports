<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">New User</div>

                <div class="panel-body">
                    <form class="form-horizontal" id="register-form"
                          method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('surname') ? ' has-error' : '' }}">
                            <label for="surname" class="col-md-4 control-label">Surname</label>

                            <div class="col-md-6">
                                <input id="surname" type="text" class="form-control" name="surname" value="{{ old('surname') }}" required autofocus>

                                @if ($errors->has('surname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('surname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('admin')
                                                    ? ' has-error' : '' }}">
                            <label for="admin"
                                   class="col-md-4 control-label">
                                Is this user an administrator?
                            </label>

                            <div class="col-md-6">
                                <div class="radio">
                                    <label>
                                        <input type="radio"
                                               name="admin"
                                               value="true" autofocus>
                                        Yes
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio"
                                               name="admin"
                                               checked="checked"
                                               value="false" autofocus>
                                        No
                                    </label>
                                </div>

                                @if ($errors->has('admin'))
                                    <span class="help-block">
                                        <strong>
                                          {{ $errors->first('admin') }}
                                        </strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('author')
                                                    ? ' has-error' : '' }}">
                            <label for="admin"
                                   class="col-md-4 control-label">
                                Is this user an author?
                            </label>

                            <div class="col-md-6">
                                <div class="radio">
                                    <label>
                                        <input type="radio"
                                               name="author"
                                               value="true" autofocus>
                                        Yes
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio"
                                               name="author"
                                               checked="checked"
                                               value="false" autofocus>
                                        No
                                    </label>
                                </div>

                                @if ($errors->has('author'))
                                    <span class="help-block">
                                        <strong>
                                          {{ $errors->first('author') }}
                                        </strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group
                          {{ $errors->has('author_id') ? ' has-error' : '' }}"
                              id="author-select-group">
                            <label for="author_id"
                                   class="col-md-4 control-label">
                                Author
                            </label>

                            <div class="col-md-6">
                                <select id="author_id"
                                        class="form-control selectpicker"
                                        data-live-search="true"
                                        data-live-search-placeholder="Search by author name or book title"
                                        name="author_id">
                                    <option selected>
                                        -- Please Select --
                                    </option>
                                    @foreach ($authors as $author)
                                    <option value="{{ $author->author_id }}"
                                            data-tokens="
                                            {{ $author->author_name . " " }}
                                            @foreach ($author->books as $book)
                                            {{ $book->title . " " }}
                                            @endforeach
                                            ">
                                        {{ $author->author_name }}
                                    </option>
                                    @endforeach
                                </select>

                                @if ($errors->has('author_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('author_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Add User
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var is_author = $('#register-form input:radio[name=author]');
        toggleAuthorSelect(is_author.value);

        is_author.change(function() {
            toggleAuthorSelect(this.value);
        });
    });

    function toggleAuthorSelect(value) {
        var author_select = $('#author-select-group');
        if (value == 'true') { 
            author_select.removeClass('hidden'); 
        } else {
            author_select.addClass('hidden');
        }
    }
</script>
