
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

