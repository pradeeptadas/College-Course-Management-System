<?php session_start(); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php include("includes/student_dashboard_up.php"); ?>

		<td id="page">
			
			<?php	
				if(isset($_SESSION['User_id']))
				{
					$user_id = mysql_escape_string($_SESSION["User_id"]);
					$user_type = mysql_escape_string($_SESSION["User_type"]); 	
				}
				else
				{
					echo "Session variables can't be retrieved";
				}
				
				if(strcmp($user_type,"Student")==0)
				{
					$sql = "SELECT * FROM student where Student_id = '$user_id' "; 
				}

				$result = mysql_query($sql,$conn);
				$row = mysql_fetch_array($result, MYSQL_ASSOC);
				echo "<h2> Welcome ".$row['Firstname']."</h2>";
			?>

			
			<?php
				if(isset($_GET['notify']))
				{
					echo "notify pressed";
				}
				elseif(isset($_GET['current_courses']))
				{
					 
					$student_id = mysql_escape_string($_SESSION['User_id']);
					$sql = "SELECT * FROM Course WHERE Course_id IN (SELECT Course_id FROM enrolled_in WHERE Student_id='$student_id') ";
			        $retval = mysql_query( $sql, $conn );
			        if(! $retval ) 
			        {
			        	die('Could not get data: ' . mysql_error());
			        }

			        while($row = mysql_fetch_array($retval, MYSQL_ASSOC)) 
			        {
			            echo "Course_id : {$row['Course_id']} <br> ". 
			            "Course Name :{$row['Course_name']}  <br> ".
			            "Start_date : {$row['Start_date']} <br> ".
			            "Duration : {$row['Duration']} <br> ".
			            "Department : {$row['Department']} <br> ";
			             
			            echo "<a href='view_lecture.php?"."courseid=".$row['Course_id']."'"."> Lectures </a></br>";
			            echo "<a href='view_assignment.php?"."courseid=".$row['Course_id']."'"."> Assignments </a>";
			            echo "</br>--------------------------------<br>";
			        }
				
				}
				elseif (isset($_GET['course_catalog'])) 
				{
					?>
					<form action="" method="post">
				        <div class="form-group">
				        <label for="examplecourse">Username</label>
				        <input type="number" name="courseid" class="form-control" id="examplecourse" placeholder="courseid" required>

				        <button type="submit" class="btn btn-info" name="register">Register Course</button>

				        </div>
				    </form>
					<?php

						$student_id = mysql_escape_string($_SESSION['User_id']);

				        echo "Enter the Course_id of course to register <br><br>";

				        $sql = "SELECT * FROM Course WHERE Course_id NOT IN (SELECT Course_id FROM enrolled_in WHERE Student_id='$student_id') ";
				        mysql_select_db('coursemng');
				        $retval = mysql_query( $sql, $conn );

				        if(! $retval ) 
				        {
				        	die('Could not get data: ' . mysql_error());
				        }

				        $max_courseid= -999;

				        while($row = mysql_fetch_array($retval, MYSQL_ASSOC)) 
				        {
					        echo "Course_id : {$row['Course_id']} <br> ". 
					        "Course Name :{$row['Course_name']}  <br> ".
					        "Start_date : {$row['Start_date']} <br> ".
					        "Duration : {$row['Duration']} <br> ".
					        "Department : {$row['Department']} <br> ".
					        "--------------------------------<br>";
					        if($max_courseid < $row['Course_id'])
					        {
					     		$max_courseid = $row['Course_id'];
					        }
				        }

				        if(isset($_POST['register']))
				        {
					        $course_id = mysql_escape_string($_POST['courseid']);

					        if($course_id <= $max_courseid)
					        {
						        $sql = "INSERT INTO enrolled_in SET Course_id='$course_id', Student_id='$student_id' ";

						        $retval = mysql_query( $sql, $conn );

						        if(! $retval )
						        {
							        die('Could not enroll in course: ' . mysql_error());
						        }
						        else
							        echo "Course registered successfully";
					        }
					        else
					        {
						        echo "Enter a valid course_id <br>";
					        }

				        }
				}
				elseif(isset($_GET['perform']))
				{
					echo "perform pressed";
				}
			?>
			
			
		</td>
<?php include("includes/student_dashboard_down.php"); ?>

<?php 
	if(isset($conn))
	{
		mysql_close($conn);	
	}
?>
