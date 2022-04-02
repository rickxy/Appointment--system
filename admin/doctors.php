<?php include('db_connect.php');?>

<div class="container-fluid">
	
	<div class="col-lg-12">
		<div class="row">
			<!-- FORM Panel -->
			<div class="col-md-4">
			<form action="" id="manage-doctor">
				<div class="card">
					<div class="card-header">
						   Doctor's Form
				  	</div>
					<div class="card-body">
							<div id="msg"></div>
							<input type="hidden" name="id">
							<div class="form-group">
								<label for="" class="control-label">Prefix</label>
								<input type="text" class="form-control" name="name_pref" placeholder="(M.D.)" required="">
							</div>
							<div class="form-group">
								<label class="control-label">Name</label>
								<textarea name="name" id="" cols="30" rows="2" class="form-control" required=""></textarea>
							</div>
							<div class="form-group">
								<label class="control-label">Medical Specialties</label>
								<select name="specialty_ids[]" id="" multiple=""  class="custom-select browser-default select2">
									<option value=""></option>
									<?php 
									$qry = $conn->query("SELECT * FROM medical_specialty order by name asc");
										while($row=$qry->fetch_assoc()):
									 ?>
									<option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
									<?php endwhile; ?>
								</select>
							</div>
							<div class="form-group">
								<label class="control-label">Clinic Address</label>
								<textarea name="clinic_address" id="" cols="30" rows="2" class="form-control" required=""></textarea>
							</div>
							<div class="form-group">
								<label for="" class="control-label">Contact</label>
								<textarea name="contact" id="" cols="30" rows="2" class="form-control" required=""></textarea>
							</div>
							<div class="form-group">
								<label for="" class="control-label">Email</label>
								<input type="email" class="form-control" name="email" required="">
							</div>
							<div class="form-group">
								<label for="" class="control-label">Password</label>
								<input type="password" class="form-control" name="password" >
							</div>
							<div class="form-group">
								<label for="" class="control-label">Image</label>
								<input type="file" class="form-control" name="img" onchange="displayImg(this,$(this))">
							</div>
							<div class="form-group">
								<img src="" alt="" id="cimg">
							</div>	
							
							
					</div>
							
					<div class="card-footer">
						<div class="row">
							<div class="col-md-12">
								<button class="btn btn-sm btn-primary col-sm-3 offset-md-3"> Save</button>
								<button class="btn btn-sm btn-default col-sm-3" type="button" onclick="_reset()"> Cancel</button>
							</div>
						</div>
					</div>
				</div>
			</form>
			</div>
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-8">
				<div class="card">
					<div class="card-body">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="text-center">Image</th>
									<th class="text-center">Info</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$cats = $conn->query("SELECT * FROM doctors_list order by id asc");
								while($row=$cats->fetch_assoc()):
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									<td class="text-center">
										<img src="../assets/img/<?php echo $row['img_path'] ?>" alt="">
									</td>
									<td class="">
										 <p>Name: <b><?php echo "Dr. ".$row['name'].', '.$row['name_pref'] ?></b></p>
										 <p><small>Email: <b><?php echo $row['email'] ?></b></small></p>
										 <p><small>Clinic Address: <b><?php echo $row['clinic_address'] ?></b></small></p>
										 <p><small>Contac #: <b><?php echo $row['contact'] ?></b></small></p>
										 <p><small><a href="javascript:void(0)" class="view_schedule" data-id="<?php echo $row['id'] ?>" data-name="<?php echo "Dr. ".$row['name'].', '.$row['name_pref'] ?>"><i class='fa fa-calendar'></i> Schedule</a></b></small></p>

									</td>
									<td class="text-center">
										<button class="btn btn-sm btn-primary edit-doctor" type="button" data-id="<?php echo $row['id'] ?>" data-name="<?php echo $row['name'] ?>" data-name_pref="<?php echo $row['name_pref'] ?>" data-clinic_address="<?php echo $row['clinic_address'] ?>" data-contact="<?php echo $row['contact'] ?>"  data-img_path="<?php echo $row['img_path'] ?>" data-specialty_ids="<?php echo $row['specialty_ids'] ?>" data-email="<?php echo $row['email'] ?>">Edit</button>
										<button class="btn btn-sm btn-danger delete_doctor" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
									</td>
								</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>	

</div>
<style>
	
	td{
		vertical-align: middle !important;
	}
	td p{
		margin: unset
	}
	img{
		max-width:100px;
		max-height: :150px;
	}
</style>
<script>
	$('.select2').select2({
		placeholder:"Please Select Here",
		width:'100%'
	})
	function _reset(){
		$('[name="id"]').val('');
		$('#manage-doctor').get(0).reset();
	}
	$('table').dataTable()
	$('#manage-doctor').submit(function(e){
		e.preventDefault()
		start_load()
		$('#msg').html('')
		$.ajax({
			url:'ajax.php?action=save_doctor',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully added",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
				else if(resp==2){
					$('#msg').html('<div class="alert alert-danger">Email already exist.</div>')
					end_load()
				}
			}
		})
	})
	function displayImg(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#cimg').attr('src', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]);
	    }
	}
	$('.edit-doctor').click(function(){
		start_load()
		var cat = $('#manage-doctor')
		cat.get(0).reset()
		cat.find("[name='id']").val($(this).attr('data-id'))
		cat.find("[name='name']").val($(this).attr('data-name'))
		cat.find("[name='name_pref']").val($(this).attr('data-name_pref'))
		cat.find("[name='email']").val($(this).attr('data-email'))
		cat.find("[name='clinic_address']").val($(this).attr('data-clinic_address'))
		cat.find("[name='contact']").val($(this).attr('data-contact'))
		cat.find("#cimg").attr("src","../assets/img/"+$(this).attr('data-img_path'))
		
		if($(this).attr('data-specialty_ids')!= ''){
			var ids = $(this).attr('data-specialty_ids')
			ids = ids.replace('[','')
			ids = ids.replace(']','')
			ids=ids.split(',')
			var nids = [];
			ids.map(function(e){
				nids.push(e)
			})
					$('[name="specialty_ids[]"]').val(nids)
		}else{
			$('[name="specialty_ids[]"]').val('')

		}
			$('[name="specialty_ids[]"]').trigger('change')
		// $('[name="specialty_ids[]"]').select2({
		// 	placeholder:"Please Select Here",
		// 	width:'100%'
		// })
		end_load()
	})

	$('.view_schedule').click(function(){
		uni_modal($(this).attr('data-name')+" - Schedule","view_doctor_schedule.php?id="+$(this).attr('data-id'))
	})
	$('.delete_doctor').click(function(){
		_conf("Are you sure to delete this doctor?","delete_doctor",[$(this).attr('data-id')])
	})
	
	function delete_doctor($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_doctor',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
</script>