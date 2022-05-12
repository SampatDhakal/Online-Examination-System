<?php

//oea.php

class oea
{
	public $base_url = 'http://localhost/examination/main/';
	public $connect;
	public $query;
	public $statement;
	public $now;

	function oea()
	{
		$this->connect = new PDO("mysql:host=localhost;dbname=oea", "root", "");

		date_default_timezone_set('Asia/Kathmandu');

		session_start();

		$this->now = date("Y-m-d H:i:s",  STRTOTIME(date('h:i:sa')));
	}

	function execute($data = null)
	{
		$this->statement = $this->connect->prepare($this->query);
		if($data)
		{
			$this->statement->execute($data);
		}
		else
		{
			$this->statement->execute();
		}		
	}

	function row_count()
	{
		return $this->statement->rowCount();
	}

	function statement_result()
	{
		return $this->statement->fetchAll();
	}

	function get_result()
	{
		return $this->connect->query($this->query, PDO::FETCH_ASSOC);
	}

	

	function is_login()
	{
		if(isset($_SESSION['user_id']))
		{
			return true;
		}
		return false;
	}

	function is_master_user()
	{
		if(isset($_SESSION['user_id']))
		{
			if($_SESSION["user_id"] == '1')
			{
				return true;
			}
			return false;
		}
		return false;
	}


	function is_student_login()
	{
		if(isset($_SESSION['student_id']))
		{
			return true;
		}
		return false;
	}

	function clean_input($string)
	{
	  	$string = trim($string);
	  	$string = stripslashes($string);
	  	$string = htmlspecialchars($string);
	  	return $string;
	}

	function Get_class_name($class_id)
	{
		$this->query = "
		SELECT class_name FROM class_oea 
		WHERE class_id = '$class_id'
		";
		$result = $this->get_result();
		foreach($result as $row)
		{
			return $row["class_name"];
		}
	}

	function Check_subject_already_added_in_exam($exam_id, $subject_id)
	{
		$this->query = "
		SELECT exam_subject_id FROM subject_wise_exam_detail 
		WHERE exam_id = '$exam_id' 
		AND subject_id = '$subject_id'
		";

		$this->execute();

		if($this->row_count() > 0)
		{
			return true;
		}
		return false;
	}

	function Check_subject_already_added_in_pexam($exam_id, $subject_id)
	{
		$this->query = "
		SELECT exam_subject_id FROM subject_wise_exam_projectdetail 
		WHERE exam_id = '$exam_id' 
		AND subject_id = '$subject_id'
		";

		$this->execute();

		if($this->row_count() > 0)
		{
			return true;
		}
		return false;
	}

	function Get_exam_name($exam_id)
	{
		$this->query = "
		SELECT exam_title FROM exam_oea 
		WHERE exam_id = '$exam_id'
		";
		$result = $this->get_result();
		foreach($result as $row)
		{
			return $row["exam_title"];
		}
	}

	function Get_pexam_name($exam_id)
	{
		$this->query = "
		SELECT exam_title FROM pexam_oea 
		WHERE exam_id = '$exam_id'
		";
		$result = $this->get_result();
		foreach($result as $row)
		{
			return $row["exam_title"];
		}
	}

	function Get_exam_duration($exam_id)
	{
		$this->query = "
		SELECT exam_duration FROM exam_oea 
		WHERE exam_id = '$exam_id'
		";
		$result = $this->get_result();
		foreach($result as $row)
		{
			return $row["exam_duration"];
		}
	}

	function Get_pexam_duration($exam_id)
	{
		$this->query = "
		SELECT exam_duration FROM pexam_oea 
		WHERE exam_id = '$exam_id'
		";
		$result = $this->get_result();
		foreach($result as $row)
		{
			return $row["exam_duration"];
		}
	}

	function Get_question_option_data($exam_subject_question_id, $option_number)
	{
		$this->query = "
		SELECT question_option_title FROM question_option_oea 
		WHERE exam_subject_question_id = '$exam_subject_question_id' 
		AND question_option_number = '$option_number'
		";
		$result = $this->get_result();
		foreach($result as $row)
		{
			return $row['question_option_title'];
		}
	}

	function Can_add_question_in_this_subject($exam_subject_id)
	{
		$this->query = "
		SELECT subject_total_question FROM subject_wise_exam_detail 
		WHERE exam_subject_id = '$exam_subject_id'
		";

		$allow_question = 0;

		$result = $this->get_result();
		foreach($result as $row)
		{
			$allow_question = $row["subject_total_question"];
		}

		$this->query = "
		SELECT * FROM exam_subject_question_oea 
		WHERE exam_subject_id = '$exam_subject_id'
		";

		$this->execute();

		$total_question = $this->row_count();

		if($total_question >= $allow_question)
		{
			return false;
		}

		return true;
	}


	function Get_Class_subject($class_id)
	{
		$this->query = "
		SELECT subject_name FROM subject_oea 
		WHERE class_id = '$class_id' 
		AND subject_status = 'Enable'
		";
		$result = $this->get_result();
		$data = array();
		foreach($result as $row)
		{
			$data[] = $row["subject_name"];
		}
		return $data;
	}

	function Get_user_name($user_id)
	{
		$this->query = "
		SELECT * FROM user_oea 
		WHERE user_id = '".$user_id."'
		";
		$result = $this->get_result();
		foreach($result as $row)
		{
			if($row['user_type'] != 'Master')
			{
				return $row["user_name"];
			}
			else
			{
				return 'Master';
			}
		}
	}

	function Get_Subject_name($subject_id)
	{
		$this->query = "
		SELECT subject_name FROM subject_oea 
		WHERE subject_id = '$subject_id'
		";
		$result = $this->get_result();
		foreach($result as $row)
		{
			return $row["subject_name"];
		}
	}

	function Get_student_question_answer_option($exam_subject_question_id, $student_id)
	{
		$this->query = "
		SELECT student_answer_option FROM exam_subject_question_answer 
		WHERE exam_subject_question_id = '".$exam_subject_question_id."' 
		AND student_id = '".$student_id."'
		";
		$result = $this->get_result();
		foreach($result as $row)
		{
			return $row["student_answer_option"];
		}
	}

	function Get_question_answer_option($question_id)
	{
		$this->query = "
		SELECT exam_subject_question_answer FROM exam_subject_question_oea 
		WHERE exam_subject_question_id = '".$question_id."' 
		";
		$result = $this->get_result();
		foreach($result as $row)
		{
			return $row["exam_subject_question_answer"];
		}
	}

	function Get_question_right_answer_mark($exam_subject_id)
	{
		$this->query = "
		SELECT marks_per_right_answer FROM subject_wise_exam_detail 
		WHERE exam_subject_id = '".$exam_subject_id."' 
		";
		$result = $this->get_result();
		foreach($result as $row)
		{
			return $row["marks_per_right_answer"];
		}
	}
	function Get_question_wrong_answer_mark($exam_subject_id)
	{
		$this->query = "
		SELECT marks_per_wrong_answer FROM subject_wise_exam_detail 
		WHERE exam_subject_id = '".$exam_subject_id."' 
		";
		$result = $this->get_result();
		foreach($result as $row)
		{
			return $row["marks_per_wrong_answer"];
		}
	}

	function Get_exam_id($exam_code)
	{
		$this->query = "
		SELECT exam_id FROM exam_oea 
		WHERE exam_code = '$exam_code'
		";

		$result = $this->get_result();

		foreach($result as $row)
		{
			return $row['exam_id'];
		}
	}

	function Get_pexam_id($exam_code)
	{
		$this->query = "
		SELECT exam_id FROM pexam_oea 
		WHERE exam_code = '$exam_code'
		";

		$result = $this->get_result();

		foreach($result as $row)
		{
			return $row['exam_id'];
		}
	}

	function Get_exam_subject_id($exam_subject_code)
	{
		$this->query = "
		SELECT exam_subject_id FROM subject_wise_exam_detail 
		WHERE subject_exam_code = '$exam_subject_code'
		";

		$result = $this->get_result();

		foreach($result as $row)
		{
			return $row['exam_subject_id'];
		}
	}

	function Get_pexam_subject_id($exam_subject_code)
	{
		$this->query = "
		SELECT exam_subject_id FROM subject_wise_exam_projectdetail 
		WHERE subject_exam_code = '$exam_subject_code'
		";

		$result = $this->get_result();

		foreach($result as $row)
		{
			return $row['exam_subject_id'];
		}
	}

	function send_email($receiver_email, $subject, $body)
	{
		$mail = new PHPMailer;

		$mail->IsSMTP();

		$mail->Host = 'smtp.gmail.com'; 

		$mail->Port = '587';

		$mail->SMTPAuth = true;

		$mail->Username = 'sampatdhakal15@gmail.com'; 

		$mail->Password = 'computer15'; 

		$mail->SMTPSecure = 'tls';

		$mail->From = 'info@Onlineexainationapplication.info';
		
		$mail->FromName = 'info@Onlineexaminationapplication.info';

		$mail->AddAddress($receiver_email, '');

		$mail->WordWrap = 50;      
		
		$mail->IsHTML(true);

		$mail->Subject = $subject;

		$mail->Body = $body;

		$mail->Send();
	}

	
	function Get_total_classes()
	{
		$this->query = "
		SELECT COUNT(class_id) as Total 
		FROM class_oea 
		WHERE class_status = 'Enable'
		";
		$result = $this->get_result();
		foreach($result as $row)
		{
			return $row["Total"];
		}
	}

	function Get_total_subject()
	{
		$this->query = "
		SELECT COUNT(subject_id) as Total 
		FROM subject_oea 
		WHERE subject_status = 'Enable'
		";
		$result = $this->get_result();
		foreach($result as $row)
		{
			return $row["Total"];
		}
	}

	function Get_total_student()
	{
		$this->query = "
		SELECT COUNT(student_id) as Total 
		FROM student_oea 
		WHERE student_status = 'Enable'
		";
		$result = $this->get_result();
		foreach($result as $row)
		{
			return $row["Total"];
		}
	}

	function Get_total_exam()
	{
		$this->query = "
		select sum(totalexam.total) as totall
		from
   			(
   				select count(exam_id) as total from exam_oea
   				UNION ALL
   				select count(*) as total from pexam_oea
   			) totalexam;
		";
		$result = $this->get_result();
		foreach($result as $row)
		{
			return $row["totall"];
		}
	}

	function Get_total_result()
	{
		$this->query = "
		select sum(totalexam.total) as totall
		from
   			(
   				select count(exam_id) as total from exam_oea Where exam_result_datetime != '0000-00-00 00:00:00'
   				UNION ALL
   				select count(*) as total from pexam_oea Where exam_result_datetime != '0000-00-00 00:00:00'
   			) totalexam; 
		";
		$result = $this->get_result();
		foreach($result as $row)
		{
			return $row["totall"];
		}
	}

}


?>