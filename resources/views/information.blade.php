@if (!Auth::user()->twitter
     || !Auth::user()->orcid
     || !Auth::user()->repositories)
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Information request</div>

                <div class="panel-body">

                    <div class="alert alert-info">
                        <i class="fa fa-lg fa-exclamation-circle" aria-hidden="true"></i>
                        <b>Please take a few seconds to answer the questions below.</b> Click "submit"
                    </div>
                    <form id="info-form" class="form-horizontal"
                          method="POST" action="{{ route('update-info') }}">
                        {{ csrf_field() }}

                        @if (!Auth::user()->display_sales)
                        <p>
                            Previously, on every title page we displayed the total number of readers who have accessed the book. We now provide more granular data, including the number of readers who discover our books on several other platforms. As part of our efforts to disclose all available data about the readership of our books, we would also like to display sales data for each title on our website. In order to do so we require your approval.
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
                            OBP is developing new tools that will improve the dissemination of our books, including metadata distribution. Similar to DOIs and ISBNs, <a href="https://orcid.org/">ORCID</a> numbers facilitate the retrieval and dissemination of your work and we would therefore like to link our author's ORCID number to their OBP publication. If you agree and have an ORCID number please provide it below:
                        </p>
                        <div class="form-group{{ $errors->has('orcid')
                                                    ? ' has-error' : '' }}">
                            <label for="orcid" class="col-md-4 control-label">
                                ORCID number.
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
                            We would like to follow you on Twitter and receive updates about your work. If you agree and have a Twitter account please provide your username below:
                        </p>
                        <div class="form-group{{ $errors->has('twitter')
                                                  ? ' has-error' : '' }}">
                            <label for="surname" class="col-md-4 control-label">
                                Twitter username.
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
                                   href="https://twitter.com/intent/user?screen_name=OpenBookPublish">
                                    <span class="fa fa-twitter"></span>
                                    Follow us
                                </a>
                            </div>
                        </div>
                        @endif

                        @if (!Auth::user()->repositories)
                        <p>
                            As you know, we strive to <a href="https://www.openbookpublishers.com/section/84/1/">collect readership data and download metrics</a> from a number of platforms that host our publications. In order to provide accurate data please let us know if you have uploaded your book to any third-party platform, including university repositories. Please provide the URLs below:
                        </p>
                        <div class="form-group{{ $errors->has('repositories') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">
                                Repository URLs.
                            </label>

                            <div class="col-md-6">
                                <textarea id="repositories"
                                          class="form-control"
                                          name="repositories">{{ old('repositories') }}</textarea>

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
                                <button id="info-submit" type="submit"
                                        class="btn btn-primary">
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
<style>
    #info-submit {
        -webkit-transition: all 0.5s ease;
        -moz-transition: all 0.5s ease;
        -o-transition: all 0.5s ease;
        transition: all 0.5s ease;
    }
</style>
<script>
    $('input').change(function(){
        $('#info-submit').removeClass('btn-primary');
        $('#info-submit').addClass('btn-success');
        $('#info-submit').addClass('bold');
    });
</script>
@endif
