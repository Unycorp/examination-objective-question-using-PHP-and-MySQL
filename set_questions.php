<?php 
//include '../connection/dbconnect.php';
include 'securitysystem.php';


$course_id = "";
if(isset($_GET['cid'])){
  $course_id = $_GET['cid'];
}


$course_title = "";
$course_code = "";
$query = "SELECT * FROM courses WHERE course_id='$course_id' LIMIT 1";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)>0){
  while($row = mysqli_fetch_array($result)){
    $course_title = $row['course_title'];
    $course_code = $row['course_code'];
  }
}

 ?>
<!DOCTYPE html>
<html lang="en">
<head>

    <title>Edu Portal</title>
    
    <?php include "csslibraries.php" ?>
     
  </head>

<body class="hold-transition light-skin sidebar-mini theme-primary fixed">
	
<div class="wrapper">
	<div id="loader"></div>
	
  <header class="main-header">
	<?php include 'header.php'; ?>
  </header>
  
  <aside class="main-sidebar">
    <!-- sidebar-->
    <?php include "sidebar_left.php" ?>
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
	  <div class="container-full">
		<!-- Main content -->
		<section class="content">
			<div class="container-fluid">
          <div class="row page-titles mx-0">
            <div class="col-sm-9 p-md-0 mb-40">
                <div class="welcome-text">
                  <h4>Set Questions</h4>
                  <span>The preview button enables you to make changes to already set questions</span>
                </div>
              </div>
                <div class="col-sm-3 p-md-0">
                  <div class="text-right">
                    <a href="preview.php?cid=<?php echo $course_id ?>" class="btn btn-rounded btn-outline-info">Preview</a>
                  </div>
                </div>
            </div>
        </div>

        <div class="row">
          <div class="col-sm-2"></div>
          <div class="col-sm-7">
            <div class="card">
              <div class="card-header border-0 pb-0">
                  <h5 class="card-title">Set <?php echo $course_title." ($course_code)" ?> Examination Questions</h5>
              </div>
              <div class="card-body">
                <label for="test_type" class="form-label">Select Test Type</label>
                <select class="form-select" id="test_type">
                  <option value="first_test">First Test</option>
                  <option value="second_test">Second Test</option>
                  <option value="exam">EXAM</option>
                </select>
                <br>
                <hr>
                <div class="form-group">
                  <label>Question</label>
                  <textarea id="question" placeholder="ask your question here" class="form-control" style="height: 100px"></textarea>
                </div>

                <div class="form-group">
                  <label>Give one correct option and three wrong options</label>
                  <div class="input-group mb-3 input-info-o">
                    <input type="text" id="optionA" placeholder="option A" class="form-control input-rounded">
                  </div>
                </div>
                <div class="form-group">
                  <div class="input-group mb-3 input-info-o">
                    <input type="text" id="optionB" placeholder="option B" class="form-control input-rounded">
                  </div>
                </div>
                <div class="form-group">
                  <div class="input-group mb-3 input-info-o">
                    <input type="text" id="optionC" placeholder="option C" class="form-control input-rounded">
                  </div>
                </div>
                <div class="form-group">
                  <div class="input-group mb-3 input-info-o">
                    <input type="text" id="optionD" placeholder="option D" class="form-control input-rounded">
                  </div>
                </div>
                <div class="form-group">
                  <label>If possible, upload a multimedia content that describe more about the question being asked</label>
                  <div class="input-group mb-3 input-warning-o">
                    <input type="file" id="attachment" class="form-control">
                  </div>
                </div>
                <p>What is the answer to the question.</p>
                <!-- <div class="form-group text-center">
                  <input type="radio" name="option" value="A"><span>A</span>
                  <input type="radio" name="option" value="B" style="width: 22px; height: 22px"><span style="position: relative; top:-4px; font-size: 20px; margin-right: 20px; margin-left: 5px">B</span>
                  <input type="radio" name="option" value="C" style="width: 22px; height: 22px"><span style="position: relative; top:-4px; font-size: 20px; margin-right: 20px; margin-left: 5px">C</span>
                  <input type="radio" name="option" value="D" style="width: 22px; height: 22px"><span style="position: relative; top:-4px; font-size: 20px; margin-right: 20px; margin-left: 5px">D</span>
                </div>-->
                <div class="demo-radio-button">
                  <input type="radio" id="radio_A" class="with-gap radio-col-danger" name="option" value="A" />
                  <label for="radio_A">A</label>
                  <input type="radio" id="radio_B" class="with-gap radio-col-danger" name="option" value="B" />
                  <label for="radio_B">B</label>
                  <input type="radio" id="radio_C" class="with-gap radio-col-danger" name="option" value="C" />
                  <label for="radio_C">C</label>
                  <input type="radio" id="radio_D" class="with-gap radio-col-danger" name="option" value="D" />
                  <label for="radio_D">D</label>
                </div>
                <div class="form-group">
                  <label>Mark given to this question</label>
                  <div class="input-group mb-3 input-success-o">
                    <input type="text" id="question_mark" placeholder="how many mark for this question" value="1" class="form-control">
                  </div>
                </div>
                <div style="text-align: right; padding: 20px">
                  <button onclick="addQuestions()" class="btn btn-outline-info btn-primary">Add Questions</button>
                </div>
              </div>
            </div>
          </div>
        <div class="col-sm-3"></div>
        
      </div>
			
		</section>
		<!-- /.content -->
	  </div>
  </div>
  <!-- /.content-wrapper -->
	
	<?php include 'footer.php'; ?>
  <!-- Side panel -->   	
</div>
<!-- ./wrapper -->
	
<?php include 'chat.php'; ?>
	<!-- Page Content overlay -->
	
	
<?php include 'jslibraries.php'; ?>
<script type="text/javascript">


var _staff_id = "<?php echo $staff_id ?>";
var _course_id = "<?php echo $course_id ?>";


	function addQuestions(){
    var test_type = document.getElementById('test_type').value;
		var question = document.getElementById('question').value;
		var optionA = document.getElementById('optionA').value;
		var optionB = document.getElementById('optionB').value;
		var optionC = document.getElementById('optionC').value;
		var optionD = document.getElementById('optionD').value;


		var option = document.getElementsByName('option');


		for(var i=0; i<option.length; i++){
			if(option[i].checked){
				option = option[i].value;
			}
		}

      var file = document.getElementById("attachment").files[0];
      var question_mark = document.getElementById("question_mark").value;
    
    
    if(file==undefined){
      var mime_type = "text";
      file = new File([""], "filename");
    }else{
		  var mime_type = file.type;
    }

  var data = new FormData();
  var ajax = new XMLHttpRequest();

  data.append("mime_type", mime_type);
  data.append("file", file);

  data.append("question_mark", question_mark);
  data.append("question", question);
  data.append("answer", option);


  data.append("optionA", optionA);
  data.append("optionB", optionB);
  data.append("optionC", optionC);
  data.append("optionD", optionD);

  data.append("staff_id", _staff_id);
  data.append("course_id", _course_id);
  data.append("test_type", test_type);

 ajax.open("post", "set_questionsAI.php", true);
 ajax.send(data);

 ajax.onreadystatechange = function() {
            if (ajax.readyState == 4 && ajax.status == 200) {
            var result = ajax.responseText;
            alert(result);
            //var div = document.getElementById("show_id");
            //div.innerHTML = result;
            if(result > 0){
              refresh(result);
            	speakSuccess("Added Successfully");
             }
            }
        }

	}
</script>
</body>
</html>
