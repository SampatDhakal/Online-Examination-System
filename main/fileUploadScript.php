<?php



include('admin/oea.php');

$object = new oea();

if(!$object->is_student_login())
{
    header("location:".$object->base_url."");
}

$exam_id = '';
$exam_title = '';
$class_id = '';
$exam_duration = '';

$subject_id = '';
$exam_subject_id = '';
$subject_full_marks = '';
$subject_exam_task = '';
$subject_exam_start_time = '';
$subject_exam_end_time = '';
$remaining_minutes = '';
$subject_exam_status = '';

$student_name = '';
$student_roll_no = '';
$student_image = '';

if(isset($_SESSION['ec']))
{
    $object->query = "
    SELECT * FROM pexam_oea 
    WHERE exam_code = '".$_SESSION['ec']."';
    ";

    $result = $object->get_result();

    foreach($result as $row)
    {
        $exam_id = $row["exam_id"];
        $exam_title = $row["exam_title"];
        $class_id = $row["exam_class_id"];
        $exam_duration = $row["exam_duration"];
    }
}
else
{
    header('location:view_pexam.php');
}

if(isset($_SESSION["esc"]))
{
    $object->query = "
    SELECT * FROM subject_wise_exam_projectdetail
    WHERE subject_exam_code = '".$_SESSION['esc']."';
    ";

    $result = $object->get_result();

    foreach($result as $row)
    {
        $subject_id = $row["subject_id"];
        $exam_subject_id = $row["exam_subject_id"];
        $subject_full_marks = $row["subject_full_marks"];
        $subject_exam_task = $row["subject_exam_task"];
        $subject_exam_start_time = $row["subject_exam_datetime"];
        $subject_exam_end_time = strtotime($subject_exam_start_time . '+' . $exam_duration . ' minute');
        $subject_exam_end_time = date('Y-m-d H:i:s', $subject_exam_end_time);
        $total_second = strtotime($subject_exam_end_time) - strtotime($subject_exam_start_time);
        $exam_subject = $object->Get_Subject_name($subject_id);
        $remaining_minutes = strtotime($subject_exam_end_time) - time();
        $subject_exam_status = $row["subject_exam_status"];
    }
}
else
{
    header('location:view_pexam.php');
}

$object->query = "
SELECT student_oea.student_name, student_oea.student_image, student_to_class_oea.student_roll_no FROM student_to_class_oea 
INNER JOIN student_oea 
ON student_oea.student_id = student_to_class_oea.student_id 
WHERE student_to_class_oea.student_id = '".$_SESSION["student_id"]."' 
ORDER BY student_to_class_oea.student_to_class_id DESC 
LIMIT 1 
";

$result = $object->get_result();
foreach($result as $row)
{
    $student_name = $row["student_name"];
    $student_roll_no = $row["student_roll_no"];
    $student_image = str_replace("../", "", $row["student_image"]);
}

$statusMsg = '';
// File upload path
$targetDir = "admin/uploads/";
$fileName = basename($_FILES["file"]["name"]);
$targetFilePath = $targetDir . $fileName;
$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

if(isset($_POST["submit"]) && !empty($_FILES["file"]["name"])){
    // Allow certain file formats
    $allowTypes = array('jpg','PNG','jpeg','gif','pdf','docx','doc','png','rar','zip');
    if(in_array($fileType, $allowTypes)){
        // Upload file to server
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
            // Insert image file name into database
            // $insert = $object->query = ("INSERT into imagess (file_name, uploaded_on) VALUES ('".$fileName."', NOW())");
           $insert = $object->query = ("INSERT into panswerdetail (student_id,exam_id,exam_subject_id,student_name,student_roll_no,subject_name,exam_title,subject_full_marks,file_name, uploaded_on,marks) VALUES ('".$_SESSION["student_id"]."','".$exam_id."','".$exam_subject_id."','".$student_name."','".$student_roll_no."','".$exam_subject."','".$exam_title."','".$subject_full_marks."','".$fileName."', NOW(),'NOT ASSIGN')");
            $object->execute();
            if($insert){
                $statusMsg = "The file ".$fileName. " has been uploaded successfully.";
            }else{
                $statusMsg = "File upload failed, please try again.";
            } 
        }else{
            $statusMsg = "Sorry, there was an error uploading your file.";
        }
    }else{
        $statusMsg = 'Sorry, only JPG, JPEG, PNG, GIF, & PDF files are allowed to upload.';
    }
}else{
    $statusMsg = 'Please select a file to upload.';
}


// Display status message
echo $statusMsg;


?>