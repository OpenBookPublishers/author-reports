@if (!Auth::user()->twitter
     || !Auth::user()->orcid
     || !Auth::user()->repositories)
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Information</div>

                <div class="panel-body">
                    
                    <form class="form-horizontal" method="POST"
                          action="{{ route('update-info') }}">
                        {{ csrf_field() }}

                        @if (!Auth::user()->display_sales)
                        <p>
                            Currently, we only display the total number of readers publicly in our website, and we are going to start displaying the broken down view detailing the platforms from which we are obtaining these metrics. We would also like to display sales data publicly, but in order to do so we require your approval first.
                        </p>
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
                        <p>
                            OBP is developing new tools that will improve the dissemination of our books, and we are particularly focusing on the distribution of metadata. Similar to DOIs and ISBNs, <a href="https://orcid.org/">ORCID</a> numbers facilitate such dissemination and we would therefore like to link your ORCID number to your publication.
                        </p>
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
                        <p>
                            We would like to follow you in Twitter and receive updates of your work.
                        </p>
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
                            <div class="col-md-2" style="padding-top: 7px;">
                                <a class="btn-link" target="_blank"
                                   href="https://twitter.com/OpenBookPublish">
                                    <span class="fa fa-twitter"></span>
                                    Follow us
                                </a>
                            </div>
                        </div>
                        @endif

                        @if (!Auth::user()->repositories)
                        <p>
                            As you may know, we collect readership and download metrics from many of the platforms that host our publications. We aim to provide accurate data and thus require to know every platform where your book can be accessed.
                        </p>
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
