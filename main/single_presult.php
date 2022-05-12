<?php

//single_subject_result.php

include('admin/oea.php');

require_once 'class/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

$object = new oea();

if(isset($_GET['ec'], $_GET['esc']))
{
	$exam_id = $object->Get_pexam_id($_GET['ec']);

	$exam_subject_id = $object->Get_pexam_subject_id($_GET['esc']);

	$object->query = "
	SELECT * FROM panswerdetail 
	WHERE exam_id = '$exam_id' 
    AND student_id = '".$_SESSION["student_id"]."' 
	AND exam_subject_id = '$exam_subject_id' 
	";

	$result = $object->get_result();
	
	$output = '
		<h3 align="center">Exam Result</h3>
		<table width="100%" border="1" cellpadding="5" cellspacing="0">
			<tr>
				<th>Subject name</th>
				<th>Exam name</th>
				<th>Full marks</th>
				<th>Obtain marks</th>
			</tr>
		';


	foreach($result as $row)
	{
		$subject_name = $row["subject_name"];
        $exam_title = $row["exam_title"];
        $subject_full_marks = $row["subject_full_marks"];
        $marks = $row["marks"];

		

		$output .= '
			<tr>
				<td>'.$subject_name.'</td>
				<td>'.$exam_title.'</td>
				<td>'.$subject_full_marks.'</td>
				<td>'.$marks.'</td>
			</tr>
			';

		
	}



	$output .= '</table>';

	// echo $output;

	$dompdf = new domPdf();
	$dompdf->set_paper('letter', 'landscape');
	$file_name = 'Exam Result.pdf';
	$dompdf->loadHtml($output);
	$dompdf->render();
	$dompdf->stream($file_name, array("Attachment" => false));
	exit(0);

}

?>