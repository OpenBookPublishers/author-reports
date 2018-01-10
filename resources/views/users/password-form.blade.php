<form class="form-horizontal" method="POST"
      action="{{ route('update-password') }}">
    {{ csrf_field() }}

    <div class="form-group{{ $errors->has('old-password')
        ? ' has-error' : '' }}">
        <label for="old-password" class="col-md-4 control-label">
            Current password
        </label>

        <div class="col-md-6">
            <input id="old-password" type="password"
                   class="form-control" name="old-password"
                   required>

            @if ($errors->has('old-password'))
                <span class="help-block">
                    <strong>
                        {{ $errors->first('old-password') }}
                    </strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('password')
        ? ' has-error' : '' }}">
        <label for="password" class="col-md-4 control-label">
            New password
        </label>

        <div class="col-md-6">
            <input id="password" type="password"
                   class="form-control" name="password"
                   required>

            @if ($errors->has('password'))
                <span class="help-block">
                    <strong>
                        {{ $errors->first('password') }}
                    </strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('password_confirmation')
        ? ' has-error' : '' }}">
        <label for="password_confirmation" class="col-md-4 control-label">
            Confirm password
        </label>

        <div class="col-md-6">
            <input id="password-confirm" type="password"
                   class="form-control" name="password_confirmation"
                   required>

            @if ($errors->has('password_confirmation'))
                <span class="help-block">
                    <strong>
                        {{ $errors->first('password_confirmation') }}
                    </strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-6 col-md-offset-4">
            <button type="submit"
                    class="btn btn-primary">
                Save
            </button>
        </div>
    </div>
</form>
