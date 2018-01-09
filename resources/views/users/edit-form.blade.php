<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Edit User: {{ $user->fullName() }}
                </div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST"
                          action="{{ route('update-user',
                                      ['user_id' => $user->user_id]) }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name')
                            ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">
                                Name
                            </label>

                            <div class="col-md-6">
                                <input id="name" type="text"
                                       class="form-control" name="name"
                                       value="{{ $user->name or old('name') }}"
                                       required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>
                                            {{ $errors->first('name') }}
                                        </strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('surname')
                            ? ' has-error' : '' }}">
                            <label for="surname"
                                   class="col-md-4 control-label">
                                Surname
                            </label>

                            <div class="col-md-6">
                                <input id="surname" type="text"
                                 class="form-control" name="surname"
                                 value="{{ $user->surname or old('surname') }}"
                                       required>

                                @if ($errors->has('surname'))
                                    <span class="help-block">
                                        <strong>
                                            {{ $errors->first('surname') }}
                                        </strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email')
                            ? ' has-error' : '' }}">
                            <label for="email"
                                   class="col-md-4 control-label">
                                Email
                            </label>

                            <div class="col-md-6">
                                <input id="email" type="text"
                                       class="form-control" name="email"
                                       value="{{ $user->email
                                            or old('email') }}"
                                       required >

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>
                                            {{ $errors->first('email') }}
                                        </strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('orcid')
                            ? ' has-error' : '' }}">
                            <label for="orcid"
                                   class="col-md-4 control-label">
                                ORCID number
                            </label>

                            <div class="col-md-6">
                                <input id="orcid" type="text"
                                   class="form-control" name="orcid"
                                   value="{{ $user->orcid or old('orcid') }}">

                                @if ($errors->has('orcid'))
                                    <span class="help-block">
                                        <strong>
                                            {{ $errors->first('orcid') }}
                                        </strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('twitter')
                            ? ' has-error' : '' }}">
                            <label for="twitter"
                                   class="col-md-4 control-label">
                                Twitter Username
                            </label>

                            <div class="col-md-6">
                                <input id="twitter" type="text"
                                     class="form-control" name="twitter"
                                     value="{{ $user->twitter
                                         or old('twitter') }}">

                                @if ($errors->has('twitter'))
                                    <span class="help-block">
                                        <strong>
                                            {{ $errors->first('twitter') }}
                                        </strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('repositories')
                            ? ' has-error' : '' }}">
                            <label for="repositories"
                                   class="col-md-4 control-label">
                                University Repositories where the user has uploaded their book.
                            </label>

                            <div class="col-md-6">
                                <textarea id="repositories"
                                          class="form-control"
                                          name="repositories">
                              {{ $user->repositories or old('repositories') }}"
                                </textarea>

                                @if ($errors->has('repositories'))
                                    <span class="help-block">
                                        <strong>
                                          {{ $errors->first('repositories') }}
                                        </strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('display_sales')
                                                    ? ' has-error' : '' }}">
                            <label for="display_sales"
                                   class="col-md-4 control-label">
                                Display sales data publicly?
                            </label>

                            <div class="col-md-6">
                                <div class="radio">
                                    <label>
                                        <input type="radio"
                                               name="display_sales"
                                               {{ $user->display_sales ?
                                               'checked="checked"'
                                               : '' }}
                                               value="true" autofocus>
                                        Yes
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio"
                                               name="display_sales"
                                               {{ !$user->display_sales ?
                                               'checked="checked"'
                                               : '' }}
                                               value="false" autofocus>
                                        No
                                    </label>
                                </div>

                                @if ($errors->has('display_sales'))
                                    <span class="help-block">
                                        <strong>
                                          {{ $errors->first('display_sales') }}
                                        </strong>
                                    </span>
                                @endif
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
                                               {{ $user->admin ?
                                               'checked="checked"'
                                               : '' }}
                                               value="true" autofocus>
                                        Yes
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio"
                                               name="admin"
                                               {{ !$user->admin ?
                                               'checked="checked"'
                                               : '' }}
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

                        @include('users.author-select')

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Save
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>