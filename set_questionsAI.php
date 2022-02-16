<?php
include '../connection/dbconnect.php';

if(isset($_POST['question_mark']) && isset($_POST['question']) && isset($_POST['optionA']) && isset($_POST['optionB']) && isset($_POST['optionC']) && isset($_POST['optionD']) && isset($_POST['answer']) && isset($_POST['mime_type']) && isset($_FILES['file']) && isset($_POST['staff_id']) && isset($_POST['test_type']) && isset($_POST['course_id'])){

	$question_mark = $_POST['question_mark'];
	$question = $_POST['question'];
	$optionA = $_POST['optionA'];
	$optionB = $_POST['optionB'];
	$optionC = $_POST['optionC'];
	$optionD = $_POST['optionD'];

	$answer = $_POST['answer'];
	$mime_type = $_POST['mime_type'];

	$test_type = $_POST['test_type'];
	$course_id = $_POST['course_id'];
	$staff_id = $_POST['staff_id'];

	$question_id = uniqid(); 

	$post_id = $question_id;

	// firstly, we get the question number. If not found, make it question 1 but if found make it question number 2
	$question_number = "";
  	$query = "SELECT * FROM questions WHERE staff_id='$staff_id' AND course_id='$course_id' AND test_type='$test_type' ORDER BY id DESC LIMIT 1";
	$result = mysqli_query($conn, $query);
	if(mysqli_num_rows($result)>0){
	while($row = mysqli_fetch_array($result)){
		$question_number = $row['question_number'];

		$question_number = $question_number + 1;
		}
	}else{
		$question_number = 1;
	}
	// Mime type can be ordinary text or with multimedia content. For ordinary text do the below code
	if($mime_type=="text"){

			$query = "INSERT INTO questions (question_number, mark, question, optionA, optionB, optionC, optionD, answer, mime_type, file_id, staff_id, test_type, course_id, question_id) 

			VALUES ('$question_number', '$question_mark', '$question', '$optionA', '$optionB', '$optionC', '$optionD', '$answer', '$mime_type', 'none', '$staff_id', '$test_type', '$course_id','$question_id')";
			$result = mysqli_query($conn, $query);
			if($result){
				echo true;
			}
			
			
	}else{
		// for multimedia (videos, images, etc), do the following code
		// there is a file
		$file_path = "images/exams/";

    	$filename = $_FILES['file']['name'];


    	$unique_file_name = $post_id.$filename;

    	$file_tmp_name = $_FILES['file']['tmp_name'];


    	$target_file = $file_path.basename($unique_file_name);
    	if(move_uploaded_file($file_tmp_name, $target_file)){
			$query = "INSERT INTO questions (question_number, mark, question, optionA, optionB, optionC, optionD, answer, mime_type, file_id, staff_id, test_type, course_id, question_id) 

			VALUES ('$question_number', '$question_mark', '\"$question\"', '$optionA', '$optionB', '$optionC', '$optionD', '$answer', '$mime_type', '$unique_file_name', '$staff_id', '$test_type', '$course_id','$question_id')";
			$result = mysqli_query($conn, $query);
			if($result){
				echo $question_number;
			}
		}
		
	}

		
}
?>