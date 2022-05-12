<?php

//exam_action.php

include('oea.php');

$object = new oea();

if(isset($_POST["action"]))
{
	if($_POST["action"] == 'fetch')
	{
		$order_column = array('pexam_oea.exam_title', 'subject_oea.subject_name', 'subject_wise_exam_projectdetail.subject_exam_datetime', 'subject_wise_exam_projectdetail.subject_full_marks', 'subject_wise_exam_projectdetail.subject_exam_task');

		$output = array();

		$main_query = "
		SELECT * FROM subject_wise_exam_projectdetail 
		INNER JOIN pexam_oea 
		ON pexam_oea.exam_id = subject_wise_exam_projectdetail.exam_id 
		INNER JOIN subject_oea 
		ON subject_oea.subject_id = subject_wise_exam_projectdetail.subject_id 
		";

		$search_query = '';

		if(isset($_POST["search"]["value"]))
		{
			$search_query .= 'WHERE pexam_oea.exam_title LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR subject_oea.subject_name LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR subject_wise_exam_projectdetail.subject_exam_datetime LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR subject_wise_exam_projectdetail.subject_full_marks LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR subject_wise_exam_projectdetail.subject_exam_task LIKE "%'.$_POST["search"]["value"].'%" ';
		}

		if(isset($_POST["order"]))
		{
			$order_query = 'ORDER BY '.$order_column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$order_query = 'ORDER BY subject_wise_exam_projectdetail.exam_subject_id DESC ';
		}

		$limit_query = '';

		if($_POST["length"] != -1)
		{
			$limit_query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}

		$object->query = $main_query . $search_query . $order_query;

		$object->execute();

		$filtered_rows = $object->row_count();

		$object->query .= $limit_query;

		$result = $object->get_result();

		$object->query = $main_query;

		$object->execute();

		$total_rows = $object->row_count();

		$data = array();

		foreach($result as $row)
		{
			$sub_array = array();
			$sub_array[] = html_entity_decode($row["exam_title"]);
			$sub_array[] = html_entity_decode($row["subject_name"]);
			$sub_array[] = $row["subject_exam_datetime"];
			$sub_array[] = $row["subject_full_marks"] . ' Marks';
			$sub_array[] = $row["subject_exam_task"];


			$sub_array[] = '
			<div align="center">
			<button type="button" name="edit_button" class="btn btn-warning btn-circle btn-sm edit_button" data-id="'.$row["exam_subject_id"].'"><i class="fas fa-edit"></i></button>
			&nbsp;
			<button type="button" name="delete_button" class="btn btn-danger btn-circle btn-sm delete_button" data-id="'.$row["exam_subject_id"].'"><i class="fas fa-times"></i></button>
			</div>
			';
			$data[] = $sub_array;
		}

		$output = array(
			"draw"    			=> 	intval($_POST["draw"]),
			"recordsTotal"  	=>  $total_rows,
			"recordsFiltered" 	=> 	$filtered_rows,
			"data"    			=> 	$data
		);
			
		echo json_encode($output);

	}

	if($_POST['action'] == 'fetch_subject')
	{
		$object->query = "
		SELECT subject_oea.subject_id, subject_oea.subject_name 
		FROM pexam_oea 
		INNER JOIN subject_to_class_oea 
		ON subject_to_class_oea.class_id = pexam_oea.exam_class_id 
		INNER JOIN subject_oea 
		ON subject_oea.subject_id = subject_to_class_oea.subject_id 
		WHERE pexam_oea.exam_id = '".$_POST["exam_id"]."' 
		ORDER BY subject_oea.subject_id ASC";

		$result = $object->get_result();
		$html = '<option value="">Select Subject</option>';
		foreach($result as $row)
		{
			if(!$object->Check_subject_already_added_in_pexam($_POST["exam_id"], $row['subject_id']))
			{
				$html .= '<option value="'.$row['subject_id'].'">'.$row['subject_name'].'</option>';
			}
		}
		echo $html;
	}

	if($_POST["action"] == 'Add')
	{
		$error = '';

		$success = '';
		
		$data = array(
			':exam_id'					=>	$_POST["exam_id"],
			':subject_id'				=>	$_POST["subject_id"],
			':subject_full_marks'	    =>	$_POST["subject_full_marks"],
			':subject_exam_task'	    =>	$_POST["subject_exam_task"],
			':subject_exam_datetime'	=>	$_POST["subject_exam_datetime"],
			':subject_exam_code'		=>	md5(uniqid())
		);

		$object->query = "
		INSERT INTO subject_wise_exam_projectdetail 
		(exam_id, subject_id, subject_full_marks, subject_exam_task, subject_exam_datetime, subject_exam_code) 
		VALUES (:exam_id, :subject_id, :subject_full_marks, :subject_exam_task, :subject_exam_datetime, :subject_exam_code)
		";

		$object->execute($data);

		$success = '<div class="alert alert-success">Subject Added in <b>'.$object->Get_exam_name($_POST["exam_id"]).'</b> Class</div>';

		$output = array(
			'error'		=>	$error,
			'success'	=>	$success
		);

		echo json_encode($output);

	}

	if($_POST["action"] == 'fetch_single')
	{
		$object->query = "
		SELECT * FROM subject_wise_exam_projectdetail 
		WHERE exam_subject_id = '".$_POST["exam_subject_id"]."'
		";

		$result = $object->get_result();

		$data = array();

		foreach($result as $row)
		{
			$data['exam_id'] = $row['exam_id'];
			$data['subject_id'] = $row['subject_id'];
			$data['subject_full_marks'] = $row['subject_full_marks'];
			$data['subject_exam_task'] = $row['subject_exam_task'];
			$data['subject_exam_datetime'] = $row['subject_exam_datetime'];
			$data['subject_select_box'] = '<option value="">Select Subject</option><option value="'.$row['subject_id'].'">'.$object->Get_Subject_name($row['subject_id']).'</option>';;
		}

		echo json_encode($data);
	}

	if($_POST["action"] == 'Edit')
	{
		$error = '';

		$success = '';

		$data = array(
			':subject_full_marks'	=>	$_POST["subject_full_marks"],
			':subject_exam_task'	=>	$_POST["subject_exam_task"],

			':subject_exam_datetime'	=>	$_POST["subject_exam_datetime"]
		);

		$object->query = "
		UPDATE subject_wise_exam_projectdetail 
		SET subject_full_marks = :subject_full_marks,
		subject_exam_task = :subject_exam_task,  
		subject_exam_datetime = :subject_exam_datetime    
		WHERE exam_subject_id = '".$_POST['hidden_id']."'
		";

		$object->execute($data);

		$success = '<div class="alert alert-success">Exam Subject Data Updated</div>';
		
		$output = array(
			'error'		=>	$error,
			'success'	=>	$success
		);

		echo json_encode($output);

	}

	if($_POST["action"] == 'delete')
	{
		$object->query = "
		DELETE FROM subject_wise_exam_projectdetail 
		WHERE exam_subject_id = '".$_POST["id"]."'
		";

		$object->execute();

		echo '<div class="alert alert-success">Exam Subject Data Deleted</div>';
	}
}

?>