@extends('/layout')
@section('content')
@include('casvd.menu')
<h3>test</h3>
@endsection

<script>
    $(document).ready(function() {
		    var ajaxcasvdallrequests = '<?php echo "test" ?>'+'a';
		    document.write(ajaxcasvdallrequests);
		});
</script>