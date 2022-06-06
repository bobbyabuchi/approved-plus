<?php

session_start();

if (isset($_SESSION['contact'])) {
    # code...
    $user_check = $_SESSION['contact'];
    // SQL Query To Fetch Complete Information Of User
    $user = "SELECT * FROM people WHERE phone = '$user_check' OR email = '$user_check'";
    $user = $connect_db->query($user);
    $user_value = mysqli_fetch_array($user);
    $user_id = $user_value['id'];
}

if (isset($_SESSION['savedteam'])) {
  # code...
  $user_check = $_SESSION['savedteam'];
  // SQL Query To Fetch Complete Information Of User
  $query = "SELECT id FROM follow_up_team WHERE phone = '$user_check' OR email = '$user_check'";
  $ses_sql = mysqli_query($connect_db, $query);
  $row = mysqli_fetch_assoc($ses_sql);
  $team_id = $row['id'];
}

# Login -----------------------------------------------------------------
if (isset($_POST['sign-in'])) {
  # code...
  $contact = stripcslashes($_REQUEST['contact']);
  $contact = mysqli_real_escape_string($connect_db, $contact);
  $password = stripslashes($_REQUEST['password']);
  $password = mysqli_real_escape_string($connect_db, $password);
  $login_query = "SELECT * FROM people WHERE email = '$contact' OR phone = '$contact' AND password = '$password'";
  //$login_query = "SELECT * FROM candidates WHERE email = '$email' and official_password = '".md5($official_password)."' ";
  $query_result = mysqli_query($connect_db, $login_query) or die('Error:' . mysqli_error($connect_db));
  $login_row = mysqli_num_rows($query_result);
  if($login_row == 1){
    # code...
    $_SESSION['contact'] = $contact;
    header("Location: /profile.php");
  }else{
    $flash_message = "Incorrect login details!";
    //header('Location: index.php');
  }
}

if (isset($_POST['team-sign-in'])) {
  # code...
  $contact = stripcslashes($_REQUEST['savedteam']);
  $contact = mysqli_real_escape_string($connect_db, $contact);
  $password = stripslashes($_REQUEST['password']);
  $password = mysqli_real_escape_string($connect_db, $password);
  $login_query = "SELECT * FROM follow_up_team WHERE email = '$contact' OR phone = '$contact' AND password = '$password'";
  //$login_query = "SELECT * FROM candidates WHERE email = '$email' and official_password = '".md5($official_password)."' ";
  $query_result = mysqli_query($connect_db, $login_query) or die('Error:' . mysqli_error($connect_db));
  $login_row = mysqli_num_rows($query_result);
  if($login_row == 1){
    # code...
    $_SESSION['savedteam'] = $contact;
    header("Location: /profile.php");
  }else{
    $flash_message = "Incorrect login details!";
    //header('Location: index.php');
  }
}



# Logout ----------------------------------------------------------------------
if (isset($_POST['sign-out'])) {
  # code...
  session_start();
  # code...Unset all of the session variables
  $_SESSION = array();
  # code...destroy session
  session_destroy();
  //echo "You have successfully logged out!"
  header('Location: /sign-in.php');
  // absolute path //header('Location: http://localhost/web.com/index.php');
  exit();
}

?>