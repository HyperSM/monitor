@extends('/layout')

@section('content')

	@include('ciscosdwan.menu')

<div style="height: 10px;"></div>
    <h4>
        <b>
        Connections for device - 
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
                <option value="Events">Events</option>
                <option value="Connections" selected>Connections</option>
            </select>
        </td>
    </tr>
</table>

<div style="height: 20px;"></div>

<table class="table table-striped table-bordered table-hover datatable" data-display-length="10">
    <thead>
        <tr>
            <th>Peer Type</th>
            <th>Peer System IP</th>
            <th>Peer Protocol</th>
            <th>Peer Private Port</th>
            <th>Peer Public Port</th>
            <th>Last Update</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($connections))
            @foreach ($connections as $connection)
                @php( $item = (array) $connection )
                <tr>
                    <td>
                        @if (isset($item['peer-type']))
                        {{ $item['peer-type'] }}
                        @endif
                    </td>
                    <td>
                        @if (isset($item['system-ip']))
                        {{ $item['system-ip'] }}
                        @endif
                    </td>
                    <td>
                        @if (isset($item['protocol']))
                        {{ $item['protocol'] }}
                        @endif
                    </td>
                    <td>
                        @if (isset($item['private-port']))
                        {{ $item['private-port'] }}
                        @endif
                    </td>
                    <td>
                        @if (isset($item['public-port']))
                        {{ $item['public-port'] }}
                        @endif
                    </td>
                    <td>
                        @if (isset($item['lastupdated']))
                        {{ date('d-M-Y H:i:s',$item['lastupdated']/1000) }}
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

