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

                        @include('users.personal-details')

                        <div class="form-group{{ $errors->has('repositories')
                            ? ' has-error' : '' }}">
                            <label for="repositories"
                                   class="col-md-4 control-label">
                                University Repositories where the user has uploaded their book.
                            </label>

                            <div class="col-md-6">
                                <textarea id="repositories"
                                          class="form-control"
                                          name="repositories">{{ $user->repositories or old('repositories') }}</textarea>

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