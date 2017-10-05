@if (!Auth::user()->twitter
     || !Auth::user()->orcid
     || !Auth::user()->repositories)
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Information</div>

                <div class="panel-body">
                    <p>
                        Information blurb.
                    </p>
                    <form class="form-horizontal" method="POST"
                          action="{{ route('update-info') }}">
                        {{ csrf_field() }}

                        @if (!Auth::user()->display_sales)
                        <div class="form-group{{ $errors->has('display_sales')
                                                    ? ' has-error' : '' }}">
                            <label for="display_sales"
                                   class="col-md-4 control-label">
                                Would you like to display sales data publicly?
                            </label>

                            <div class="col-md-6">
                                <div class="radio">
                                    <label>
                                        <input type="radio"
                                               name="display_sales"
                                               {{ Auth::user()->display_sales ?
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
                                               {{ !Auth::user()->display_sales ?
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
                        @endif

                        @if (!Auth::user()->orcid)
                        <div class="form-group{{ $errors->has('orcid')
                                                    ? ' has-error' : '' }}">
                            <label for="orcid" class="col-md-4 control-label">
                                Do you have an ORCID number? Please share it with us.
                            </label>

                            <div class="col-md-6">
                                <input id="orcid" type="text"
                                       class="form-control" name="orcid"
                                       value="{{ old('orcid') }}" autofocus>

                                @if ($errors->has('orcid'))
                                    <span class="help-block">
                                        <strong>
                                            {{ $errors->first('orcid') }}
                                        </strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        @endif

                        @if (!Auth::user()->twitter)
                        <div class="form-group{{ $errors->has('twitter')
                                                  ? ' has-error' : '' }}">
                            <label for="surname" class="col-md-4 control-label">
                                Do you use Twitter? Please let us know your username.
                            </label>

                            <div class="col-md-6">
                                <input id="twitter" type="text"
                                       class="form-control"
                                       name="twitter"
                                       value="{{ old('twitter') }}">

                                @if ($errors->has('twitter'))
                                    <span class="help-block">
                                        <strong>
                                            {{ $errors->first('twitter') }}
                                        </strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        @endif

                        @if (!Auth::user()->repositories)
                        <div class="form-group{{ $errors->has('repositories') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">
                                Have you uploaded your book to a university repository? Please tell us which ones and provide the URLs.
                            </label>

                            <div class="col-md-6">
                                <textarea id="repositories" class="form-control"
                                          name="repositories">
                                    {{ old('repositories') }}
                                </textarea>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>
                                            {{ $errors->first('repositories') }}
                                        </strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        @endif

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
