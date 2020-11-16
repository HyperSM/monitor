@extends('/layout')

@section('content')

	@include('ciscosdwan.menu')

<div style="height: 10px;"></div>
<h4>
		<b>
		Events for device - 
		<font color="#006699">			
			<span>{{$deviceid}}</span>
		</font>
		</b>
</h4>
<div style="height: 10px;"></div>
<table border="0">
	<tr>
		<td style="width:10px;"></td>
		<td>View Device By</td>
		<td style="width:20px;"></td>
		<td>
			<select onchange="selectview()" id="switchview">
				<option value="System Status">System Status</option>
				<option value="Application-DPI">Application-DPI</option>
				<option value="Events" selected>Events</option>
				<option value="Connections">Connections</option>
			</select>
		</td>
	</tr>
</table>

<div style="height: 20px;"></div>

<table class="table table-striped table-bordered table-hover datatable" data-display-length="10">
    <thead>
        <tr>
            <th style="width: 250px;">Event Time</th>
            <th style="width: 150px;">Host Name</th>
            <th style="width: 150px;">IP Address</th>
            <th style="width: 250px;">Event Name</th>
            <th>Severity</th>
            <th>Component</th>
            <th>Detail</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($events))
            @foreach ($events as $event)
                <tr>
                    <td>
                        @if (isset($event->entry_time))
                        {{ date('M d, Y h:i A',($event->entry_time*25200*1000)/1000) }}
                        @endif
                    </td>
                    <td>
                        @if (isset($event->device_type))
                        {{ $event->device_type }}
                        @endif
                    </td>
                    <td>
                        @if (isset($event->system_ip))
                        {{ $event->system_ip }}
                        @endif
                    </td>
                    <td>
                        @if (isset($event->eventname))
                        {{ $event->eventname }}
                        @endif
                    </td>
                    <td>
                        @if (isset($event->severity_level))
                        {{ $event->severity_level }}
                        @endif
                    </td>
                    <td>
                        @if (isset($event->component))
                        {{ $event->component }}
                        @endif
                    </td>
                    <td>
                        @if (isset($event->details))
                        {{ $event->details }}
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>


<script>
    //Chuyển view summary, application dpi, interface ...
    function selectview() {
        var e = document.getElementById("switchview");
        //var value = e.options[e.selectedIndex].value;
        var text = e.options[e.selectedIndex].text;
        
        //alert($("#switchview :selected").text(););
       switch (text){
            case 'System Status':
                var url = '{{@Config::get('app.url')}}/admin/ciscosdwan/network/detail/{{$deviceid}}/systemstatus';
                break;
            case 'Application-DPI':
                var url = '{{@Config::get('app.url')}}/admin/ciscosdwan/network/detail/{{$deviceid}}/applicationdpi';
                break;
            case 'Events':
                var url = '{{@Config::get('app.url')}}/admin/ciscosdwan/network/detail/{{$deviceid}}/events';
                break;
            case 'Connections':
                var url = '{{@Config::get('app.url')}}/admin/ciscosdwan/network/detail/{{$deviceid}}/connections';
                break;
            case 'Interfaces':
                var url = '{{@Config::get('app.url')}}/admin/ciscosdwan/network/detail/{{$deviceid}}/interfaces';
                break;
            default:
                break;
        }

        window.location.href = url;
    }


</script>
@endsection

