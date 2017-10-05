@section('head')
<link href="{{ mix('css/pie-graph.css') }}" rel="stylesheet">
@endsection

@foreach ($data as $graph)
<div class="graph-wrap">
    <p class="section-header line-break-double">
        {!! $graph['title'] !!}
    </p>
    <div class="line-break"> </div>

    <?php
    $start = 0; // start degree
    $piece = 0; // pie section number
    ?>
    @foreach ($graph['data'] as $measure => $stat)
    <?php
    $value = round(($stat * 360) / $graph['total']);
    $piece++;
    if ($value >= 180) {
        $class = "pie big";
    } else {
        $class = "pie";
    }
    ?>
    
    <div id="piece-{{ $piece }}" class="{{ $class }}" data-start="{{ $start }}"
         data-value="{{ $value }}">
    </div>
    <style>
        #piece-{{ $piece }}:before,
        #piece-{{ $piece }}:after {
            background-color: #{{ $colours[$piece - 1] }};
        }
        .pie[data-start="{{ $start }}"] {
            -moz-transform: rotate({{ $start }}deg); /* Firefox */
            -ms-transform: rotate({{ $start }}deg); /* IE */
            -webkit-transform: rotate({{ $start }}deg); /* Safari/Chrome */
            -o-transform: rotate({{ $start }}deg); /* Opera */
            transform:rotate({{ $start }}deg);           
        }
        .pie[data-value="{{ $value }}"]:before {
            -moz-transform: rotate({{ $value + 1 }}deg); /* Firefox */
            -ms-transform: rotate({{ $value + 1 }}deg); /* IE */
            -webkit-transform: rotate({{ $value + 1 }}deg); /* Safari/Chrome */
            -o-transform: rotate({{ $value + 1 }}deg); /* Opera */
            transform:rotate({{ $value + 1 }}deg);   
        }
    </style>
    <?php
    $start += $value;
    ?>
    @endforeach

    <table id="legend">
        <tr>
            <td></td>
            <td>{{ $graph['column'] }}</td>
            <td>Visits</td>
            <td>Pct.</td>
        </tr>
        <?php
        $count = count($graph['data']) - 1;
        arsort($graph['data']);
        ?>
        @foreach ($graph['data'] as $measure => $stat)
        <?php
            $pct = number_format(($stat * 100) / $graph['total'], 2);
        ?>
        <tr>
            <td>
                <span class="legend-label"
                      style="background-color: #{{ $colours[$count] }};">
                </span>
            </td>
            <td>{{ $measure }}</td>
            <td>{{ $stat }}</td>
            <td>{{ $pct }}</td>
        </tr>
        <?php
            $count--
        ?>
        @endforeach
    </table>
</div>
@endforeach