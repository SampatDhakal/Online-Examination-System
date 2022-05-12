<?php

//exam.php

include('oea.php');

$object = new oea();

if(!$object->is_login())
{
	header("location:".$object->base_url."admin");
}

if($object->is_master_user())
{
	include('header.php');
}

else 
{
	include('header1.php');

}

$object->query = "
SELECT * FROM pexam_oea 
WHERE exam_status = 'Pending' OR exam_status = 'Created' 
ORDER BY exam_title ASC
";

$result = $object->get_result();


                
?>

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Exam Subject and Task Management</h1>

                    <!-- DataTales Example -->
					<!-- project based -->
					<span id="message"></span>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                        	<div class="row">
                            	<div class="col">
                            		<h6 class="m-0 font-weight-bold text-primary">Project Based Examination</h6>
                            	</div>
                            	<div class="col" align="right">
                            		<button type="button" name="add_exam_projectsubject" id="add_exam_projectsubject" class="btn btn-success btn-circle btn-sm"><i class="fas fa-plus"></i></button>
                            	</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="exam_subject_projecttable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Exam Name</th>
                                            <th>Subject</th>
                                            <th>Exam Datetime</th>
                                            <th>Full Marks</th>
											<th>Exam Task</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
					<!-- projectbasedd -->

					

                <?php
                include('footer.php');
                ?>

<!-- project based -->

<div id="examsubjectprojectModal" class="modal fade">
  	<div class="modal-dialog">
    	<form method="post" id="exam_subject_projectform">
      		<div class="modal-content">
        		<div class="modal-header">
          			<h4 class="modal-title" id="modal_title">Add Project Based Exam </h4>
          			<button type="button" class="close" data-dismiss="modal">&times;</button>
        		</div>
        		<div class="modal-body">
        			<span id="form_message"></span>
                    <div class="form-group">
                        <label>Exam Name</label>
                        <select name="exam_id" id="exam_id" class="form-control" required>
                            <option value="">Select Exam</option>
                            <?php
                            foreach($result as $row)
                            {
                                echo '
                                <option value="'.$row["exam_id"].'">'.$row["exam_title"].'</option>
                                ';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Subject</label>
                        <select name="subject_id" id="subject_id" class="form-control" required>
                            <option value="">Select Subject</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Exam Date & Time</label>
                        <input type="text" name="subject_exam_datetime" id="subject_exam_datetime" class="form-control datepicker" readonly required data-parsley-trigger="keyup" />
                    </div>
                    <div class="form-group">
                        <label>Full Marks</label>
                        <input type="number" name="subject_full_marks" id="subject_full_marks" class="form-control" required/>
                    </div>
					<!-- <div class="form-group">
                        <label>Exam Task</label>
                        <input type="text" name="subject_exam_task" id="subject_exam_task" class="form-control" required/>
                    </div> -->
					<!-- new -->
					<div class="form-outline">
						<label>Exam Task</label>
 						 <textarea class="form-control" name="subject_exam_task" id="subject_exam_task" rows="4"></textarea>
					</div>
					<!-- new -->
        		</div>
        		<div class="modal-footer">
          			<input type="hidden" name="hidden_id" id="hidden_id" />
          			<input type="hidden" name="action" id="action" value="Add" />
          			<input type="submit" name="submit" id="submit_button" class="btn btn-success" value="Add" />
          			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        		</div>
      		</div>
    	</form>
  	</div>
</div>

<script>
// project based
$(document).ready(function(){

var dataTable = $('#exam_subject_projecttable').DataTable({
	"processing" : true,
	"serverSide" : true,
	"order" : [],
	"ajax" : {
		url:"exam_subject_projectaction.php",
		type:"POST",
		data:{action:'fetch'}
	},
	"columnDefs":[
		{
			"targets":[5],
			"orderable":false,
		},
	],
});	

$('#exam_id').change(function(){
	var exam_id = $('#exam_id').val();
	if(exam_id != '')
	{
		$.ajax({
			url:"exam_subject_projectaction.php",
			method:"POST",
			data:{action:'fetch_subject', exam_id:exam_id},
			success:function(data)
			{
				$('#subject_id').html(data);
			}
		});
	}
});

var date = new Date();
date.setDate(date.getDate());
$("#subject_exam_datetime").datetimepicker({
	startDate: date,
	format: 'yyyy-mm-dd hh:ii',
	autoclose: true
});

$('#add_exam_projectsubject').click(function(){
	
	$('#exam_subject_projectform')[0].reset();

	$('#exam_subject_projectform').parsley().reset();

	$('#modal_title').text('Add Project based exam Data');

	$('#action').val('Add');

	$('#submit_button').val('Add');

	$('#examsubjectprojectModal').modal('show');

	$('#form_message').html('');

	$('#exam_id').attr('disabled', false);

	$('#subject_id').attr('disabled', false);

});

$('#exam_subject_projectform').parsley();

$('#exam_subject_projectform').on('submit', function(event){
	event.preventDefault();
	if($('#exam_subject_projectform').parsley().isValid())
	{		
		$.ajax({
			url:"exam_subject_projectaction.php",
			method:"POST",
			data:$(this).serialize(),
			dataType:'json',
			beforeSend:function()
			{
				$('#submit_button').attr('disabled', 'disabled');
				$('#submit_button').val('wait...');
			},
			success:function(data)
			{
				$('#submit_button').attr('disabled', false);
				if(data.error != '')
				{
					$('#form_message').html(data.error);
					$('#submit_button').val('Add');
				}
				else
				{
					$('#examsubjectprojectModal').modal('hide');

					$('#message').html(data.success);

					dataTable.ajax.reload();

					setTimeout(function(){

						$('#message').html('');

					}, 5000);
				}
			}
		})
	}
});

$(document).on('click', '.edit_button', function(){

	var exam_subject_id = $(this).data('id');

	$('#exam_subject_projectform').parsley().reset();

	$('#form_message').html('');

	$.ajax({

		  url:"exam_subject_projectaction.php",

		  method:"POST",

		  data:{exam_subject_id:exam_subject_id, action:'fetch_single'},

		  dataType:'JSON',

		  success:function(data)
		  {
			$('#subject_id').html(data.subject_select_box);

			$('#exam_id').val(data.exam_id);

			$('#subject_id').val(data.subject_id);

			$('#exam_id').attr('disabled', 'disabled');

			$('#subject_id').attr('disabled', 'disabled');

			$('#subject_full_marks').val(data.subject_full_marks);

			$('#subject_exam_task').val(data.subject_exam_task);

			$('#subject_exam_datetime').val(data.subject_exam_datetime);

			$('#modal_title').text('Edit Project based exam Data');

			$('#action').val('Edit');

			$('#submit_button').val('Edit');

			$('#examsubjectprojectModal').modal('show');

			$('#hidden_id').val(exam_subject_id);

		  }

	})

});

$(document).on('click', '.delete_button', function(){

	var id = $(this).data('id');

	if(confirm("Are you sure you want to remove it?"))
	{

		  $.ajax({

			url:"exam_subject_projectaction.php",

			method:"POST",

			data:{id:id, action:'delete'},

			success:function(data)
			{

				  $('#message').html(data);

				  dataTable.ajax.reload();

				  setTimeout(function(){

					$('#message').html('');

				  }, 5000);

			}

		  })

	}

  });

});
</script>