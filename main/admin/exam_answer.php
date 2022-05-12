<?php

//student.php

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


// $object->query = "
// SELECT * FROM class_oea 
// WHERE class_status = 'Enable' 
// ORDER BY class_name ASC
// ";

// $result = $object->get_result();

?>

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Assign Marks To Submitted Labworks</h1>

                    <!-- DataTales Example -->
                    <span id="message"></span>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                        	<div class="row">
                            	<div class="col">
                            		<h6 class="m-0 font-weight-bold text-primary">Labwork Submitted List</h6>
                            	</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="marks_table" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Student ID</th>
                                            <th>Student Name</th>
                                            <th>Student Roll</th>
                                            <th>Subject Name</th>
                                            <th>Exam Title</th>
                                            <th>Subject Full Marks</th>
                                            <th>File</th>
                                            <th>Uploaded on</th>
                                            <th>Marks</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                <?php
                include('footer.php');
                ?>

<div id="studentModal" class="modal fade">
  	<div class="modal-dialog">
    	<form method="post" id="student_form" enctype="multipart/form-data">
      		<div class="modal-content">
        		<div class="modal-header">
          			<h4 class="modal-title" id="modal_title">Give Marks</h4>
          			<button type="button" class="close" data-dismiss="modal">&times;</button>
        		</div>
        		<div class="modal-body">
        			<span id="form_message"></span>
		          	<div class="form-group">
		          		<label>marks</label>
		          		<input type="number" name="marks" id="marks" class="form-control"/>
		          	</div>
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
$(document).ready(function(){

    // $('#student_dob').datepicker({
    //     format: "yyyy-mm-dd",
    //     autoclose: true
    // });

	var dataTable = $('#marks_table').DataTable({
		"processing" : true,
		"serverSide" : true,
		"order" : [],
		"ajax" : {
			url:"pmarks_action.php",
			type:"POST",
			data:{action:'fetch'}
		},
		"columnDefs":[
			{
				"targets":[9],
				"orderable":false,
			},
		],
	});

    

    // $('#add_student').click(function(){
        
    //     $('#student_form')[0].reset();

    //     $('#student_form').parsley().reset();

    //     $('#modal_title').text('Add Student');

    //     $('#action').val('Add');

    //     $('#submit_button').val('Add');

    //     $('#studentModal').modal('show');

    //     $('#form_message').html('');

    //     $('#student_image').attr('required', 'required');

    //     $('#student_uploaded_image').html('');

    // });

    // $('#student_image').change(function(){
    //     var extension = $('#student_image').val().split('.').pop().toLowerCase();
    //     if(extension != '')
    //     {
    //         if(jQuery.inArray(extension, ['gif','png','jpg','jpeg']) == -1)
    //         {
    //             alert("Invalid Image File");
    //             $('#student_image').val('');
    //             return false;
    //         }
    //     }
    // });

	$('#student_form').parsley();

	$('#student_form').on('submit', function(event){
		event.preventDefault();
		if($('#student_form').parsley().isValid())
		{		
			$.ajax({
				url:"pmarks_action.php",
				method:"POST",
				data:new FormData(this),
				dataType:'json',
                contentType:false,
                processData:false,
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
						$('#studentModal').modal('hide');
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

		var id = $(this).data('id');

		$('#student_form').parsley().reset();

        $('#student_form')[0].reset();

		$('#form_message').html('');

        // $('#student_uploaded_image').html('');

		$.ajax({

	      	url:"pmarks_action.php",

	      	method:"POST",

	      	data:{id:id, action:'fetch_single'},

	      	dataType:'JSON',

	      	success:function(data)
	      	{

	        	$('#marks').val(data.marks);

	        	$('#modal_title').text('Give Marks');

	        	$('#action').val('Edit');

	        	$('#submit_button').val('Edit');

	        	$('#studentModal').modal('show');

	        	$('#hidden_id').val(id);

	      	}

	    })

	});

	// $(document).on('click', '.status_button', function(){
	// 	var id = $(this).data('id');
    // 	var status = $(this).data('status');
	// 	var next_status = 'Enable';
	// 	if(status == 'Enable')
	// 	{
	// 		next_status = 'Disable';
	// 	}
	// 	if(confirm("Are you sure you want to "+next_status+" it?"))
    // 	{

    //   		$.ajax({

    //     		url:"student_action.php",

    //     		method:"POST",

    //     		data:{id:id, action:'change_status', status:status, next_status:next_status},

    //     		success:function(data)
    //     		{

    //       			$('#message').html(data);

    //       			dataTable.ajax.reload();

    //       			setTimeout(function(){

    //         			$('#message').html('');

    //       			}, 5000);

    //     		}

    //   		})

    // 	}
	// });

	/*$(document).on('click', '.delete_button', function(){

    	var id = $(this).data('id');

    	if(confirm("Are you sure you want to remove it?"))
    	{

      		$.ajax({

        		url:"student_action.php",

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

  	});*/

});
</script>