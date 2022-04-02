<?php include 'admin/db_connect.php'; 

	$special = $conn->query("SELECT * FROM medical_specialty");
	$ms_arr = array();
	while ($row=$special->fetch_assoc()) {
		$ms_arr[$row['id']] = $row['name'];
	}


?>
        <header class="masthead">
            <div class="container h-100">
                <div class="row h-100 align-items-center justify-content-center text-center">
                    <div class="col-lg-10 align-self-end mb-4 page-title">
                    	<h3 class="text-white">Doctor's</h3>
                        <hr class="divider my-4" />
                    </div>
                    
                </div>
            </div>
        </header>
	<section class="page-section" id="doctors" >
        <div class="container">
        	<div class="card">
        		<div class="card-body">
        			<div class="col-lg-12">
						<?php if(isset($_GET['sid']) && $_GET['sid'] > 0): ?>
						<div class="row">
							<div class="col-md-12 text-center">
								<?php
								$s = $conn->query("SELECT * from medical_specialty where id = ".$_GET['sid'])->fetch_array()['name'];
								?>
								<h2><b>Doctor/'s who are in titled as <?php echo $s ?></b></h2>
							</div>
						</div>
						<hr class="divider">
						<?php endif; ?>
				<?php 
				$where = "";
				if(isset($_GET['sid']) && $_GET['sid'] > 0)
				$where = " where  (REPLACE(REPLACE(REPLACE(specialty_ids,',','\",\"'),'[','[\"'),']','\"]')) LIKE '%\"".$_GET['sid']."\"%' ";
				$cats = $conn->query("SELECT * FROM doctors_list ".$where." order by id asc");
				while($row=$cats->fetch_assoc()):
				?>
				<div class="row align-items-center">
					<div class="col-md-3">
						<img src="assets/img/<?php echo $row['img_path'] ?>" alt="">
					</div>
					<div class="col-md-6">
						 <p>Name: <b><?php echo "Dr. ".$row['name'].', '.$row['name_pref'] ?></b></p>
						 <p><small>Email: <b><?php echo $row['email'] ?></b></small></p>
						 <p><small>Clinic Address: <b><?php echo $row['clinic_address'] ?></b></small></p>
						 <p><small>Contac #: <b><?php echo $row['contact'] ?></b></small></p>
						 <p><small><a href="javascript:void(0)" class="view_schedule" data-id="<?php echo $row['id'] ?>" data-name="<?php echo "Dr. ".$row['name'].', '.$row['name_pref'] ?>"><i class='fa fa-calendar'></i> Schedule</a></b></small></p>
						 <p><b>Specialties:</b></p>

						 <div>
						 	<?php if(!empty($row['specialty_ids'])): ?>
						 	<?php 
						 	foreach(explode(",", str_replace(array("[","]"),"",$row['specialty_ids'])) as $k => $val): 
						 	?>
						 	<span class="badge badge-light" style="padding: 10px"><large><b><?php echo $ms_arr[$val] ?></b></large></span>
						 	<?php endforeach; ?>
						 	<?php endif; ?>
						 </div>
					</div>
					<div class="col-md-3 text-center align-self-end-sm">
						<button class="btn-outline-primary  btn  mb-4 set_appointment" type="button" data-id="<?php echo $row['id'] ?>"  data-name="<?php echo "Dr. ".$row['name'].', '.$row['name_pref'] ?>">Set Appointment</button>
					</div>
				</div>
				<hr class="divider" style="max-width: 60vw">
				<?php endwhile; ?>
				</div>
				</div>
        	</div>
        </div>
    </section>
    <style>
    	#doctors img{
    		max-height: 300px;
    		max-width: 200px; 
    	}
    </style>
    <script>
        
       $('.view_schedule').click(function(){
			uni_modal($(this).attr('data-name')+" - Schedule","view_doctor_schedule.php?id="+$(this).attr('data-id'))
		})
       $('.set_appointment').click(function(){
       	if('<?php echo isset($_SESSION['login_id']) ?>' == 1)
			uni_modal("Set Appointment with "+$(this).attr('data-name'),"set_appointment.php?id="+$(this).attr('data-id'),'mid-large')
		else{
			uni_modal("Login First","login.php")
		}
		})
    </script>
	
