<p class="section-header line-break">
    @if ($is_pdf)
        {{ $table['title'] }}
    @else
        {{ $table['title'] }}
        @if (in_array($name, ["readership", "downloads"]))
            @include('readership-popover')
        @elseif ($name === "sales")
            @include('sales-popover')
            @if ($is_public && !$book->areSalesPublic())
                @include('private-popover')
            @endif
        @elseif (str_replace(range(0,9), '', $name) === "royalties"
                 && $is_public)
            @include('private-popover')
        @endif
    @endif
</p>
<div class="{{ !$is_pdf ? 'table-responsive' : '' }}">
    <table class="report-table table">
        <tr>
          <th class="platform border">{{ $table['column'] }}</th>
          @if ($year === null)
            @foreach ($book->years_active as $year => $months)
          <th class="border right">
              @if ($is_pdf)
              {{ $year }}
              @elseif ($is_public)
              <a href="{{ URL::route('public-report',
                          ['doi_prefix' => $book->getDoiPrefix(),
                           'doi_suffix' => $book->getDoiSuffix(),
                           'year' => $year]) }}"
                 data-toggle="tooltip" data-placement="bottom"
                 title="Click here to view {{ $year }}'s monthly breakdown">
                {{ $year }}
              </a>
              @elseif ((str_replace(range(0,9), '', $name) === "royalties") && isset($author))
              <a href="{{ URL::route('admin-royalties-html',
                                     ['author_id' => $author->author_id,
                                      'year' => $year]) }}"
                 data-toggle="tooltip" data-placement="bottom"
                 title="Click here to view {{ $year }}'s monthly breakdown">
                {{ $year }}
              </a>
              @else
              <a href="{{ URL::route('report', ['book_id' => $book->book_id,
                                                'year' => $year]) }}"
                 data-toggle="tooltip" data-placement="bottom"
                 title="Click here to view {{ $year }}'s monthly breakdown">
                {{ $year }}
              </a>
              @endif
          </th>
            @endforeach
          @else
            @if (str_replace(range(0,9), '', $name) === "royalties")
              @foreach ($book->quarters as $q => $quarter)
          <th class="border right">
              {{ $quarter }}
          </th>
              @endforeach
            @else
              @foreach ($book->years_active[$year] as $month => $blank)
          <th class="border right">
              {{ Carbon\Carbon::createFromFormat("m", $month)->format('M') }}
          </th>
              @endforeach
            @endif
          @endif
          <th class="border right">Totals</th>
        </tr>
        @foreach ($table['data'] as $platform => $stats)
        <?php
        $platform_total = 0;
        $class = $platform === "Net Rev Total" || $platform === "Amount due"
                 ? "result right" : "right";
        $platform_class = $platform === "Net Rev Total"
            || $platform === "Amount due" ? "result" : "platform";
        ?>
        <tr>
            <td class="{{ $platform_class }}">{{ $platform }}</td>
            @foreach ($stats as $year => $stat)
            <?php
            $platform_total += $stat;
            if (!isset($table['years_total'][$year])) {
                $table['years_total'][$year] = 0;
            }
            $table['years_total'][$year] += $stat;
            ?>
            <td class="nowrap {{ $class }}">
                <?php // FIXME hack for royalties paid ?>
                @if ($platform !== "Amount due")
                    @if (is_float($stat) && floor($stat) !== $stat)
                    {{ number_format($stat, 2, '.', '') }}
                    @elseif (is_float($stat) && floor($stat) === $stat)
                    {{ number_format($stat, 0, '.', '') }}
                    @else
                    {{ $stat ? : "" }}
                    @endif
                @endif
            </td>
            @endforeach

            <td class="nowrap {{ $class }}">
                @if (is_float($platform_total)
                     && floor($platform_total) !== $platform_total)
                {{ number_format($platform_total, 2, '.', '') }}
                @elseif (is_float($stat)
                         && floor($platform_total) === $platform_total)
                {{ number_format($platform_total, 0, '.', '') }}
                @else
                {{ $platform_total ? : "" }}
                @endif
            </td>
        </tr>
        @endforeach
        @if ($table['totals_col'] !== "")
        <tr>
            <td class="result">{{ $table['totals_col'] }}</td>
            @foreach ($table['years_total'] as $year => $total)
            <?php
            $table['global_total'] += $total;
            ?>
            <td class="result right">{{ $total ? : "" }}</td>
            @endforeach
            <td class="result right">{{ $table['global_total'] ? : "" }}</td>
        </tr>
        @endif
    </table>
</div>
<script>
    $(function () {
      $('[data-toggle="tooltip"]').tooltip();
    });
</script>
