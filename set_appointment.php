<?php
include ('admin/db_connect.php')
?>
<style>
	#uni_modal .modal-footer{
		display: none
	}
</style>
<div class="container-fluid">
	<div class="col-lg-12">
		<div id="msg"></div>
		<form action="" id="manage-appointment">
			<input type="hidden" name="doctor_id" value="<?php echo $_GET['id'] ?>">
			<div class="form-group">
				<label for="" class="control-label">Date</label>
				<input type="date" value="" name="date" class="form-control" required>
			</div>

			<div class="form-group">
				<label for="" class="control-label">Time</label>
				<input type="time" value="" name="time" class="form-control" required>
			</div>

			<hr>
			<div class="col-md-12 text-center">
				<button class="btn-primary btn btn-sm col-md-4">Request</button>
				<button class="btn btn-secondary btn-sm col-md-4  " type="button" data-dismiss="modal" id="">Close</button>
			</div>
		</form>
	</div>
</div>

<script>
	
	$("#manage-appointment").submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
			url:'admin/ajax.php?action=set_appointment',
			method:'POST',
			data:$(this).serialize(),
			success:function(resp){
				resp = JSON.parse(resp)
				if(resp.status == 1){
					alert_toast("Request submitted successfully");
					end_load();
					$('.modal').modal("hide");
				}else{
					$('#msg').html('<div class="alert alert-danger">'+resp.msg+'</div>')
					end_load();
				}
			}
		})
	})
</script>

