@extends('/layout')
@section('content')
@include('centreon.menu')
<!-- Page header -->
<div class="page-header">
	<div class="page-title">
		<h3>Hosts</h3>
		
	</div>
</div>

<div class="row">
					<div class="col-md-12">
						<div class="widget box">
							<div class="widget-header">
								<h4><i class="icon-reorder"></i>List Hosts </h4>&nbsp;&nbsp;
								<span id="addnew">
									<a style="text-decoration: none;" href="{{@Config::get('app.url')}}/admin/centreon/hosts/addhost"><i class="icon-plus"></i> Add New </a>
								</span>
								<div class="toolbar no-padding">
									<div class="btn-group">
										<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
									</div>
								</div>
							</div>
							<div class="widget-content no-padding">
								<table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable" data-display-length="25" id="hosts">
									<thead>
										<tr>
											
											<th data-class="expand">Name</th>
											<th> Alias</th>
											<th>IP Address / DNS</th>
											<th>Poller</th>
                                            <th>Status</th>
											<th style="text-align: center;">Action</th>
										</tr>
									</thead>
									<tbody>
								
                                        <?php 
                                        foreach ($hosts as $host){
                                        ?>
                                        <tr>
											
											<td>
                                                <?php
                                                echo $host->name;
                                                ?>
                                            </td>
											<td> 
                                                <?php
                                                echo $host->alias;
                                                ?>
                                            </td>
											<td> 
                                                <?php
                                                echo $host->address;
                                                ?>
                                            </td>
											<td> 
                                                <?php
                                                echo $host->instance_name;
                                                ?>
                                            </td>
                                            <?php
                                            if($host->state == 0 ){
                                            ?>
											<td><span class="label label-success">UP</span></td>
											<?php }elseif($host->state == 2){ ?>
												<td><span class="label label-danger">DOWN</span></td>
											<?php }elseif($host->state == 3){?>
												<td><span class="label label-warning">UNREACHABLE</span></td>
											<?php }elseif($host->state == 4){ ?>
												<td><span class="label label-info">PENDING</span></td>
											<?php }?>
											<td style="text-align: center;">
												<a href="{{@Config::get('app.url')}}/admin/dashboard/users/edit/{{$host->name}}" id="edit" class="bs-tooltip mr-1" title="Edit" style="text-decoration: none;"><i class="icon-edit"></i></a>
												<a href="{{@Config::get('app.url')}}/admin/dashboard/users/delete/{{$host->name}}" id="delete" class="bs-tooltip" title="Delete" style="text-decoration: none;"><i class="icon-trash"></i></a>
											</td>
											</tr>
											
										<?php }?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>

<!-- <script>
$(document).ready(function(){
		$('#hosts').DataTable({
			"aaSorting": [[ 1, "asc" ]],
			"iDisplayLength": 10,
			"aLengthMenu": [5, 10, 15, 25, 50, "All"]
		});
    });
</script> -->
@endsection

