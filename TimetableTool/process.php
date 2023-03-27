<?php
session_start();

// initializing variables
$name = "";
$surname = "";
$email    = "";
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
            function_alert("User already exists!");
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
            $query = "INSERT INTO instructors (name, surname, email, instructor_password, instructor_username, admin_username) 
                    VALUES('$name', '$surname', '$email', '$password', '$inst_username', '$admin_username')";
            mysqli_query($db, $query);
            function_alert("Instructor registered!");
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
            $passwordReal = mysqli_real_escape_string($db, $_POST['add_admin_password']);
            $new_admin_username = mysqli_real_escape_string($db, $_POST['add_admin_username']);
            $loggedin_admin_username = $_SESSION['username'];
            $password = hash("sha512", $passwordReal);
            $query = "INSERT INTO admins (admin_username, admin_password) 
                    VALUES('$new_admin_username', '$password')";
            mysqli_query($db, $query);
            function_alert("Admin Added!");
        }
    }
}

// REMOVE INSTRUCTOR
if (isset($_POST['rem_inst_username'])) {
    $inst_username = mysqli_real_escape_string($db, $_POST['rem_inst_username']);
    $user_check_query = "SELECT * FROM instructors WHERE instructor_username='$inst_username' LIMIT 1";
    $result = mysqli_query($db, $user_check_query);
    if (mysqli_num_rows($result) == 1) {
        if (count($errors) == 0) {
            $inst_username = mysqli_real_escape_string($db, $_POST['rem_inst_username']);
            $admin_username = $_SESSION['username'];
            //$password = hash("sha512", $passwordReal);
            $query = "DELETE FROM instructors WHERE instructors.instructor_username = '$inst_username'";
            mysqli_query($db, $query);
            function_alert("Instructor removed!");
        }
    } else {
        function_alert("User does not exist!");
    }
}

// LOGIN USER
if (isset($_POST['login_user'])) {
    //run algo to check if timetable has been created then set some variable
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
            header('location: instructor/instructor.php');
        } else {
            $query = "SELECT * FROM admins WHERE admin_username='$username' AND admin_password='$password'";
            $results = mysqli_query($db, $query);
            $user = $results->fetch_assoc();
            if (mysqli_num_rows($results) == 1) {
                $_SESSION['username'] = $username;
                $_SESSION['admin'] = 1;
                $query = "DELETE FROM run";
                mysqli_query($db, $query);
                $query = "INSERT INTO run (start) VALUES('0')";
                mysqli_query($db, $query);
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
                $query = "INSERT INTO courses (course_code, max_students, course_type) 
                    VALUES('" . $course_code . "_L', '$max_students', '2')";
                mysqli_query($db, $query);
            } else {
                $query = "INSERT INTO courses (course_code, max_students, course_type, instructor_username) 
                    VALUES('$course_code', '$max_students', '1', '$loggedin_instructor_username');";
                mysqli_query($db, $query);
                $query = "INSERT INTO courses (course_code, max_students, course_type, instructor_username) 
                    VALUES('" . $course_code . "_T', '$max_students', '3', '$loggedin_instructor_username');";
                mysqli_query($db, $query);
                $query = "INSERT INTO courses (course_code, max_students, course_type) 
                    VALUES('". $course_code . "_L', '$max_students', '4')";
                mysqli_query($db, $query);
            }
            function_alert("Course Added!");
            $_SESSION['added_course'] = $course_code;
            header('location: pref_days.php');
        }
    }
}

// ADD LAB
// if (isset($_POST['add_lab'])) {
//     $course_code = mysqli_real_escape_string($db, $_POST['course_code']);
//     $query = "SELECT * FROM courses WHERE course_code='$course_code' LIMIT 1";
//     $result = mysqli_query($db, $query);
//     $user = mysqli_fetch_assoc($result);
//     if (!$user) {
//         function_alert("Course does not exists!");
//     } else {
//         if (count($errors) == 0) {
//             $lab_code = mysqli_real_escape_string($db, $_POST['lab_code']);
//             $course_type = 5;
//             $max_students = 30;
//             $loggedin_instructor_username = $_SESSION['username'];
//             $course_code = $course_code . '_' . $lab_code;
//             $query = "INSERT INTO courses (course_code, max_students, course_type, instructor_username) 
//                     VALUES('$course_code', '$max_students', '$course_type', '$loggedin_instructor_username')";
//             mysqli_query($db, $query);
//             function_alert("Lab Added!");
//             $_SESSION['added_course'] = $course_code;
//             header('location: pref_days.php');
//         }
//     }
// }

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
            header('location: pref_days.php');
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

// ADD PREFFERED DAYS
if (isset($_POST['add_pref_days'])) {
    $_SESSION['mt'] = 0;
    $_SESSION['w'] = 0;
    $_SESSION['tf'] = 0;
    $instructor = $_SESSION['username'];
    if (count($errors) == 0) {
        if (isset($_POST['monday_thursday'])) {
            $_SESSION['mt'] = 1;
        }
        if (isset($_POST['wednesday'])) {
            $_SESSION['w'] = 1;
        }
        if (isset($_POST['tuesday_friday'])) {
            $_SESSION['tf'] = 1;
        }
        if ($_SESSION['mt'] == 1) {
            header('location: pref_hours_mt.php');
        } else if ($_SESSION['w'] == 1) {
            header('location: pref_hours_w.php');
        } else if ($_SESSION['tf'] == 1) {
            header('location: pref_hours_tf.php');
        } else  header('location: instructor.php');
    }
}

// ADD PREFERRED HOURS MONDAY/THURSDAY
if (isset($_POST['add_preferred_hours_mt'])) {
    $course_code =  $_SESSION['added_course'];
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
            $query = "INSERT INTO courses_hours (course_code, day, hour) 
                    VALUES('$course_code', '1', '9.0')";
            mysqli_query($db, $query);
        }
        if ($h_1030 == 1) {
            $query = "INSERT INTO courses_hours (course_code, day, hour) 
                    VALUES('$course_code', '1', '10.5')";
            mysqli_query($db, $query);
        }
        if ($h_12 == 1) {
            $query = "INSERT INTO courses_hours (course_code, day, hour) 
                    VALUES('$course_code', '1', '12.0')";
            mysqli_query($db, $query);
        }
        if ($h_1330 == 1) {
            $query = "INSERT INTO courses_hours (course_code, day, hour) 
                    VALUES('$course_code', '1', '13.5')";
            mysqli_query($db, $query);
        }
        if ($h_15 == 1) {
            $query = "INSERT INTO courses_hours (course_code, day, hour) 
                    VALUES('$course_code', '1', '15.0')";
            mysqli_query($db, $query);
        }
        if ($h_1630 == 1) {
            $query = "INSERT INTO courses_hours (course_code, day, hour) 
                    VALUES('$course_code', '1', '16.5')";
            mysqli_query($db, $query);
        }
        if ($h_18 == 1) {
            $query = "INSERT INTO courses_hours (course_code, day, hour) 
                    VALUES('$course_code', '1', '18.0')";
            mysqli_query($db, $query);
        }
        if ($h_1930 == 1) {
            $query = "INSERT INTO courses_hours (course_code, day, hour) 
                    VALUES('$course_code', '1', '19.5')";
            mysqli_query($db, $query);
        }
        if ($_SESSION['w'] == 1) {
            header('location: pref_hours_w.php');
        } else if ($_SESSION['tf'] == 1) {
            header('location: pref_hours_tf.php');
        } else header('location: instructor.php');
    }
}

// ADD PREFERRED HOURS WEDNESDAY
if (isset($_POST['add_preferred_hours_w'])) {
    $course_code =  $_SESSION['added_course'];
    if (count($errors) == 0) {
        $h_7 = mysqli_real_escape_string($db, $_POST['h_7']);
        $h_8 = mysqli_real_escape_string($db, $_POST['h_8']);
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
        if ($h_7 == 1) {
            $query = "INSERT INTO courses_hours_wednesdays (course_code, hour) 
                    VALUES('$course_code', '7.0')";
            mysqli_query($db, $query);
        }
        if ($h_8 == 1) {
            $query = "INSERT INTO courses_hours_wednesdays (course_code, hour) 
                    VALUES('$course_code', '8.0')";
            mysqli_query($db, $query);
        }
        if ($h_9 == 1) {
            $query = "INSERT INTO courses_hours_wednesdays (course_code, hour) 
                    VALUES('$course_code', '9.0')";
            mysqli_query($db, $query);
        }
        if ($h_10 == 1) {
            $query = "INSERT INTO courses_hours_wednesdays (course_code, hour) 
                    VALUES('$course_code', '10.0')";
            mysqli_query($db, $query);
        }
        if ($h_11 == 1) {
            $query = "INSERT INTO courses_hours_wednesdays (course_code, hour) 
                    VALUES('$course_code', '11.0')";
            mysqli_query($db, $query);
        }
        if ($h_12 == 1) {
            $query = "INSERT INTO courses_hours_wednesdays (course_code, hour) 
                    VALUES('$course_code', '12.0')";
            mysqli_query($db, $query);
        }
        if ($h_13 == 1) {
            $query = "INSERT INTO courses_hours_wednesdays (course_code, hour) 
                    VALUES('$course_code', '13.0')";
            mysqli_query($db, $query);
        }
        if ($h_14 == 1) {
            $query = "INSERT INTO courses_hours_wednesdays (course_code, hour) 
                    VALUES('$course_code', '14.0')";
            mysqli_query($db, $query);
        }
        if ($h_15 == 1) {
            $query = "INSERT INTO courses_hours_wednesdays (course_code, hour) 
                    VALUES('$course_code', '15.0')";
            mysqli_query($db, $query);
        }
        if ($h_16 == 1) {
            $query = "INSERT INTO courses_hours_wednesdays (course_code, hour) 
                    VALUES('$course_code', '16.0')";
            mysqli_query($db, $query);
        }
        if ($h_17 == 1) {
            $query = "INSERT INTO courses_hours_wednesdays (course_code, hour) 
                    VALUES('$course_code', '17.0')";
            mysqli_query($db, $query);
        }
        if ($h_18 == 1) {
            $query = "INSERT INTO courses_hours_wednesdays (course_code, hour) 
                    VALUES('$course_code', '18.0')";
            mysqli_query($db, $query);
        }


        if ($_SESSION['tf'] == 1) {
            header('location: pref_hours_tf.php');
        } else header('location: instructor.php');
    }
}

// ADD PREFERRED HOURS TUESDAY/FRIDAY
if (isset($_POST['add_preferred_hours_tf'])) {
    $course_code =  $_SESSION['added_course'];
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
            $query = "INSERT INTO courses_hours (course_code, day, hour) 
                    VALUES('$course_code', '3', '9.0')";
            mysqli_query($db, $query);
        }
        if ($h_1030 == 1) {
            $query = "INSERT INTO courses_hours (course_code, day, hour) 
                    VALUES('$course_code', '3', '10.5')";
            mysqli_query($db, $query);
        }
        if ($h_12 == 1) {
            $query = "INSERT INTO courses_hours (course_code, day, hour) 
                    VALUES('$course_code', '3', '12.0')";
            mysqli_query($db, $query);
        }
        if ($h_1330 == 1) {
            $query = "INSERT INTO courses_hours (course_code, day, hour) 
                    VALUES('$course_code', '3', '13.5')";
            mysqli_query($db, $query);
        }
        if ($h_15 == 1) {
            $query = "INSERT INTO courses_hours (course_code, day, hour) 
                    VALUES('$course_code', '3', '15.0')";
            mysqli_query($db, $query);
        }
        if ($h_1630 == 1) {
            $query = "INSERT INTO courses_hours (course_code, day, hour) 
                    VALUES('$course_code', '3', '16.5')";
            mysqli_query($db, $query);
        }
        if ($h_18 == 1) {
            $query = "INSERT INTO courses_hours (course_code, day, hour) 
                    VALUES('$course_code', '3', '18.0')";
            mysqli_query($db, $query);
        }
        if ($h_1930 == 1) {
            $query = "INSERT INTO courses_hours (course_code, day, hour) 
                    VALUES('$course_code', '3', '19.5')";
            mysqli_query($db, $query);
        }
        header('location: instructor.php');
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
            mysqli_query($db, $query);
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
        } else  header('location: instructor.php');
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
        } else header('location: instructor.php');
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
        } else header('location: instructor.php');
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
    $q = "SELECT * FROM conflicts WHERE ( course_code1 = '$course_code1' AND  course_code2 = '$course_code2' ) OR ( course_code1 = '$course_code2' AND  course_code2 = '$course_code1' ) LIMIT 1";
    $result = mysqli_query($db, $q);
    $user = mysqli_fetch_assoc($result);
    if ($user) {
        function_alert("Courses conflict already exists!");
    } else {
        $q = "SELECT * FROM courses WHERE ( course_code = '$course_code1')";
        $result = mysqli_query($db, $q);
        $user = mysqli_fetch_assoc($result);
        if (!$user) {
            function_alert("First course does not exist");
        } else {
            $q = "SELECT * FROM courses WHERE ( course_code = '$course_code2')";
            $result = mysqli_query($db, $q);
            $user = mysqli_fetch_assoc($result);
            if (!$user) {
                function_alert("Second course does not exist");
            } else if (count($errors) == 0) {
                $query = "INSERT INTO conflicts (course_code1, course_code2)
                        VALUES('$course_code1', '$course_code2')";
                mysqli_query($db, $query);
                function_alert("Course conflict Added!");
            }
        }
    }
}

// ADD COURSES PARALLEL
if (isset($_POST['courses_parallel'])) {
    $course_code1 = mysqli_real_escape_string($db, $_POST['c1']);
    $course_code2 = mysqli_real_escape_string($db, $_POST['c2']);
    $q = "SELECT * FROM parallel WHERE ( course_code1 = '$course_code1' AND  course_code2 = '$course_code2' ) OR ( course_code1 = '$course_code2' AND  course_code2 = '$course_code1' ) LIMIT 1";
    $result = mysqli_query($db, $q);
    $user = mysqli_fetch_assoc($result);
    if ($user) {
        function_alert("Courses parallel already exists!");
    } else {
        $q = "SELECT * FROM courses WHERE ( course_code = '$course_code1')";
        $result = mysqli_query($db, $q);
        $user = mysqli_fetch_assoc($result);
        if (!$user) {
            function_alert("First course does not exist");
        } else {
            $q = "SELECT * FROM courses WHERE ( course_code = '$course_code2')";
            $result = mysqli_query($db, $q);
            $user = mysqli_fetch_assoc($result);
            if (!$user) {
                function_alert("Second course does not exist");
            } else if (count($errors) == 0) {
                $query = "INSERT INTO parallel (course_code1, course_code2)
                        VALUES('$course_code1', '$course_code2')";
                mysqli_query($db, $query);
                function_alert("Courses parallel Added!");
            }
        }
    }
}

// ADD ROOM
if (isset($_POST['add_room'])) {
    $room_capacity = mysqli_real_escape_string($db, $_POST['add_room_capacity']);
    $room_number = mysqli_real_escape_string($db, $_POST['add_room_number']);
    $q = "SELECT * FROM rooms WHERE ( room_number = '$room_number' )";
    $result = mysqli_query($db, $q);
    $user = mysqli_fetch_assoc($result);
    if ($user) {
        function_alert("Room already exists!");
    } else {
        $query = "INSERT INTO rooms (room_number, room_capacity) VALUES('$room_number', '$room_capacity')";
        mysqli_query($db, $query);
        function_alert("Room added!");
    }
}

function function_alert($msg)
{
    echo "<script type='text/javascript'>alert('$msg');</script>";
}

if (isset($_POST['logout'])) {
    session_destroy();
    header('location: index.php');
}

if (isset($_POST['logout_b'])) {
    session_destroy();
    header('location: ../index.php');
}


if (isset($_POST['run_algo'])) {
    //$filename = 'read_input.py'; // or whatever your filename is
    //$output = shell_exec("/usr/bin/python3 /opt/lampp/htdocs/python/$filename");

    $query = "DELETE FROM run";
    mysqli_query($db, $query);
    $query = "INSERT INTO run (start) VALUES('1')";
    mysqli_query($db, $query);
    //$command = escapeshellcmd('python3 hello.py');
    //$output = shell_exec($command);
    //echo $output;
}


