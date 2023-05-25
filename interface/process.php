<?php
session_start();
// initializing variables
$name = "";
$surname = "";
$email = "";
$password = "";
$errors = array();

// connect to the database
$db = mysqli_connect('localhost', 'timefadetool', 'QJJHpr8qFrR1aCbk', 'timefadetool');

// REGISTER INSTRUCTOR
if (isset($_POST['reg_user'])) {
    $inst_username = mysqli_real_escape_string($db, $_POST['inst_username']);
    $user_check_query = "SELECT * FROM instructors WHERE instructor_username='$inst_username' LIMIT 1";
    $result = mysqli_query($db, $user_check_query);
    $user = mysqli_fetch_assoc($result);
    if ($user) {
        if ($user['instructor_username'] === $inst_username) {
            function_alert("Instructor already exists!");
        }
    } else {
        $inst_email = mysqli_real_escape_string($db, $_POST['email']);
    $user_check_query = "SELECT * FROM instructors WHERE instructor_username='$inst_email' LIMIT 1";
    $result = mysqli_query($db, $user_check_query);
    $user = mysqli_fetch_assoc($result);
    if ($user) {
        if ($user['instructor_username'] === $inst_username) {
            function_alert("Email already used!");
        }
    } else {
        if (count($errors) == 0) {
            $name = mysqli_real_escape_string($db, $_POST['name']);
            $surname = mysqli_real_escape_string($db, $_POST['surname']);
            $email = mysqli_real_escape_string($db, $_POST['email']);
            $passwordReal = mysqli_real_escape_string($db, $_POST['password']);
            $inst_username = mysqli_real_escape_string($db, $_POST['inst_username']);
            $admin_username = $_SESSION['username'];
            $password = hash("sha512", $passwordReal);
            $query = "INSERT INTO instructors (name, surname, email, instructor_password, instructor_username, admin_username, first_login) 
                    VALUES('$name', '$surname', '$email', '$password', '$inst_username', '$admin_username', '1')";
            mysqli_query($db, $query);
            function_alert("Instructor registered!");
        }
    }
}
}

// CREATE PASSWORD
if (isset($_POST['create_password'])) {
    $passwordReal = mysqli_real_escape_string($db, $_POST['password1']);

    if (count($errors) == 0) {
        if($_SESSION['inst'] == 1){
            $user_username = $_SESSION['username'];
            $password = hash("sha512", $passwordReal);
            $query = "UPDATE instructors SET instructor_password='$password', first_login='0' WHERE instructor_username='$user_username'";
            mysqli_query($db, $query);
            header('location: instructor/instructor.php');
        }
        else {
                if($_SESSION['admin'] == 1){
                    $user_username = $_SESSION['username'];
                    $password = hash("sha512", $passwordReal);
                    $query = "UPDATE admins SET admin_password='$password', first_login='0' WHERE admin_username='$user_username'";
                    mysqli_query($db, $query);
                    header('location: admin/admin.php');
                 }
        }
    }
}

// CHANGE PASSWORD
if (isset($_POST['change_password'])) {
    $passwordReal = mysqli_real_escape_string($db, $_POST['password1']);
    $current_passwordReal = mysqli_real_escape_string($db, $_POST['current_password']);
    $current_password = hash("sha512", $current_passwordReal);
    $user_username = $_SESSION['username'];
    $password = hash("sha512", $passwordReal);

    if (count($errors) == 0) {
        if($_SESSION['inst'] == 1){
            $query = "SELECT * FROM instructors WHERE instructor_username='$user_username' AND instructor_password='$current_password'";
            $results = mysqli_query($db, $query);
            $user = $results->fetch_assoc();
            if (mysqli_num_rows($results) == 1) {
                $query = "UPDATE instructors SET instructor_password='$password' WHERE instructor_username='$user_username'";
                mysqli_query($db, $query);
                function_alert("Password changed!");
            }
            else{
                function_alert("Given current password is incorrect!");
            }
        }
        else {
                if($_SESSION['admin'] == 1){
                    $query = "SELECT * FROM admins WHERE admin_username='$user_username' AND admin_password='$current_password'";
                    $results = mysqli_query($db, $query);
                    $user = $results->fetch_assoc();
                    if (mysqli_num_rows($results) == 1) {
                        $query = "UPDATE admins SET admin_password='$password' WHERE admin_username='$user_username'";
                        mysqli_query($db, $query);
                        function_alert("Password changed!");
                 }
                 else{
                    function_alert("Given current password is incorrect!");
                }
            }
        }
    }
}

// ADD ADMIN
if (isset($_POST['reg_admin'])) {
    $new_admin_username = mysqli_real_escape_string($db, $_POST['add_admin_username']);
    $user_check_query = "SELECT * FROM admins WHERE admin_username='$new_admin_username' LIMIT 1";
    $result = mysqli_query($db, $user_check_query);
    $user = mysqli_fetch_assoc($result);
    if ($user) {
        function_alert("Admin already exists!");
    } else {
        if (count($errors) == 0) {
            $passwordReal = mysqli_real_escape_string($db, $_POST['password']);
            $new_admin_username = mysqli_real_escape_string($db, $_POST['add_admin_username']);
            $loggedin_admin_username = $_SESSION['username'];
            $password = hash("sha512", $passwordReal);
            $query = "INSERT INTO admins (admin_username, admin_password, first_login) 
                    VALUES('$new_admin_username', '$password', '1')";
            mysqli_query($db, $query);
            function_alert("Admin Added!");
        }
    }
}

// REMOVE COURSE
if (isset($_POST['remove_course'])) {
    $course_code = mysqli_real_escape_string($db, $_POST['rem_course_code']);
    $user_check_query = "SELECT * FROM courses WHERE course_code='$course_code' LIMIT 1";
    $result = mysqli_query($db, $user_check_query);
    if (mysqli_num_rows($result) == 1) {
        if (count($errors) == 0) {
            $q1 = "DELETE parallel FROM courses JOIN parallel ON courses.course_code = parallel.course_code1 WHERE courses.course_code= '$course_code'";
            mysqli_query($db, $q1);
            $q2 = "DELETE parallel FROM courses JOIN parallel ON courses.course_code = parallel.course_code2 WHERE courses.course_code = '$course_code'";
            mysqli_query($db, $q2);
            $q3 = "DELETE conflicts FROM courses JOIN conflicts ON courses.course_code = conflicts.course_code1 WHERE courses.course_code = '$course_code'";
            mysqli_query($db, $q3);
            $q4 = "DELETE conflicts FROM courses JOIN conflicts ON courses.course_code = conflicts.course_code2 WHERE courses.course_code = '$course_code'";
            mysqli_query($db, $q4);
            $q5 = "DELETE FROM courses WHERE courses.course_code = '$course_code'";
            mysqli_query($db, $q5);

            $q1 = "DELETE parallel FROM courses JOIN parallel ON courses.course_code = parallel.course_code1 WHERE courses.course_code LIKE '$course_code%'";
            mysqli_query($db, $q1);
            $q2 = "DELETE parallel FROM courses JOIN parallel ON courses.course_code = parallel.course_code2 WHERE courses.course_code  LIKE '$course_code%'";
            mysqli_query($db, $q2);
            $q3 = "DELETE conflicts FROM courses JOIN conflicts ON courses.course_code = conflicts.course_code1 WHERE courses.course_code LIKE '$course_code%'";
            mysqli_query($db, $q3);
            $q4 = "DELETE conflicts FROM courses JOIN conflicts ON courses.course_code = conflicts.course_code2 WHERE courses.course_code  LIKE '$course_code%'";
            mysqli_query($db, $q4);
            $q5 = "DELETE FROM courses WHERE courses.course_code LIKE '$course_code%'";
            mysqli_query($db, $q5);
            function_alert("Course removed!");
        }
    } else {
        function_alert("Course does not exist!");
    }
}

// REMOVE INSTRUCTOR
if (isset($_POST['remove_inst'])) {
    $inst_username = mysqli_real_escape_string($db, $_POST['rem_inst_username']);
    $user_check_query = "SELECT * FROM instructors WHERE instructor_username='$inst_username' LIMIT 1";
    $result = mysqli_query($db, $user_check_query);
    if (mysqli_num_rows($result) == 1) {
        if (count($errors) == 0) {
            $q1 = "DELETE parallel FROM courses JOIN parallel ON courses.course_code = parallel.course_code1 WHERE courses.instructor_username = '$inst_username'";
            mysqli_query($db, $q1);
            $q2 = "DELETE parallel FROM courses JOIN parallel ON courses.course_code = parallel.course_code2 WHERE courses.instructor_username = '$inst_username'";
            mysqli_query($db, $q2);
            $q3 = "DELETE conflicts FROM courses JOIN conflicts ON courses.course_code = conflicts.course_code1 WHERE courses.instructor_username = '$inst_username'";
            mysqli_query($db, $q3);
            $q4 = "DELETE conflicts FROM courses JOIN conflicts ON courses.course_code = conflicts.course_code2 WHERE courses.instructor_username = '$inst_username'";
            mysqli_query($db, $q4);
            $q5 = "DELETE FROM courses WHERE courses.instructor_username = '$inst_username'";
            mysqli_query($db, $q5);
            $q6 = "DELETE FROM instructors_unable_hours WHERE instructors_unable_hours.instructor_username = '$inst_username'";
            mysqli_query($db, $q6);
            $q7 = "DELETE FROM instructors_unable_hours_wednesdays WHERE instructors_unable_hours_wednesdays.instructor_username = '$inst_username'";
            mysqli_query($db, $q7);
            $q8 = "DELETE FROM instructors WHERE instructors.instructor_username = '$inst_username'";
            mysqli_query($db, $q8);

            function_alert("Instructor removed!");
        }
    } else {
        function_alert("Instructor does not exist!");
    }
}

// LOGIN USER
if (isset($_POST['login_user'])) {
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $passwordReal = mysqli_real_escape_string($db, $_POST['password']);
    $password = hash("sha512", $passwordReal);

    if (count($errors) == 0) {
        $query = "SELECT * FROM instructors WHERE instructor_username='$username' AND instructor_password='$password'";
        $results = mysqli_query($db, $query);
        $user = $results->fetch_assoc();
        if (mysqli_num_rows($results) == 1) {
            $_SESSION['username'] = $username;
            $_SESSION['inst'] = 1;
            if($user['first_login'] == 1){
                header('location: create_password.php');
            }
            else
                header('location: instructor/instructor.php');
        } else {
            $query = "SELECT * FROM admins WHERE admin_username='$username' AND admin_password='$password'";
            $results = mysqli_query($db, $query);
            $user = $results->fetch_assoc();
            if (mysqli_num_rows($results) == 1) {
                $_SESSION['username'] = $username;
                $_SESSION['admin'] = 1;
                if($user['first_login'] == 1){
                    header('location: create_password.php');
                }
                else
                    header('location: admin/admin.php');
            } else {
                function_alert("Wrong credentials!");

            }
        }
    }
}

// ADD COURSE
if (isset($_POST['add_course'])) {
    $course_code = mysqli_real_escape_string($db, $_POST['course_code']);
    $query = "SELECT * FROM courses WHERE course_code='$course_code' LIMIT 1";
    $result = mysqli_query($db, $query);
    $user = mysqli_fetch_assoc($result);
    $query = "SELECT * FROM additional_info LIMIT 1";
    $result = mysqli_query($db, $query);
    $state = mysqli_fetch_assoc($result);
    
    if(!$state){
        function_alert("Cant add course untill Admin initialises information!");
    }
    else
    {
        $lab_capacity = $state['labratories_capacity'];
        if ($user) {
            function_alert("Course already exists!");
        } else {
            if (count($errors) == 0) {
                $course_type = mysqli_real_escape_string($db, $_POST['course_type']);
                $max_students = mysqli_real_escape_string($db, $_POST['max_students']);
                $loggedin_instructor_username = $_SESSION['username'];
                $query = "";
                if ($course_type == 1) {
                    $query = "INSERT INTO courses (course_code, max_students, course_type, instructor_username) 
                        VALUES('$course_code', '$max_students', '1', '$loggedin_instructor_username')";
                    mysqli_query($db, $query);
                } elseif ($course_type == 2) {
                    $query = "INSERT INTO courses (course_code, max_students, course_type, instructor_username) 
                        VALUES('$course_code', '$max_students', '1', '$loggedin_instructor_username');";
                    mysqli_query($db, $query);
                    $query = "INSERT INTO courses (course_code, max_students, course_type, instructor_username) 
                        VALUES('" . $course_code . "_T', '$max_students', '3', '$loggedin_instructor_username')";
                    mysqli_query($db, $query);
                } elseif ($course_type == 3) {
                    $query = "INSERT INTO courses (course_code, max_students, course_type, instructor_username) 
                        VALUES('$course_code', '$max_students', '1', '$loggedin_instructor_username');";
                    mysqli_query($db, $query);
                    $query = "INSERT INTO courses (course_code, max_students, course_type, instructor_username) 
                        VALUES('" . $course_code . "_T', '$max_students', '3', '$loggedin_instructor_username');";
                    mysqli_query($db, $query);
                    $num_labs = ceil($max_students / $lab_capacity);
                    $query = "INSERT INTO courses (course_code, max_students, course_type, num_of_labs) 
                        VALUES('" . $course_code . "_L', '$max_students', '2', '$num_labs')";
                    mysqli_query($db, $query);
                } elseif ($course_type == 4) {
                    $query = "INSERT INTO courses (course_code, max_students, course_type, instructor_username) 
                        VALUES('$course_code', '$max_students', '1', '$loggedin_instructor_username');";
                    mysqli_query($db, $query);
                    $query = "INSERT INTO courses (course_code, max_students, course_type, instructor_username) 
                        VALUES('" . $course_code . "_T', '$max_students', '3', '$loggedin_instructor_username');";
                    mysqli_query($db, $query);
                    $num_labs = ceil($max_students / $lab_capacity);
                    $query = "INSERT INTO courses (course_code, max_students, course_type, num_of_labs) 
                        VALUES('" . $course_code . "_L', '$max_students', '4', '$num_labs')";
                    mysqli_query($db, $query);
                }elseif ($course_type == 5) {
                    $query = "INSERT INTO courses (course_code, max_students, course_type, instructor_username) 
                        VALUES('$course_code', '$max_students', '5', '$loggedin_instructor_username');";
                    mysqli_query($db, $query);
                } elseif ($course_type == 6) {
                    $query = "INSERT INTO courses (course_code, max_students, course_type, instructor_username) 
                    VALUES('$course_code', '$max_students', '1', '$loggedin_instructor_username');";
                    mysqli_query($db, $query);
                    $num_labs = ceil($max_students / $lab_capacity);
                    $query = "INSERT INTO courses (course_code, max_students, course_type, num_of_labs) 
                    VALUES('" . $course_code . "_L', '$max_students', '2', '$num_labs')";
                    mysqli_query($db, $query);
                }
                elseif ($course_type == 7) {
                    $query = "INSERT INTO courses (course_code, max_students, course_type, instructor_username) 
                    VALUES('$course_code', '$max_students', '5', '$loggedin_instructor_username');";
                    mysqli_query($db, $query);
                    $query = "INSERT INTO courses (course_code, max_students, course_type, instructor_username) 
                    VALUES('" . $course_code . "_T', '$max_students', '3', '$loggedin_instructor_username');";
   
   
   mysqli_query($db, $query);
                }
                elseif ($course_type == 8) {
                    $query = "INSERT INTO courses (course_code, max_students, course_type, instructor_username) 
                    VALUES('$course_code', '$max_students', '5', '$loggedin_instructor_username');";
                    mysqli_query($db, $query);
                    $num_labs = ceil($max_students / $lab_capacity);
                    $query = "INSERT INTO courses (course_code, max_students, course_type, num_of_labs) 
                    VALUES('" . $course_code . "_L', '$max_students', '4', '$num_labs')";
                    mysqli_query($db, $query);
                }
                else{
                    $query = "INSERT INTO courses (course_code, max_students, course_type, instructor_username) 
                    VALUES('$course_code', '$max_students', '5', '$loggedin_instructor_username');";
                    mysqli_query($db, $query);
                    $query = "INSERT INTO courses (course_code, max_students, course_type, instructor_username) 
                    VALUES('" . $course_code . "_T', '$max_students', '3', '$loggedin_instructor_username');";
                    mysqli_query($db, $query);
                    $num_labs = ceil($max_students / $lab_capacity);
                    $query = "INSERT INTO courses (course_code, max_students, course_type, num_of_labs) 
                    VALUES('" . $course_code . "_L', '$max_students', '4', '$num_labs')";
                    mysqli_query($db, $query);
                }
                function_alert("Course Added!");
                $_SESSION['added_course'] = $course_code;
            }
        }
    }
}

// ADD LABORATORY
if (isset($_POST['add_lab'])) {
    $lab_code = mysqli_real_escape_string($db, $_POST['lab_code']);
    $query = "SELECT * FROM courses WHERE course_code='$lab_code' LIMIT 1";
    $result = mysqli_query($db, $query);
    $user = mysqli_fetch_assoc($result);
    if (!$user) {
        function_alert("Lab does not exists!");
    } else {
        if (count($errors) == 0) {
            $loggedin_instructor_username = $_SESSION['username'];
            $query = "UPDATE courses SET instructor_username = '$loggedin_instructor_username' WHERE course_code = '$lab_code'";
            mysqli_query($db, $query);
            function_alert("Lab Added!");
            $_SESSION['added_course'] = $course_code;
        }
    }
}

// UPDATE COURSE PREFFERED HOURS
if (isset($_POST['update_course_pref_hours'])) {
    $instructor = $_SESSION['username'];
    $course_code = mysqli_real_escape_string($db, $_POST['course_id']);
    $query = "SELECT * FROM courses WHERE course_code='$course_code' LIMIT 1";
    $result = mysqli_query($db, $query);
    $course = mysqli_fetch_assoc($result);
    if (!$course) {
        function_alert("Course does not exists!");
    } else {
        $query = "DELETE FROM courses_hours WHERE course_code='$course_code'";
        mysqli_query($db, $query);
        $_SESSION['added_course'] = $course_code;
        header('location: pref_days.php');
    }
}

// ADD UNAVAILABLE DAYS
if (isset($_POST['add_unavailable_days'])) {
    $_SESSION['mt'] = 0;
    $_SESSION['w'] = 0;
    $_SESSION['tf'] = 0;
    $instructor = $_SESSION['username'];
    $query = "DELETE FROM instructors_unable_hours WHERE instructor_username = '$instructor'";
    mysqli_query($db, $query);
    $query = "DELETE FROM instructors_unable_hours_wednesdays WHERE instructor_username = '$instructor'";
    mysqli_query($db, $query);
    $mt = 0;
    $w = 0;
    $tf = 0;
    if (count($errors) == 0) {
        if (isset($_POST['monday_thursday'])) {
            $mt = 1;
            $_SESSION['mt'] = 1;
        }
        if (isset($_POST['wednesday'])) {
            $w = 1;
            $_SESSION['w'] = 1;
        }
        if (isset($_POST['tuesday_friday'])) {
            $tf = 1;
            $_SESSION['tf'] = 1;
        }
        if ($mt == 1) {
            $query = "INSERT INTO instructors_unable_hours (instructor_username, day, hour) 
                    VALUES('$instructor', '1', '9.0')";
            mysqli_query($db, $query);
            $query = "INSERT INTO instructors_unable_hours (instructor_username, day, hour) 
                    VALUES('$instructor', '1', '10.5')";
            mysqli_query($db, $query);
            $query = "INSERT INTO instructors_unable_hours (instructor_username, day, hour) 
                    VALUES('$instructor', '1', '12.0')";
            mysqli_query($db, $query);
            $query = "INSERT INTO instructors_unable_hours (instructor_username, day, hour) 
                    VALUES('$instructor', '1', '13.5')";
            mysqli_query($db, $query);
            $query = "INSERT INTO instructors_unable_hours (instructor_username, day, hour) 
                    VALUES('$instructor', '1', '15.0')";
            mysqli_query($db, $query);
            $query = "INSERT INTO instructors_unable_hours (instructor_username, day, hour) 
                    VALUES('$instructor', '1', '16.5')";
            mysqli_query($db, $query);
            $query = "INSERT INTO instructors_unable_hours (instructor_username, day, hour) 
                    VALUES('$instructor', '1', '18.0')";
            mysqli_query($db, $query);
            $query = "INSERT INTO instructors_unable_hours (instructor_username, day, hour) 
                    VALUES('$instructor', '1', '19.5')";
            mysqli_query($db, $query);
        }
        if ($w == 1) {
            $query = "INSERT INTO instructors_unable_hours_wednesdays (instructor_username, hour) 
                    VALUES('$instructor',  '9.0')";
            mysqli_query($db, $query);
            $query = "INSERT INTO instructors_unable_hours_wednesdays (instructor_username, hour) 
                    VALUES('$instructor',  '10.0')";
            mysqli_query($db, $query);
            $query = "INSERT INTO instructors_unable_hours_wednesdays (instructor_username, hour) 
                    VALUES('$instructor',  '11.0')";
            mysqli_query($db, $query);
            $query = "INSERT INTO instructors_unable_hours_wednesdays (instructor_username, hour) 
                    VALUES('$instructor',  '12.0')";
            mysqli_query($db, $query);
            $query = "INSERT INTO instructors_unable_hours_wednesdays (instructor_username, hour) 
                    VALUES('$instructor',  '13.0')";
            mysqli_query($db, $query);
            $query = "INSERT INTO instructors_unable_hours_wednesdays (instructor_username, hour) 
                    VALUES('$instructor',  '14.0')";
            mysqli_query($db, $query);
            $query = "INSERT INTO instructors_unable_hours_wednesdays (instructor_username, hour) 
                    VALUES('$instructor',  '15.0')";
            mysqli_query($db, $query);
            $query = "INSERT INTO instructors_unable_hours_wednesdays (instructor_username, hour) 
                    VALUES('$instructor',  '16.0')";
            mysqli_query($db, $query);
            $query = "INSERT INTO instructors_unable_hours_wednesdays (instructor_username, hour) 
                    VALUES('$instructor',  '17.0')";
            mysqli_query($db, $query);
            $query = "INSERT INTO instructors_unable_hours_wednesdays (instructor_username, hour) 
                    VALUES('$instructor',  '18.0')";
            mysqli_query($db, $query);
            $query = "INSERT INTO instructors_unable_hours_wednesdays (instructor_username, hour) 
                    VALUES('$instructor',  '19.0')";
            mysqli_query($db, $query);
            $query = "INSERT INTO instructors_unable_hours_wednesdays (instructor_username, hour) 
                    VALUES('$instructor',  '20.0')";
            mysqli_query($db, $query);
        }
        if ($tf == 1) {
            $query = "INSERT INTO instructors_unable_hours (instructor_username, day, hour) 
                    VALUES('$instructor', '2', '9.0')";
            mysqli_query($db, $query);
            $query = "INSERT INTO instructors_unable_hours (instructor_username, day, hour) 
                    VALUES('$instructor', '2', '10.5')";
            mysqli_query($db, $query);
            $query = "INSERT INTO instructors_unable_hours (instructor_username, day, hour) 
                    VALUES('$instructor', '2', '12.0')";
            mysqli_query($db, $query);
            $query = "INSERT INTO instructors_unable_hours (instructor_username, day, hour) 
                    VALUES('$instructor', '2', '13.5')";
            mysqli_query($db, $query);
            $query = "INSERT INTO instructors_unable_hours (instructor_username, day, hour) 
                    VALUES('$instructor', '2', '15.0')";
            mysqli_query($db, $query);
            $query = "INSERT INTO instructors_unable_hours (instructor_username, day, hour) 
                    VALUES('$instructor', '2', '16.5')";
            mysqli_query($db, $query);
            $query = "INSERT INTO instructors_unable_hours (instructor_username, day, hour) 
                    VALUES('$instructor', '2', '18.0')";
            mysqli_query($db, $query);
            $query = "INSERT INTO instructors_unable_hours (instructor_username, day, hour) 
                    VALUES('$instructor', '2', '19.5')";
            mysqli_query($db, $query);
        }
        if ($mt == 0) {
            header('location: unavailable_hours_mt.php');
        } else if ($w == 0) {
            header('location: unavailable_hours_w.php');
        } else if ($tf == 0) {
            header('location: unavailable_hours_tf.php');
        } else
            header('location: instructor.php');
    }
}

// ADD UNAVAILABLE HOURS MONDAY/THURSDAY
if (isset($_POST['add_unavailable_hours_mt'])) {
    $instructor = $_SESSION['username'];
    if (count($errors) == 0) {
        $h_9 = mysqli_real_escape_string($db, $_POST['h_9']);
        $h_1030 = mysqli_real_escape_string($db, $_POST['h_1030']);
        $h_12 = mysqli_real_escape_string($db, $_POST['h_12']);
        $h_1330 = mysqli_real_escape_string($db, $_POST['h_1330']);
        $h_15 = mysqli_real_escape_string($db, $_POST['h_15']);
        $h_1630 = mysqli_real_escape_string($db, $_POST['h_1630']);
        $h_18 = mysqli_real_escape_string($db, $_POST['h_18']);
        $h_1930 = mysqli_real_escape_string($db, $_POST['h_1930']);
        if ($h_9 == 1) {
            $query = "INSERT INTO instructors_unable_hours (instructor_username, day, hour) 
                    VALUES('$instructor', '1', '9.0')";
            mysqli_query($db, $query);
        }
        if ($h_1030 == 1) {
            $query = "INSERT INTO instructors_unable_hours (instructor_username, day, hour) 
                    VALUES('$instructor', '1', '10.5')";
            mysqli_query($db, $query);
        }
        if ($h_12 == 1) {
            $query = "INSERT INTO instructors_unable_hours (instructor_username, day, hour) 
                    VALUES('$instructor', '1', '12.0')";
            mysqli_query($db, $query);
        }
        if ($h_1330 == 1) {
            $query = "INSERT INTO instructors_unable_hours (instructor_username, day, hour) 
                    VALUES('$instructor', '1', '13.5')";
            mysqli_query($db, $query);
        }
        if ($h_15 == 1) {
            $query = "INSERT INTO instructors_unable_hours (instructor_username, day, hour) 
                    VALUES('$instructor', '1', '15.0')";
            mysqli_query($db, $query);
        }
        if ($h_1630 == 1) {
            $query = "INSERT INTO instructors_unable_hours (instructor_username, day, hour) 
                    VALUES('$instructor', '1', '16.5')";
            mysqli_query($db, $query);
        }
        if ($h_18 == 1) {
            $query = "INSERT INTO instructors_unable_hours (instructor_username, day, hour) 
                    VALUES('$instructor', '1', '18.0')";
            mysqli_query($db, $query);
        }
        if ($h_1930 == 1) {
            $query = "INSERT INTO instructors_unable_hours (instructor_username, day, hour) 
                    VALUES('$instructor', '1', '19.5')";
            mysqli_query($db, $query);
        }
        if ($_SESSION['w'] == 0) {
            header('location: unavailable_hours_w.php');
        } else if ($_SESSION['tf'] == 0) {
            header('location: unavailable_hours_tf.php');
        } else
            header('location: instructor.php');
    }
}

// ADD UNAVAILABLE HOURS WEDNESDAY
if (isset($_POST['add_unavailable_hours_w'])) {
    $instructor = $_SESSION['username'];
    if (count($errors) == 0) {
        $h_9 = mysqli_real_escape_string($db, $_POST['h_9']);
        $h_10 = mysqli_real_escape_string($db, $_POST['h_10']);
        $h_11 = mysqli_real_escape_string($db, $_POST['h_11']);
        $h_12 = mysqli_real_escape_string($db, $_POST['h_12']);
        $h_13 = mysqli_real_escape_string($db, $_POST['h_13']);
        $h_14 = mysqli_real_escape_string($db, $_POST['h_14']);
        $h_15 = mysqli_real_escape_string($db, $_POST['h_15']);
        $h_16 = mysqli_real_escape_string($db, $_POST['h_16']);
        $h_17 = mysqli_real_escape_string($db, $_POST['h_17']);
        $h_18 = mysqli_real_escape_string($db, $_POST['h_18']);
        $h_19 = mysqli_real_escape_string($db, $_POST['h_19']);
        $h_20 = mysqli_real_escape_string($db, $_POST['h_20']);
        if ($h_9 == 1) {
            $query = "INSERT INTO instructors_unable_hours_wednesdays (instructor_username, hour) 
                    VALUES('$instructor',  '9.0')";
            mysqli_query($db, $query);
        }
        if ($h_10 == 1) {
            $query = "INSERT INTO instructors_unable_hours_wednesdays (instructor_username, hour) 
                    VALUES('$instructor',  '10.0')";
            mysqli_query($db, $query);
        }
        if ($h_11 == 1) {
            $query = "INSERT INTO instructors_unable_hours_wednesdays (instructor_username, hour) 
                    VALUES('$instructor',  '11.0')";
            mysqli_query($db, $query);
        }
        if ($h_12 == 1) {
            $query = "INSERT INTO instructors_unable_hours_wednesdays (instructor_username, hour) 
                    VALUES('$instructor',  '12.0')";
            mysqli_query($db, $query);
        }
        if ($h_13 == 1) {
            $query = "INSERT INTO instructors_unable_hours_wednesdays (instructor_username, hour) 
                    VALUES('$instructor',  '13.0')";
            mysqli_query($db, $query);
        }
        if ($h_14 == 1) {
            $query = "INSERT INTO instructors_unable_hours_wednesdays (instructor_username, hour) 
                    VALUES('$instructor',  '14.0')";
            mysqli_query($db, $query);
        }
        if ($h_15 == 1) {
            $query = "INSERT INTO instructors_unable_hours_wednesdays (instructor_username, hour) 
                    VALUES('$instructor',  '15.0')";
            mysqli_query($db, $query);
        }
        if ($h_16 == 1) {
            $query = "INSERT INTO instructors_unable_hours_wednesdays (instructor_username, hour) 
                    VALUES('$instructor',  '16.0')";
            mysqli_query($db, $query);
        }
        if ($h_17 == 1) {
            $query = "INSERT INTO instructors_unable_hours_wednesdays (instructor_username, hour) 
                    VALUES('$instructor',  '17.0')";
            mysqli_query($db, $query);
        }
        if ($h_18 == 1) {
            $query = "INSERT INTO instructors_unable_hours_wednesdays (instructor_username, hour) 
                    VALUES('$instructor',  '18.0')";
            mysqli_query($db, $query);
        }
        if ($h_19 == 1) {
            $query = "INSERT INTO instructors_unable_hours_wednesdays (instructor_username, hour) 
                    VALUES('$instructor',  '19.0')";
            mysqli_query($db, $query);
        }
        if ($h_20 == 1) {
            $query = "INSERT INTO instructors_unable_hours_wednesdays (instructor_username, hour) 
                    VALUES('$instructor',  '20.0')";
            mysqli_query($db, $query);
        }

        if ($_SESSION['tf'] == 0) {
            header('location: unavailable_hours_tf.php');
        } else
            header('location: instructor.php');
    }
}

// ADD UNAVAILABLE HOURS TUESDAY/FRIDAY
if (isset($_POST['add_unavailable_hours_tf'])) {
    $instructor = $_SESSION['username'];
    if (count($errors) == 0) {
        $h_9 = mysqli_real_escape_string($db, $_POST['h_9']);
        $h_1030 = mysqli_real_escape_string($db, $_POST['h_1030']);
        $h_12 = mysqli_real_escape_string($db, $_POST['h_12']);
        $h_1330 = mysqli_real_escape_string($db, $_POST['h_1330']);
        $h_15 = mysqli_real_escape_string($db, $_POST['h_15']);
        $h_1630 = mysqli_real_escape_string($db, $_POST['h_1630']);
        $h_18 = mysqli_real_escape_string($db, $_POST['h_18']);
        $h_1930 = mysqli_real_escape_string($db, $_POST['h_1930']);
        if ($h_9 == 1) {
            $query = "INSERT INTO instructors_unable_hours (instructor_username, day, hour) 
                    VALUES('$instructor', '2', '9.0')";
            mysqli_query($db, $query);
        }
        if ($h_1030 == 1) {
            $query = "INSERT INTO instructors_unable_hours (instructor_username, day, hour) 
                    VALUES('$instructor', '2', '10.5')";
            mysqli_query($db, $query);
        }
        if ($h_12 == 1) {
            $query = "INSERT INTO instructors_unable_hours (instructor_username, day, hour) 
                    VALUES('$instructor', '2', '12.0')";
            mysqli_query($db, $query);
        }
        if ($h_1330 == 1) {
            $query = "INSERT INTO instructors_unable_hours (instructor_username, day, hour) 
                    VALUES('$instructor', '2', '13.5')";
            mysqli_query($db, $query);
        }
        if ($h_15 == 1) {
            $query = "INSERT INTO instructors_unable_hours (instructor_username, day, hour) 
                    VALUES('$instructor', '2', '15.0')";
            mysqli_query($db, $query);
        }
        if ($h_1630 == 1) {
            $query = "INSERT INTO instructors_unable_hours (instructor_username, day, hour) 
                    VALUES('$instructor', '2', '16.5')";
            mysqli_query($db, $query);
        }
        if ($h_18 == 1) {
            $query = "INSERT INTO instructors_unable_hours (instructor_username, day, hour) 
                    VALUES('$instructor', '2', '18.0')";
            mysqli_query($db, $query);
        }
        if ($h_1930 == 1) {
            $query = "INSERT INTO instructors_unable_hours (instructor_username, day, hour) 
                    VALUES('$instructor', '2', '19.5')";
            mysqli_query($db, $query);
        }
        header('location: instructor.php');
    }
}

// ADD COURSES CONFLICT
if (isset($_POST['courses_conflict'])) {
    $course_code1 = mysqli_real_escape_string($db, $_POST['c1']);
    $course_code2 = mysqli_real_escape_string($db, $_POST['c2']);
    if ($course_code1 == $course_code2) {
        function_alert("Please choose different courses!");
    } else {

        $q = "SELECT * FROM conflicts WHERE ( course_code1 = '$course_code1' AND  course_code2 = '$course_code2' ) OR ( course_code1 = '$course_code2' AND  course_code2 = '$course_code1' ) LIMIT 1";
        $result = mysqli_query($db, $q);
        $user = mysqli_fetch_assoc($result);
        if ($user) {
            function_alert("Courses conflict already exists!");
        } else {
            $q = "SELECT * FROM courses WHERE course_code = '$course_code1'";
            $result = mysqli_query($db, $q);
            $user1 = mysqli_fetch_assoc($result);
            if (!$user1) {
                function_alert("First course does not exist");
            } else {
                $q = "SELECT * FROM courses WHERE course_code = '$course_code2'";
                $result = mysqli_query($db, $q);
                $user2 = mysqli_fetch_assoc($result);
                if (!$user2) {
                    function_alert("Second course does not exist");
                } else if (count($errors) == 0) {
                    if($user1['instructor_username'] == $user2['instructor_username']){
                        function_alert("Please choose courses taught by different instructors!");
                    }
                    else{
                        $query = "INSERT INTO conflicts (course_code1, course_code2)
                            VALUES('$course_code1', '$course_code2')";
                        mysqli_query($db, $query);
                        function_alert("Course conflict Added!");
                    }
                }
            }
        }
    }
}

// ADD COURSES PARALLEL
if (isset($_POST['courses_parallel'])) {
    $course_code1 = mysqli_real_escape_string($db, $_POST['c1']);
    $course_code2 = mysqli_real_escape_string($db, $_POST['c2']);
    if ($course_code1 == $course_code2) {
        function_alert("Please choose different courses!");
    } else {
        $q = "SELECT * FROM parallel WHERE ( course_code1 = '$course_code1' AND  course_code2 = '$course_code2' ) OR ( course_code1 = '$course_code2' AND  course_code2 = '$course_code1' ) LIMIT 1";
        $result = mysqli_query($db, $q);
        $user = mysqli_fetch_assoc($result);
        if ($user) {
            function_alert("Courses parallel already exists!");
        } else {
            $q = "SELECT * FROM courses WHERE course_code = '$course_code1'";
            $result = mysqli_query($db, $q);
            $user1 = mysqli_fetch_assoc($result);
            if (!$user1) {
                function_alert("First course does not exist");
            } else {
                $q = "SELECT * FROM courses WHERE course_code = '$course_code2'";
                $result = mysqli_query($db, $q);
                $user2 = mysqli_fetch_assoc($result);
                if (!$user2) {
                    function_alert("Second course does not exist");
                } else if (count($errors) == 0) {
                    if($user1['instructor_username'] == $user2['instructor_username']){
                        function_alert("Please choose courses taught by different instructors!");
                    }
                    else{
                        $query = "INSERT INTO parallel (course_code1, course_code2)
                            VALUES('$course_code1', '$course_code2')";
                        mysqli_query($db, $query);
                        function_alert("Courses parallel Added!");
                    }
                }
            }
        }
    }
}

// ADD ADDITIONAL INFORMATION
if (isset($_POST['add_info'])) {
    $num_of_lecture_rooms = mysqli_real_escape_string($db, $_POST['num_of_lecture_rooms']);
    $num_of_lab_rooms = mysqli_real_escape_string($db, $_POST['num_of_lab_rooms']);
    $lec_capacity = mysqli_real_escape_string($db, $_POST['lec_capacity']);
    $lab_capacity = mysqli_real_escape_string($db, $_POST['lab_capacity']);
    $q = "DELETE FROM additional_info";
    mysqli_query($db, $q);
    $query = "INSERT INTO additional_info (lectures_capacity, labratories_capacity, num_of_labs, num_of_lecture_rooms) VALUES('$lec_capacity', '$lab_capacity', '$num_of_lab_rooms','$num_of_lecture_rooms')";
    $result = mysqli_query($db, $query);
    function_alert("Information added!");
}

function function_alert($msg)
{
    $msg = str_replace("'", "\\'", $msg);

    $js_code = "<script type='text/javascript'>
                   alert('$msg');
                 </script>";

    echo $js_code;
}

if (isset($_POST['logout'])) {
    session_destroy();
    header('location: index.php');
}

if (isset($_POST['logout_b'])) {
    session_destroy();
    header('location: ../index.php');
}

// ADD COURSES COMBO FOR SCHEDULE
if (isset($_POST['add_courses_combo'])) {

    $selected_courses = $_POST['selected_courses'];

    $courses_string = implode(',', $selected_courses);
    $sql = "SELECT course_code FROM courses WHERE course_code LIKE ";
    foreach ($selected_courses as $course) {
        $sql .= "'" . $course . "%' OR course_code LIKE ";
    }
    $sql = rtrim($sql, "OR course_code LIKE ");
    $result = mysqli_query($db, $sql);

    $courses_array = array();
    while ($row = mysqli_fetch_assoc($result)) {
         $courses_array[] = $row['course_code'];
    }
     $courses_array = array_unique(array_merge($selected_courses, $courses_array));

     $courses_string = implode(",", $courses_array);

     $sql = "SELECT DISTINCT instructor_username FROM courses WHERE course_code IN ('" . implode("','", $courses_array) . "')";
     $result = mysqli_query($db, $sql);
     $instructors_array = array();
     while ($row = mysqli_fetch_assoc($result)) {
        $instructors_array[] = $row['instructor_username'];
     }
     $instructors_string = implode(",", $instructors_array);
     $courses_string = '"' . $courses_string . '"';
     $instructors_string = '"' . $instructors_string . '"';
     //echo $courses_string;
     //echo " ";
     //echo $instructors_string;
     $input_string = $courses_string . " " . $instructors_string;

}

if (isset($_POST['run_algo'])) {
    //$filename = 'read_input.py'; // or whatever your filename is
    //$output = shell_exec("/usr/bin/python3 /opt/lampp/htdocs/python/$filename");

    // $query = "DELETE FROM run";
    //mysqli_query($db, $query);
    //$query = "INSERT INTO run (start) VALUES('1')";
    //mysqli_query($db, $query);
    //$command = escapeshellcmd('python3 hello.py');
    //$output = shell_exec($command);
    //echo $output;
    function_alert("Solver running!");
}