<?php

include '../connection/dbconnect.php';

$student_id = "sid";
$course_id = "cid";

//Getting Question number 1.
$question_number="";
$query2 = "SELECT * FROM questions WHERE course_id='$course_id' LIMIT 1";
	$result2 = mysqli_query($conn, $query2);
	if(mysqli_num_rows($result2)>0){
	while($row = mysqli_fetch_array($result2)){
		$question_number = $row['question_number'];
			}
		}
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/flaticon.css">
        <?php include 'csslibraries.php';?>
        <style type="text/css">
   body{
    overflow-y: scroll;
    overflow-x: hidden;
   }
   .btn-fill{
     padding: 5px 10px;
   }
  .btn-fill-me{
  margin: 3px;
  border: 0; 
  cursor: pointer;
  color: #ffffff;
  border-radius: 4px;
  -webkit-transition: all 0.3s ease-out;
  -moz-transition: all 0.3s ease-out;
  -ms-transition: all 0.3s ease-out;
  -o-transition: all 0.3s ease-out;
  transition: all 0.3s ease-out;
    }
.btn-fill-me:hover:after {
  visibility: visible;
  opacity: 1;
  width: 100%;
}
ul{
    display:inline-block; 
    text-align:left
}
ul li{
   color: black; 
}

.myradio{
    min-width: 20px;
    min-height: 20px;
}
.myquestionlist li{
font-size: 19px;
display: flex;
align-items: center;
margin: 10px;
color: #5a5a5a;
}
.myquestionlist li span{
color: #07249e;
font-size: 16px;
margin-right: 25px;
}

.header{
    width: 100%;
    height: 60px;
    background: #0383ff;
    display: flex;
    flex-direction: row;
    align-items: center;
}
.profile-pic{
text-align: center;
padding-top: 18px;
}
.profile-pic img{
        width: 150px;
    height: 150px;
    border-radius: 50%;
    padding: 2px;
  background: linear-gradient(to bottom, darkorange, darkorange, red, yellow);
    box-shadow: 0px 1px 10px 0px rgba(0, 0, 0, 0.6);
}
.submit-btn-holder{
    text-align: center;
}
</style>
</head>
<body>
<div class="header">
<button class="sidebar-btn fa fa-menu" onclick="showLeftSideBar()">Left</button>    
</div>


<div class="overlay-bg" onclick="hideLeftSideBar()"></div>
<div class="leftsidebar">
   <div class="profile-pic">
       <img src="../images/2.jpg">
   </div>
   <div style="text-align: center;">
       <p>Name of the person</p>
       <p>Department</p>
       <p>Course Area</p>
   </div>
   <div class="submit-btn-holder">
       <button onclick="submitQuestions()">Submit Exams</button>
   </div>    
</div>


<img src="../images/icon/ic1.png" style="position: fixed; z-index: -1; right: 0; top:15px;">

<div style="text-align: center;">
    <div id="show_questions">

    </div>
</div>

<!--
<button onclick="submitQuestions()">Submit</button>
-->
<script type="text/javascript">

var _student_id = "<?php echo $student_id ?>";
var _course_id = "<?php echo $course_id ?>";
var _question_number = "<?php echo $question_number ?>";

refresh(_question_number);

var qn = 1;
function nextQuestion(question_number, next_question_number){
refresh(next_question_number);
answerQuestion(question_number);
qn = next_question_number;
}

function previousQuestion(question_number, previous_question_number){
refresh(previous_question_number);
answerQuestion(question_number);
qn = previous_question_number;
}

// change it later to 1sec i.e 1000 in order to improve computer speed 
var timer = setInterval(function(){
	var radio = document.getElementsByName("option");
for(var i=0; i<radio.length; i++){
if(radio[i].checked){
	answerQuestion(qn);
}
}
},5000);


function submitQuestions(){
var data = new FormData();
var ajax = new XMLHttpRequest();


 data.append("submit_id", "anything");
 data.append("student_id",_student_id);
 data.append("course_id",_course_id);
 ajax.open("post", "questionAnsweringAI.php", true);
 ajax.send(data);

 ajax.onreadystatechange = function() {
            if (ajax.readyState == 4 && ajax.status == 200) {
            var result = ajax.responseText;
            if(result){
            	clearInterval(timer);
            	// navigate the student away from this page to the home or login page
            	window.location.href = "index.php";
            }
            //alert(result);
            //var div = document.getElementById("show_questions");
            //div.innerHTML = result;
            //if(result){
            //	alert("Added Successfully");
             //}
            }
        }
}


function answerQuestion(question_number){
var data = new FormData();
var ajax = new XMLHttpRequest();

var answer = document.getElementsByName('option');

for(var i=0; i<answer.length; i++){
	if(answer[i].checked){
		answer = answer[i].value;
	}
}

 data.append("question_number", question_number);
 data.append("student_id",_student_id);
 data.append("course_id",_course_id);
  data.append("answer", answer);

 ajax.open("post", "questionAnsweringAI.php", true);
 ajax.send(data);

 ajax.onreadystatechange = function() {
            if (ajax.readyState == 4 && ajax.status == 200) {
            var result = ajax.responseText;
            //var div = document.getElementById("show_questions");
            //div.innerHTML = result;
            //if(result){
            //	alert("Added Successfully");
             //}
            }
        }
	
}

//Refresh is done through the question number
function refresh(question_number){
var data = new FormData();
var ajax = new XMLHttpRequest();
data.append("ref_id", "something");
 data.append("question_number", question_number);
 data.append("student_id",_student_id);
 data.append("course_id",_course_id);

 ajax.open("post", "questionAnsweringAI.php", true);
 ajax.send(data);

 ajax.onreadystatechange = function() {
            if (ajax.readyState == 4 && ajax.status == 200) {
            var result = ajax.responseText;
            var div = document.getElementById("show_questions");
            div.innerHTML = result;
            //if(result){
            //	alert("Added Successfully");
             //}
            }
        }
}	
</script>

<script type="text/javascript">
    var overlayBg = document.getElementsByClassName('overlay-bg')[0];
    var leftsidebar = document.getElementsByClassName('leftsidebar')[0];

showLeftSideBar();

    function showLeftSideBar(){
        overlayBg.setAttribute("class", "animated slideInLeft");
        overlayBg.style.position = "absolute";
        overlayBg.style.top = "0";
        overlayBg.style.left = "0";
        overlayBg.style.width = "100%";
        overlayBg.style.height = "100vh";
        overlayBg.style.zIndex = "20";
        overlayBg.style.background = "#616161";
        overlayBg.style.opacity = "0.2";
        overlayBg.style.animationDuration = "0.8s";
        overlayBg.style.webkitAnimationDuration = "0.8s";

        leftsidebar.setAttribute("class", "animated slideInLeft");
        leftsidebar.style.position = "fixed";
        leftsidebar.style.width = "250px";
        leftsidebar.style.height = "100vh";
        leftsidebar.style.top = "60px";
        leftsidebar.style.left = "0";
        leftsidebar.style.background = "white";
        leftsidebar.style.zIndex = "100";
        leftsidebar.style.boxShadow = "0 1px 10px 0px rgba(0,0,0,0.5)";
    }
    function hideLeftSideBar(){
        overlayBg.setAttribute("class", "animated slideInRight");
        overlayBg.style.left = "-100%";
    
        leftsidebar.setAttribute("class", "animated slideInRight");
        leftsidebar.style.left="-250px";
    }
</script>
</body>
</html>