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
