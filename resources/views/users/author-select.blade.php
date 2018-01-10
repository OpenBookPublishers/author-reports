<div class="form-group{{ $errors->has('author') ? ' has-error' : '' }}">
    <label for="admin"
           class="col-md-4 control-label">
        Is this user an author?
    </label>

    <div class="col-md-6">
        <div class="radio">
            <label>
                <input type="radio"
                       name="author"
                       {{ isset($user) && isset($user->author)
                          ? 'checked="checked"' : '' }}
                       value="true" autofocus>
                Yes
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio"
                       name="author"
                       {{ isset($user) && ($user->author)
                          ? '' : 'checked="checked"' }}
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
            <option>-- Please Select --</option>
            @foreach ($authors as $author)
            <option value="{{ $author->author_id }}"
                    data-tokens="
                    {{ $author->author_name . " " }}
                    @foreach ($author->books as $book)
                    {{ $book->title . " " }}
                    @endforeach
                    " {{ isset($user) && isset($user->author) && $user->author->author_id === $author->author_id ? "selected" : "" }}>
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

<script>
    $(document).ready(function() {
        var is_author = $('input:radio[name=author]');
        toggleAuthorSelect(is_author.prop('checked'));

        is_author.change(function() {
            var val = (this.value == 'true');
            toggleAuthorSelect(val);
        });
    });

    function toggleAuthorSelect(value) {
        var author_select = $('#author-select-group');
        if (value) { 
            author_select.removeClass('hidden'); 
        } else {
            author_select.addClass('hidden');
        }
    }
</script>
