@extends('layouts.dashboard-btn')
@section('title', 'Royalties')
@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default" style="min-height: 30em;">
                <div class="panel-heading">
                    All Royalties
                </div>

                <div class="panel-body table-responsive">

                    <table class="table table-hover">
                        <tr>
                            <th>Name</th>
                            <th>Base unit</th>
                            <th>Threshold unit</th>
                            <th>Rate</th>
                            <th>Royalties arising</th>
                            <th>Royalties paid</th>
                            <th>Amount due</th>
                            <th></th>
                            <th></th>
                        </tr>
                        @foreach ($authors as $author)
                        <tr>
                            <td rowspan="
                                {{ count($author->royalties) > 1
                                    ? count($author->royalties) + 1
                                    : count($author->royalties) }}">
                            @if ($author->user === null)
                                {{ $author->author_name }}
                            @else
                                <a href="{{ route('edit-user',
                                        ['user_id' => 
                                        $author->user->user_id]) }}">
                                    {{ $author->user->name }} 
                                    {{ $author->user->surname }}
                                </a>
                            @endif
                            </td>
                            @if (count($author->royalties) <= 1)
                            <td>
                               {{ $author->royalties[0]['base_unit'] }}
                            </td>
                            <td>
                               {{ $author->royalties[0]['threshold_unit'] }}
                            </td>
                            <td>
                               {{ $author->royalties[0]['rate'] }}
                            </td>
                            @else
                            <td></td>
                            <td></td>
                            <td></td>
                            @endif
                            <td>
                                {{ number_format(
                                            $author->royalties_arising,
                                            2, '.', '') }}
                            </td>
                            <td>
                                {{ number_format(
                                            $author->royalties_paid,
                                            2, '.', '') }}
                            </td>
                            <td class="{{ $author->amount_due > 0
                            ? "warning" : "" }}">
                                {{ number_format(
                                            $author->amount_due,
                                            2, '.', '') }}
                            </td>
                            <td>
                                <div class="dropdown">
                                        <button class="btn btn-default
                                                       dropdown-toggle"
                                                type="button"
                                                id="dropdownMenu1"
                                                data-toggle="dropdown"
                                                aria-haspopup="true"
                                                aria-expanded="true">
                                            Actions 
                                            <span class="caret"></span>
                                        </button>

                                        <ul class="dropdown-menu
                                                   dropdown-menu-right"
                                           aria-labelledby="dropdownMenu1">
                                            <li>
                                                <a href="{{ route(
                                                    'admin-royalties-html',
                                                     ['author_id' => 
                                                $author->author_id]) }}">
                                                    <i class="fa
                                                       fa-file-text-o"
                                                   aria-hidden="true"></i>
                                                    Royalties Report
                                                </a>
                                            </li>

                                            <li>
                                                <a href="{{ route(
                                                    'admin-royalties-pdf',
                                                     ['author_id' => 
                                                  $author->author_id]) }}">
                                                    <i class="fa
                                                       fa-file-pdf-o"
                                                   aria-hidden="true"></i>
                                                    Royalties Report (PDF)
                                                </a>
                                            </li>
                                        </ul>
                                </div>
                            </td>
                        </tr>
                        @if (count($author->royalties) > 1)
                        @foreach ($author->royalties as $agreement)
                        <tr style="background-color: #f7f7f7">
                            <td>
                                {{ $agreement['base_unit'] }}
                            </td>
                            <td>
                                {{ $agreement['threshold_unit'] }}
                            </td>
                            <td>
                                {{ $agreement['rate'] }}
                            </td>
                            <td>
                                {{ number_format(
                                          $agreement['Royalties arising'],
                                          2, '.', '') }}
                            </td>
                            <td>
                                {{ number_format(
                                          $agreement['Royalties paid'],
                                          2, '.', '') }}
                            </td>
                            <td>
                                {{ number_format(
                                          $agreement['Amount due'],
                                          2, '.', '') }}
                            </td>
                            <td></td>
                        </tr>
                        @endforeach
                        @endif
                        @endforeach
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
