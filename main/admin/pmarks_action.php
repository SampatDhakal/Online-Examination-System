<?php

//subject_action.php

include('oea.php');

$object = new oea();

if(isset($_POST["action"]))
{
	if($_POST["action"] == 'fetch')
	{
		$order_column = array('student_id', 'student_name', 'student_roll_no', 'subject_name', 'exam_title', 'subject_full_marks', 'file_name', 'uploaded_on','marks');

		$output = array();

		$main_query = "
		SELECT * FROM panswerdetail 
		";

		$search_query = '';

		if(isset($_POST["search"]["value"]))
		{
			$search_query .= 'WHERE student_id LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR student_name LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR student_roll_no LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR subject_name LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR exam_title LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR subject_full_marks LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR file_name LIKE "%'.$_POST["search"]["value"].'%" ';
            $search_query .= 'OR uploaded_on LIKE "%'.$_POST["search"]["value"].'%" ';
            $search_query .= 'OR marks LIKE "%'.$_POST["search"]["value"].'%" ';
		}

		if(isset($_POST["order"]))
		{
			$order_query = 'ORDER BY '.$order_column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$order_query = 'ORDER BY id DESC ';
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
			$sub_array[] = $row["student_id"];
            $sub_array[] = html_entity_decode($row["student_name"]);
			$sub_array[] = html_entity_decode($row["student_roll_no"]);
			$sub_array[] = html_entity_decode($row["subject_name"]);
			$sub_array[] = $row["exam_title"];
			$sub_array[] = $row["subject_full_marks"];
            $sub_array[] = '<a href="uploads/'.$row["file_name"].' ">'.$row["file_name"].'</a>';
			$sub_array[] = $row["uploaded_on"];
			$sub_array[] = $row["marks"];
			$sub_array[] = '
			<div align="center">
			<button type="button" name="edit_button" class="btn btn-warning btn-circle btn-sm edit_button" data-id="'.$row["id"].'"><i class="fas fa-edit"></i></button>
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
}



	if($_POST["action"] == 'fetch_single')
	{
		$object->query = "
		SELECT * FROM panswerdetail 
		WHERE id = '".$_POST["id"]."'
		";

		$result = $object->get_result();

		$data = array();

		foreach($result as $row)
		{   $data['id'] = $row['id'];
            $data['marks'] = $row['marks'];

		}

		echo json_encode($data);
	}

	if($_POST["action"] == 'Edit')
	{
		$error = '';

		$success = '';

		$data = array(
            ':marks'		=>	$_POST["marks"],
			':id'			=>	$_POST['hidden_id']
		);

		$object->query = "
		SELECT * panswerdetail 
		WHERE marks = :marks 
		AND id != :id
		";

		$object->execute($data);

		if($object->row_count() > 0)
		{
			$error = '<div class="alert alert-danger">Student Data Already Exists</div>';
		}
		else
		{
			

			$data = array(
				':marks'			=>	$_POST["marks"],
				':id'			=>	$_POST['hidden_id']
			);

			$object->query = "
			UPDATE panswerdetail 
			SET marks = :marks 
			WHERE id = :id
			";

			$object->execute($data);

			$success = '<div class="alert alert-success">Student Marks Updated</div>';
			
		}

		$output = array(
			'error'		=>	$error,
			'success'	=>	$success
		);

		echo json_encode($output);

    }



?>