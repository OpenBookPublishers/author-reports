<a name="private-popover" tabindex="0" role="button" data-toggle="popover"
   data-trigger="focus" title="Data privacy" class="pull-left"
   style="margin-right: -80px;" data-placement="right"
   data-content="This data is not displayed publicly. You are logged-in and, therefore, have access to this data even in the public report.">
    <i class="fa fa-lg fa-lock" aria-hidden="true" style="color:"></i> Private
</a>
<script>
    $(function () {
      $('[name="private-popover"]').popover({html: true});
    });
</script>
