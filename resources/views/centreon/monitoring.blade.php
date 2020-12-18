@extends('/layout')
@section('content')
@include('centreon.menu')

<div id="result"></div>

<!-- Page header -->
<div class="page-header">
{{--    <div class="page-title">

    </div>--}}
</div>
<div class="row">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                <h4><i class="icon-reorder"></i>Monitoring</h4>&nbsp;&nbsp;
                <div class="toolbar no-padding">
                    <div class="btn-group">
                        <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
                    </div>
                </div>
            </div>
            <div class="widget-content no-padding">
                <table class="table table-striped table-bordered table-hover table-checkable table-responsive "
                    data-display-length="25" id="monitor">
                    <thead>
                        <tr>
                            <th data-class="expand">Host</th>
                            <th> Services</th>
                            <th>Status</th>
                            <th>Hard State Duration</th>
                            <th>Last check</th>
                            <th>Tries</th>
                            <th>Status information</th>
                        </tr>
                    </thead>
                    <tbody>
                    {{--
                        @foreach($monitors as $monitor)
                                <tr>
                                    <td>
                                        @if(strcmp($temp,$monitor->host_id) != 0)
                                                <a href="{{@Config::get('app.url')}}/admin/centreon/hosts/edit/{{$monitor->host_id}}" >{{$monitor->name}}</a>
                                        @else
                                                <a href="#"></a>
                                        @endif

                                    </td>
                                    <td>{{$monitor->description}}</td>
                                    <td>
                                        @if(strpos($monitor->output,'WARNING') !== false)
                                            <span class="label label-warning">WARNING</span>
                                        @elseif(strpos($monitor->output,'CRITICAL') !== false)
                                            <span class="label label-danger">CRITICAL</span>
                                        @elseif(strpos($monitor->output,'UNKNOWN') !== false)
                                            <span class="label label-default">UNKNOWN</span>
                                        @elseif(strpos($monitor->output,'PENDING') !== false)
                                            <span class="label label-default">PENDING</span>
                                        @else
                                            <span class="label label-success">OK</span>
                                        @endif
                                    </td>
                                    <td>{{$monitor->last_hard_state_change}}</td>
                                    <td>{{$monitor->last_check}}</td>
                                    @if($monitor -> check_attempt != "" && $monitor->max_check_attempts != "")
                                        <td>{{$monitor ->check_attempt}} / {{$monitor->max_check_attempts}}</td>
                                    @else
                                        <td></td>
                                    @endif
                                    <td>{{$monitor->output}}</td>
                                </tr>
                        @endforeach--}}

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    .td .tooltiptext {
        visibility: hidden;
        width: 120px;
        background-color: black;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px 0;

        /* Position the tooltip */
        position: absolute;
        z-index: 1;
    }

    .tooltip:hover .tooltiptext {
        visibility: visible;
    }

  /*  td.details-control {
        background: url('https://datatables.net/examples/resources/details_open.png') no-repeat center center;
        cursor: pointer;
    }
    tr.shown td.details-control {
        background: url('https://datatables.net/examples/resources/details_close.png') no-repeat center center;
    }*/
</style>


<script>
$(document).ready(function() {
    var refresherate = {{$refreshrate}}
    setInterval(function () {
        loadpage();
    }, refresherate);

    var loadpage = function () {
        jQuery.ajax({
            headers: {},
            url: '<?php echo URL::route("ajaxmonitors") ?>',
            method: 'get',
            data: {},
            success: function (result, status, xhr) {

                $('#monitor tbody').html(result);
            },
            error: function (xhr, textStatus, errorThrown) {
            }
        });
    }

    loadpage();

    var table = $('#monitor').DataTable({
        select: true
    });

   /* $('#monitor tbody').on('mouseover', 'td.aaa', function() {
        $('.tooltiptext').css('display','block');
    }).on('mouseout', 'td', function() {
        $('.tooltiptext').css('display','none');
    });*/

/*    $('#monitor tbody').on('click', 'td.details-control', function () {

        var $selectedid = $(this).data('id');
        var tr = $(this).closest('tr');
        var row = table.row(tr);

        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        } else {
            // Open this row
            //row.child( format(row.data()) ).show();
            row.child( format() ).show();
            tr.addClass('shown');
        }
    });*/


  /*  function format(){
        jQuery.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                '_token': '{{ csrf_token() }}'
            },
            url: '<?php echo URL::route("ajaxgetdetailhost") ?>',
            method: 'POST',
            data: {
                id: $selectedid,
                _token: '{{ csrf_token() }}'
            },
            success: function (result, status, xhr) {
                console.log(result);

                // Open this row
                row.child(result).show();
                tr.addClass('shown');
            },
            error: function (xhr, textStatus, errorThrown) {
                console.log(errorThrown)
            }
        });
    }*/

});
</script>

@endsection
