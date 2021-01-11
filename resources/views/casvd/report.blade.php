@extends('/layout')
@section('content')
    @include('casvd.menu')


    <div class="row" >
        <form class="form-inline" id="formReport" action="{{@Config::get('app.url')}}/admin/casvd/reporttotal" method="post">
            @csrf
            <input type="hidden" name="starttime" id="starttime">
            <input type="hidden" name="endtime" id="endtime">
            <div class="col-md-4>
                <div class="statbox widget box box-shadow">
                    <div class="widget-header" style="padding: 20px">
                        <span style="width: 105px;float: left"><b>SELECT RANGE  :</b></span>
                        <div id="incidentrange" style="float:left;width: 210px" >
                            <span></span> &nbsp;
                            <i class="fa fa-calendar"></i>&nbsp;
                            <i class="fa fa-caret-down"></i>
                        </div>
                        <input type="submit" class="btn btn-default"  value="View Report">
                    </div>
                </div>
            </div>

        </form>
    </div>

    <script type="text/javascript">
        $(function() {
            var start = moment().startOf('day');
            var end = moment().startOf('day');
            document.getElementById('starttime').value = start.unix();
            document.getElementById('endtime').value = end.unix();
            console.log(start.unix() + ' ' + end.unix() )

            $('#incidentrange').daterangepicker(
                {
                    startDate: start,
                    endDate: end,
                    alwaysShowCalendars: true,
                    ranges: {
                        'Today': [moment(), moment()],
                        // 'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        // 'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    }
                },

                function (start, end) {
                    $('#incidentrange span').html(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));
                    //ajaxGetTotal('incident',start.unix(),end.unix());
                    document.getElementById('starttime').value = start.unix();
                    document.getElementById('endtime').value = end.unix();
                    console.log(start.unix() + ' ' + end.unix() )
                }
            );

        });
    </script>

@endsection



