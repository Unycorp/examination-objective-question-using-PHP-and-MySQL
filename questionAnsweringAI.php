<?php
include '../connection/dbconnect.php';

//Get total scores or mark
// This usually occurs when the student click the submit button or if the time counter runs out.
if(isset($_POST['submit_id']) && isset($_POST['student_id']) && isset($_POST['course_id'])){

	$student_id = $_POST['student_id'];


	$course_id = $_POST['course_id'];


	// Answer the recent question before submitting the result

	$studentmark = 0;

	//This is the student table.
  	$query = "SELECT * FROM answers WHERE student_id='$student_id' AND score_status='correct'";
	$result = mysqli_query($conn, $query);
	if(mysqli_num_rows($result)>0){
	while($row = mysqli_fetch_array($result)){
		$mark = $row['mark'];

		$studentmark = $studentmark + $mark;
		}
	}


	// This is the grade table
		//This is the student table.
  	$query2 = "SELECT * FROM grade WHERE student_id='$student_id' AND course_id='$course_id' LIMIT 1";
	$result2 = mysqli_query($conn, $query2);
	if(mysqli_num_rows($result2)>0){
		$query3 = "UPDATE grade SET exam_score='$studentmark' WHERE student_id='$student_id' AND course_id='$course_id' LIMIT 1";
		$result3 = mysqli_query($conn, $query3);

		if($result3){
				// Delete every transaction that occurs on the student table
		$query5 = "DELETE FROM answers WHERE student_id='$student_id' AND course_id='$course_id'";
		$result5 = mysqli_query($conn, $query5);
			if($result5){
				echo true;
			}
	
		}
		
	}else{
	$query4 = "INSERT INTO grade (student_id, course_id, exam_score) VALUES ('$student_id', '$course_id', '$studentmark')";
	$result4 = mysqli_query($conn, $query4);

		// Delete every transaction that occurs on the student table
	if($result4){
		$query6 = "DELETE FROM answers WHERE student_id='$student_id' AND course_id='$course_id'";
		$result6 = mysqli_query($conn, $query6);
			if($result6){
				echo true;
			}
		}
	
	}



}

// Answering of questions
if(isset($_POST['student_id']) && isset($_POST['course_id']) && isset($_POST['question_number']) && isset($_POST['answer'])){


$student_id = $_POST['student_id'];

$course_id = $_POST['course_id'];

$question_number = $_POST['question_number'];

$answer = $_POST['answer'];

	$question_id = "";

  	$query = "SELECT * FROM questions WHERE course_id='$course_id' AND question_number='$question_number' LIMIT 1";
	$result = mysqli_query($conn, $query);
	if(mysqli_num_rows($result)>0){
		while($row = mysqli_fetch_array($result)){
		$question_id = $row['question_id'];
		}
	}

	// check if the question has been answered.
  	$query1 = "SELECT * FROM answers WHERE student_id='$student_id' AND question_id='$question_id' LIMIT 1";
	$result1 = mysqli_query($conn, $query1);
	if(mysqli_num_rows($result1)>0){
		// if yes, update the student table
		$query2 = "UPDATE answers SET answer='$answer' WHERE student_id='$student_id' AND question_id='$question_id' LIMIT 1";
		$result2 = mysqli_query($conn, $query2);
		}else{
			$answer_id = uniqid();
			// if no, insert the answer into the student table
			$query3 = "INSERT INTO answers (answer_id, student_id, question_id, course_id, question_number, answer) VALUES ('$answer_id', '$student_id', '$question_id', '$course_id', '$question_number', '$answer')";
			$result3 = mysqli_query($conn, $query3);
		}

// Whether updating or inserting, you should be mark immediately.
// Remember that there are two tables. One contain the questions and answer. The other contains the answers and mark. Simply put as teacher (question) table and student (answer) table.

///////////////////////////////////////////////////////////////
 //Marking the question Answered (Scoring you) ////////////////////////////////////////////////// /////////
// Getting the answer from the question
$teacher_answer = "";
$question_mark = "";
// This is the teacher table. Where the real answer lies. Each teacher must assign a particular mark to each questions.
  	$query4 = "SELECT * FROM questions WHERE question_id='$question_id' LIMIT 1";
	$result4 = mysqli_query($conn, $query4);
	if(mysqli_num_rows($result4)>0){
	while($row = mysqli_fetch_array($result4)){
		$teacher_answer = $row['answer'];
		$question_mark = $row['mark'];
		}
	}

	$student_answer = "";
	// Getting your own answer from where you insert
	// This is the student table where the student answers lies
  	$query5 = "SELECT * FROM answers WHERE student_id='$student_id' AND course_id='$course_id' AND question_id='$question_id' LIMIT 1";
	$result5 = mysqli_query($conn, $query5);
	if(mysqli_num_rows($result5)>0){
	while($row = mysqli_fetch_array($result5)){
		$student_answer = $row['answer'];
		}
	}

	if($teacher_answer == $student_answer){
		// because some question can carry 5 marks
		// here update the sudent table
		$query6 = "UPDATE answers SET score_status='correct', mark='$question_mark' WHERE student_id='$student_id' AND course_id='$course_id' AND question_id='$question_id' LIMIT 1";
		$result6 = mysqli_query($conn, $query6);
	}else{
		$query7 = "UPDATE answers SET score_status='wrong', mark='0' WHERE student_id='$student_id' AND course_id='$course_id' AND question_id='$question_id' LIMIT 1";
		$result7 = mysqli_query($conn, $query7);
	}


}


if(isset($_POST['ref_id']) && isset($_POST['question_number']) && isset($_POST['student_id']) && isset($_POST['course_id'])){

$question_number = $_POST['question_number'];
$student_id = $_POST['student_id'];
$course_id = $_POST['course_id'];


//Getting the total number of questions
  	$query1 = "SELECT * FROM questions WHERE course_id='$course_id'";
	$result1 = mysqli_query($conn, $query1);
	$num = mysqli_num_rows($result1);
//Getting a particular question from the list of all questions
		$file_id = "";
		$mime_type = "";
  	$query = "SELECT * FROM questions WHERE question_number='$question_number' AND course_id='$course_id'  LIMIT 1";
	$result = mysqli_query($conn, $query);
	if(mysqli_num_rows($result)>0){
	while($row = mysqli_fetch_array($result)){

		$question = $row['question'];
		$optionA = $row['optionA'];
		$optionB = $row['optionB'];
		$optionC = $row['optionC'];
		$optionD = $row['optionD'];
		$file_id = $row['file_id'];
		$mime_type = $row['mime_type'];

		$question_number = $row['question_number'];

		$next_question_number = $question_number + 1;
		$previous_question_number = $question_number - 1;

		if($next_question_number >= $num){
			$next_question_number = $num;
		}
		
		if($previous_question_number <= 1){
			$previous_question_number = 1;
		}
//Getting the answer to a particular question numbers
		$student_answer = "";
  	$query3 = "SELECT * FROM answers WHERE question_number='$question_number' AND course_id='$course_id' AND student_id='$student_id' LIMIT 1";
	$result3 = mysqli_query($conn, $query3);
	if(mysqli_num_rows($result3)>0){
	while($row = mysqli_fetch_array($result3)){
		$student_answer = $row['answer'];
		}
	}

	$tickA=$tickB=$tickC=$tickD="";
	if($student_answer=="A"){
		$tickA = "checked";
	}else if ($student_answer=="B") {
		$tickB = "checked";
	}else if ($student_answer=="C") {
		$tickC = "checked";
	}else if ($student_answer=="D") {
		$tickD = "checked";
	}
		echo "
		<div style='display:inline-block; text-align:left; margin-bottom:100px; width:550px;'>
		<h3 style='font-family: roboto, sans-serif; font-weight: 400; padding-top:65px; paddig-left: 5px; padding-right:5px; color: #000024; font-size:18px' class='animated slideInDown'>
		<span style='font-size:20px'>$question_number</span>. $question</h3>
		
		<div>";
		if($mime_type=="image/png" || $mime_type=="image/jpg"){
			echo "<div style='width:550px'>
			<img src='../file/images/questions/$file_id' style='width:100%; margin-bottom: 20px;'>
			</div>";
		}else if($mime_type=="video/mp4"){
			echo "<div>
			<video src='../file/videos/questions/$file_id' controls autoplay>
			</div>";
		}
	echo "</div>

	<div style='text-align:left; width:550px; padding: 10px;'>
	<ul class='myquestionlist' style='color: #000024; font-size:18px; font-family: roboto, sans-serif'>";
		echo "<li>
			<input type='radio' name='option' value='A' $tickA class='myradio'> 
			A<span class='fa fa-angle-double-right' style='color:darkorange'></span> $optionA
			</li>
			<li>
			<input type='radio' name='option' value='B' $tickB class='myradio'> 
			B<span class='fa fa-angle-double-right'></span> $optionB
			</li>
			<li>
			<input type='radio' name='option' value='C' $tickC class='myradio'> 
			C<span class='fa fa-angle-double-right' style='color:darkgreen'></span> $optionC
			</li>
			<li>
			<input type='radio' name='option' value='D' $tickD class='myradio'> 
			D<span class='fa fa-angle-double-right' style='color:red'></span> $optionD
			</li>";
	echo "</ul>
	</div>
	
	<div style='float:left; margin-left:30px; margin-bottom:20px'>
	<button style='border: 0; cursor: pointer;' class='btn-fill gradient-accent' onclick='previousQuestion($question_number, $previous_question_number)'><i class='fas fa-arrow-circle-left'></i> Prev</button>
	</div>

	<div style='float:right; margin-right:30px;'>
	<button style='border: 0; cursor: pointer;' class='btn-fill gradient-accent' onclick='nextQuestion($question_number, $next_question_number)'>Next<i class='fas fa-arrow-circle-right'></i></button>
	</div>
 </div>

	<div style='position:fixed; bottom:0; width:100%; height:auto; background:#fff; padding:10px; text-align:left; z-index:19'>";

	$query4 = "SELECT * FROM questions WHERE  course_id='$course_id'";
	$result4 = mysqli_query($conn, $query4);
	if(mysqli_num_rows($result4)>0){
	while($row = mysqli_fetch_array($result4)){
		$question_number = $row['question_number'];

	$student_answer = "";
  	$query3 = "SELECT * FROM answers WHERE question_number='$question_number' AND course_id='$course_id' AND student_id='$student_id' LIMIT 1";
	$result3 = mysqli_query($conn, $query3);
	if(mysqli_num_rows($result3)>0){
	while($row = mysqli_fetch_array($result3)){
		$student_answer = $row['answer'];
		}
	}
	if($student_answer=="A" || $student_answer=="B" || $student_answer=="C" || $student_answer=="D"){
		$color = "gradient-accent";
	}else{
		$color = "gradient-primary";
	}
	echo "<button class='btn btn-fill-me $color' onclick='refresh($question_number)'>$question_number</button>";
			}
		}
	echo "</div>";
		}
	}

}
?>