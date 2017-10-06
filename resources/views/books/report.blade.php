<style>
    .report-wrapper {
        font-family: "Times New Roman", arial, Verdana;
    }
</style>

<div class="report-wrapper">
    @foreach ($data as $table)
        @include('books.table')
    @endforeach
</div>