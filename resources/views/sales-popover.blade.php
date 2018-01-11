<a name="sales-popover" tabindex="0" role="button" data-toggle="popover"
   data-trigger="focus" title="Sales Update Frequency"
   data-content="We update our sales figures quaterly. Some data may be missing for the last few months.">
    <i class="fa fa-lg fa-info-circle" aria-hidden="true"></i>
</a>
<script>
    $(function () {
      $('[name="sales-popover"]').popover({html: true});
    });
</script>