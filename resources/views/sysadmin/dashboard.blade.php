@extends('sysadmin/layout')
@section('content')
@include('sysadmin.menu')

<div class="page-header">
    <div class="page-title" style="margin: auto; width: 100%; text-align: center;">
        <h2><b>Admin Dashboard</b></h2>
        <h5>Welcome back, <b>{{$user->fullname}}</b>!</h5>
    </div>
</div>

<div class="row row-bg">
    <div style="height: 5vh;"></div>
    <div style="margin: auto; width: 20%;">
        <div class="statbox widget box box-shadow">
            <div class="widget-content">
                <div class="visual green">
                    <i class="icon-user-md"></i>
                </div>
                <div class="title">Admin users</div>
                <div class="value"><?php echo count($users); ?></div>
                <a class="more" href="#adminModal" data-toggle="modal">View More <i class="pull-right icon-angle-right"></i></a>
            </div>
        </div>
    </div>

    <div style="height: 5vh;"></div>
    <div style="margin: auto; width: 20%;">
        <div class="statbox widget box box-shadow">
            <div class="widget-content">
                <div class="visual cyan">
                    <i class="icon-group"></i>
                </div>
                <div class="title">Domains</div>
                <div class="value"><?php echo count($domains); ?></div>
                <a class="more" href="#domainModal" data-toggle="modal">View More <i class="pull-right icon-angle-right"></i></a>
            </div>
        </div>
    </div>

    <div style="height: 5vh;"></div>
    <div style="margin: auto; width: 20%;">
        <div class="statbox widget box box-shadow">
            <div class="widget-content">
                <div class="visual yellow">
                    <i class="icon-dollar"></i>
                </div>
                <div class="title">Price Management</div>
                <div class="value">$</div>
                <a class="more" href="#priceModal" data-toggle="modal">View More <i class="pull-right icon-angle-right"></i></a>
            </div>
        </div>
    </div>

    <div style="height: 5vh;"></div>
</div>

<!-- Modal dialog -->
    <!-- Admin users -->
    <div class="modal fade" id="adminModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Admin users</h4>
                </div>
                <div class="modal-body">
                    <a style="text-decoration: none;" href="{{@Config::get('app.url')}}/sysadmin/users/adduser"><i class="icon-plus"></i> Add new user </a>
                    <div style="height: 5px;"></div>
                    <table class="table table-hover table-bordered">
                        <thead>
                            <th>UserName</th>
                            <th>FullName</th>
                        </thead>
                        <tbody>
                            @foreach ($users as $itemUser)
                                <tr>
                                    <td><a href="{{@Config::get('app.url')}}/sysadmin/users/edit/{{$itemUser->userid}}">{{$itemUser->username}}</a></td>
                                    <td><a href="{{@Config::get('app.url')}}/sysadmin/users/edit/{{$itemUser->userid}}">{{$itemUser->fullname}}</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div style="height: 10px;"></div>
                    <a href="{{@Config::get('app.url')}}/sysadmin/users">Show all users</a>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Domains -->
    <div class="modal fade" id="domainModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Domains</h4>
                </div>
                <div class="modal-body">
                    <a style="text-decoration: none;" href="{{@Config::get('app.url')}}/sysadmin/domains/adddomain"><i class="icon-plus"></i> Add new domain </a>
                    <div style="height: 5px;"></div>
                    <table class="table table-hover table-bordered">
                        <thead>
                            <th>DomainName</th>
                            <th>Company</th>
                        </thead>
                        <tbody>
                            @foreach ($domains as $itemDomain)
                                <tr>
                                    <td><a href="{{@Config::get('app.url')}}/sysadmin/domains/edit/{{$itemDomain->domainid}}">{{$itemDomain->domainname}}</a></td>
                                    <td>{{$itemDomain->company}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div style="height: 10px;"></div>
                    <a href="{{@Config::get('app.url')}}/sysadmin/domains">Show all domains</a>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Price management -->
    <div class="modal fade" id="priceModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Price Management</h4>
                </div>
                <div class="modal-body">
                    <a style="text-decoration: none;" href="{{@Config::get('app.url')}}/sysadmin/billing/prices/addprice"><i class="icon-plus"></i> Add new product </a>
                    <div style="height: 5px;"></div>
                    <table class="table table-hover table-bordered">
                        <thead>
                            <th>Product</th>
                            <th>Price</th>
                        </thead>
                        <tbody>
                            @foreach ($prices as $itemPrice)
                                <tr>
                                    <td><a href="{{@Config::get('app.url')}}/sysadmin/billing/prices/edit/{{$itemPrice->product}}">{{$itemPrice->product}}</a></td>
                                    <td>{{$itemPrice->price}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div style="height: 10px;"></div>
                    <a href="{{@Config::get('app.url')}}/sysadmin/billing/prices">Show all product</a>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection