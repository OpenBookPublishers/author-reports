<p class="section-header line-break">{{ $table['title'] }}</p>
<table class="report-table table table-responsive">
    <tr>
      <td class="platform border">{{ $table['column'] }}</td>
      @foreach ($book->years_active as $year)
      <td class="border right">{{ $year }}</td>
      @endforeach
      <td class="border right">Totals</td>
    </tr>
    @foreach ($table['data'] as $platform => $stats)
    <?php
    $platform_total = 0;
    $class = $platform === "Net Rev Total" || $platform === "Amount due"
             ? "result" : "right";
    ?>
    <tr>
        <td>{{ $platform }}</td>
        @foreach ($stats as $year => $stat)
        <?php
        $platform_total += $stat;
        if (!isset($table['years_total'][$year])) {
            $table['years_total'][$year] = 0;
        }
        $table['years_total'][$year] += $stat;
        ?>
        <td class="{{ $class }}">
            <!-- FIXME hack for royalties paid -->
            @if ($platform !== "Amount due")
                @if (is_float($stat))
                {{ number_format($stat, 2, '.', '') }}
                @else
                {{ $stat ? : "" }}
                @endif
            @endif
        </td>
        @endforeach
        
        <td class="{{ $class }}">
            @if (is_float($stat))
            {{ number_format($platform_total, 2, '.', '') }}
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
        
