@extends('/layout')
@section('content')
@include('centreon.menu')

<style>
    .report-content{
        padding:10px;
    }
</style>

<div class="report-content">
<form class="form-inline" id="formReport" action="{{@Config::get('app.url')}}/admin/centreon/reportdetailbyhost" method="post">
    @csrf
    <div class="form-group">
        <label style="font-size: 20px;font-weight: bold">Select Host</label>
        <select class="form-control" name="host">
            @foreach($hosts as $host)
                <option value="{{$host->name}}">{{$host->name}}</option>
            @endforeach
        </select>

    </div>
    <input type="submit" class="btn btn-default" style="margin-top: 22px" value="View Report">
</form>

</div>

@endsection
