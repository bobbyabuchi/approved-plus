<?php

// current date
date_default_timezone_set('Africa/Lagos');
$sign_in = date("h:i:s A");
$sign_out = date("h:i:s A");

$present_date = date("d");
$present_day = date("D");
$present_year = date("Y");
$present_month = date("m");

# fetch newest registered member id for setting default password
$newest_member_id = "SELECT * FROM people ORDER BY id DESC LIMIT 1";
$newest_member_id = $connect_db->query($newest_member_id);
if ($newest_member_id->num_rows > 0) {
  // output data of each row
  while($newest_member_id_row = $newest_member_id->fetch_assoc()) {
    $newest_member_id_value = $newest_member_id_row["id"];
    $newest_member_id_value += 1;
  }
} else {
    $start_count = 1;
  $newest_member_id_value = $start_count;
}

$Saved = 'Saved';
//echo $Saved.$newest_member_id_value;
$password = $Saved.$newest_member_id_value;

# follow up request Sign-up
if (isset($_POST['register'])) {
    // code...
    $fullname = $connect_db->real_escape_string($_POST['fullname']);
    $gender = $connect_db->real_escape_string($_POST['gender']);
    $phone = $connect_db->real_escape_string($_POST['phone']);
    $location = $connect_db->real_escape_string($_POST['location']);
    //$agreement = $connect_db->real_escape_string($_POST['agreement']);
    //$sign_in = $sign_in;

    # CHECK IF phone NUMBER ALREADY EXISTS
    $check_existing = $connect_db->query("SELECT * FROM people WHERE phone = '$phone'");

    if ($fullname == '' or $gender == '' or $phone == '' or $location =='') {
        // code...
        $flash_message = "<b class='text-white btn btn-danger btn-block'>Could you kindly complete the form please?</b>";
    }elseif ($check_existing->num_rows != 0) {
        # code...
       $flash_message = "<b class='text-white btn btn-danger btn-block'>Profile already exists, kindly login.</b>";
       //header("Refresh:3; url=sign-in.php");
    }
    else {
        $save_profile = "INSERT INTO people (fullname, gender, phone, location, password, present_day, present_date, present_month, present_year) VALUES ('$fullname', '$gender', '$phone', '$location', '$password', '$present_day', '$present_date', '$present_month', '$present_year')";
        if ($connect_db->query($save_profile) != TRUE) {
            // code...
            $flash_message = "<b class='text-dark btn btn-danger btn-block'>Error: ".$connect_db->error."</b>";
            //$flash_message .= $connect_db->error; 
        }else {
            $flash_message = "<b class='text-white btn btn-success btn-block'>Success: we will keep in touch with you.</b>";
            include('notify_follow_up_team.php');
            //header("Refresh:4");
            #...if you need to redirect it to another page
            #header("Refresh:0; url=page2.php");
        }
    }
}

# Get Logged in Team data
if (isset($team_id)){
    $user = "SELECT * FROM follow_up_team WHERE id = '$team_id'";
    $user = $connect_db->query($user);
    $user_value = mysqli_fetch_array($user);
    $user_name = $user_value['fullname'];
    $user_phone = $user_value['phone'];
    $user_email = $user_value['email'];
    $user_vocation = $user_value['vocation'];
    $user_location = $user_value['location'];   
}

# Get Logged in User data
if (isset($user_id)){
    $user = "SELECT * FROM people WHERE id = '$user_id'";
    $user = $connect_db->query($user);
    $user_value = mysqli_fetch_array($user);
    $user_name = $user_value['fullname'];
    $user_phone = $user_value['phone'];
    $user_email = $user_value['email'];
    $user_vocation = $user_value['vocation'];
    $user_location = $user_value['location'];   
}


if (isset($_POST['visitor_sign_out'])) {
  // code...
  $id = $connect_db->real_escape_string($_POST['id']);
  $remark = $connect_db->real_escape_string($_POST['remark']);
  $sign_out = $sign_out;

  # CHECK IF EMAIL/REGNUMBER ALREADY EXISTS
  //$check_existing = $connect_db->query("SELECT * FROM window_book WHERE email = '$email' or phone = '$phone'");

  if ($remark == '') {
      // code...
      $flash_message = "<b class='text-dark btn btn-danger btn-block'>Please breifly tell us about your experience.</b>";
  }//elseif ($check_existing->num_rows != 0) {
      # code...
     // $flash_message = "<b class='text-dark btn btn-danger btn-block'>Membership profile already exists.</b>";
  //}
  else {
      $save_profile = "UPDATE visitors SET remark = '$remark', sign_out = '$sign_out' WHERE id=$id";
      if ($connect_db->query($save_profile) != TRUE) {
          // code...
          $flash_message = "<b class='text-dark btn btn-danger btn-block'>Error.<br /></b>";
          $flash_message .= $connect_db->error; 
      }else {
          $flash_message = "<b class='text-white btn btn-success btn-block'>Signed Out</b>";
          //include('notify_gurrantor.php');
          //header("Refresh:4");
          #...if you need to redirect it to another page
          #header("Refresh:0; url=page2.php");
      }
  }
}

# Decisions per day
$present_date_count = "SELECT COUNT(id) FROM people WHERE present_date = '$present_date'";
$present_date_count = $connect_db->query($present_date_count);
$present_date_count_value = mysqli_fetch_array($present_date_count);
$present_date_count_value = $present_date_count_value[0];

# Total Males
$total_males = "SELECT COUNT(id) FROM people WHERE gender ='Male'";
$total_males = $connect_db->query($total_males);
$total_males_value = mysqli_fetch_array($total_males);
$total_males_value = $total_males_value[0];

# Total Females
$total_females = "SELECT COUNT(id) FROM people WHERE gender ='Female'";
$total_females = $connect_db->query($total_females);
$total_females_value = mysqli_fetch_array($total_females);
$total_females_value = $total_females_value[0];

# Total Decisions
$total_decisions = "SELECT COUNT(id) FROM people";
$total_decisions = $connect_db->query($total_decisions);
$total_decisions_value = mysqli_fetch_array($total_decisions);
$total_decisions_value = $total_decisions_value[0];

# Total Waiting
$total_waiting = "SELECT COUNT(id) FROM people WHERE status = 'Inactive'";
$total_waiting = $connect_db->query($total_waiting);
$total_waiting_value = mysqli_fetch_array($total_waiting);
$total_waiting_value = $total_waiting_value[0];

# Total Female Waiting
$total_females_waiting = "SELECT COUNT(id) FROM people WHERE status = 'Inactive' AND gender = 'Female'";
$total_females_waiting = $connect_db->query($total_females_waiting);
$total_females_waiting_value = mysqli_fetch_array($total_females_waiting);
$total_females_waiting_value = $total_females_waiting_value[0];

# Total Male Waiting
$total_males_waiting = "SELECT COUNT(id) FROM people WHERE status = 'Inactive' AND gender = 'Male'";
$total_males_waiting = $connect_db->query($total_males_waiting);
$total_males_waiting_value = mysqli_fetch_array($total_males_waiting);
$total_males_waiting_value = $total_males_waiting_value[0];

# Fully Trained
$completed_training = "SELECT COUNT(id) FROM people WHERE level = 20";
$completed_training = $connect_db->query($completed_training);
$completed_training_value = mysqli_fetch_array($completed_training);
$completed_training_value = $completed_training_value[0];

# Daily Decisions in weekly perspective of the current year --------------------
$Mon = "SELECT COUNT(id) FROM people WHERE present_day = 'Mon'";
$Mon = $connect_db->query($Mon);
$Mon_value = mysqli_fetch_array($Mon);
$Mon_value = $Mon_value[0];

$Tue = "SELECT COUNT(id) FROM people WHERE present_day = 'Tue'";
$Tue = $connect_db->query($Tue);
$Tue_value = mysqli_fetch_array($Tue);
$Tue_value = $Tue_value[0];

$Wed = "SELECT COUNT(id) FROM people WHERE present_day = 'Wed'";
$Wed = $connect_db->query($Wed);
$Wed_value = mysqli_fetch_array($Wed);
$Wed_value = $Wed_value[0];

$Thu = "SELECT COUNT(id) FROM people WHERE present_day = 'Thu'";
$Thu = $connect_db->query($Thu);
$Thu_value = mysqli_fetch_array($Thu);
$Thu_value = $Thu_value[0];

$Fri = "SELECT COUNT(id) FROM people WHERE present_day = 'Fri'";
$Fri = $connect_db->query($Fri);
$Fri_value = mysqli_fetch_array($Fri);
$Fri_value = $Fri_value[0];

$Sat = "SELECT COUNT(id) FROM people WHERE present_day = 'Sat'";
$Sat = $connect_db->query($Sat);
$Sat_value = mysqli_fetch_array($Sat);
$Sat_value = $Sat_value[0];

$Sun = "SELECT COUNT(id) FROM people WHERE present_day = 'Sun'";
$Sun = $connect_db->query($Sun);
$Sun_value = mysqli_fetch_array($Sun);
$Sun_value = $Sun_value[0];

# Monthly View in Current Year Perspective ---------------------------------
$Jan = "SELECT COUNT(id) FROM people WHERE present_month = 01";
$Jan = $connect_db->query($Jan);
$Jan_value = mysqli_fetch_array($Jan);
$Jan_value = $Jan_value[0];

$Feb = "SELECT COUNT(id) FROM people WHERE present_month = 02";
$Feb = $connect_db->query($Feb);
$Feb_value = mysqli_fetch_array($Feb);
$Feb_value = $Feb_value[0];

$Mar = "SELECT COUNT(id) FROM people WHERE present_month = 03";
$Mar = $connect_db->query($Mar);
$Mar_value = mysqli_fetch_array($Mar);
$Mar_value = $Mar_value[0];

$Apr = "SELECT COUNT(id) FROM people WHERE present_month = 04";
$Apr = $connect_db->query($Apr);
$Apr_value = mysqli_fetch_array($Apr);
$Apr_value = $Apr_value[0];

$May = "SELECT COUNT(id) FROM people WHERE present_month = 05";
$May = $connect_db->query($May);
$May_value = mysqli_fetch_array($May);
$May_value = $May_value[0];

$Jun = "SELECT COUNT(id) FROM people WHERE present_month = 06";
$Jun = $connect_db->query($Jun);
$Jun_value = mysqli_fetch_array($Jun);
$Jun_value = $Jun_value[0];

$Jul = "SELECT COUNT(id) FROM people WHERE present_month = 07";
$Jul = $connect_db->query($Jul);
$Jul_value = mysqli_fetch_array($Jul);
$Jul_value = $Jul_value[0];

$Aug = "SELECT COUNT(id) FROM people WHERE present_month = 08";
$Aug = $connect_db->query($Aug);
$Aug_value = mysqli_fetch_array($Aug);
$Aug_value = $Aug_value[0];

$Sep = "SELECT COUNT(id) FROM people WHERE present_month = 09";
$Sep = $connect_db->query($Sep);
$Sep_value = mysqli_fetch_array($Sep);
$Sep_value = $Sep_value[0];

$Oct = "SELECT COUNT(id) FROM people WHERE present_month = 10";
$Oct = $connect_db->query($Oct);
$Oct_value = mysqli_fetch_array($Oct);
$Oct_value = $Oct_value[0];

$Nov = "SELECT COUNT(id) FROM people WHERE present_month = 11";
$Nov = $connect_db->query($Nov);
$Nov_value = mysqli_fetch_array($Nov);
$Nov_value = $Nov_value[0];

$Dec = "SELECT COUNT(id) FROM people WHERE present_month = 12";
$Dec = $connect_db->query($Dec);
$Dec_value = mysqli_fetch_array($Dec);
$Dec_value = $Dec_value[0];

# Six year Perspective ---------------------------------
$Year1 = "SELECT COUNT(id) FROM people WHERE present_year = 2021";
$Year1 = $connect_db->query($Year1);
$Year1_value = mysqli_fetch_array($Year1);
$Year1_value = $Year1_value[0];

$Year2 = "SELECT COUNT(id) FROM people WHERE present_year = 2022";
$Year2 = $connect_db->query($Year2);
$Year2_value = mysqli_fetch_array($Year2);
$Year2_value = $Year2_value[0];

$Year3 = "SELECT COUNT(id) FROM people WHERE present_year = 2023";
$Year3 = $connect_db->query($Year3);
$Year3_value = mysqli_fetch_array($Year3);
$Year3_value = $Year3_value[0];

$Year4 = "SELECT COUNT(id) FROM people WHERE present_year = 2024";
$Year4 = $connect_db->query($Year4);
$Year4_value = mysqli_fetch_array($Year4);
$Year4_value = $Year4_value[0];

$Year5 = "SELECT COUNT(id) FROM people WHERE present_year = 2025";
$Year5 = $connect_db->query($Year5);
$Year5_value = mysqli_fetch_array($Year5);
$Year5_value = $Year5_value[0];

$Year6 = "SELECT COUNT(id) FROM people WHERE present_year = 2026";
$Year6 = $connect_db->query($Year6);
$Year6_value = mysqli_fetch_array($Year6);
$Year6_value = $Year6_value[0];


// # Visitor's SignOut
// if (isset($_POST['sign_out'])) {
//     // code...
//     $bookclub_id = $connect_db->real_escape_string($_POST['membership_id']);

//     # fetch club_member_applicant's fullname for gurantor's confirmation
//     $fullname = "SELECT * FROM window_book WHERE membership_id = $bookclub_id";
//     $fullname = $connect_db->query($fullname);
//     if ($fullname->num_rows > 0) {
//       // output data of each row
//       while($fullname_row = $fullname->fetch_assoc()) {
//         $fullname_value = $fullname_row["fullname"];
//         $g_email = $fullname_row["g_email"];
//       }
//     } else {
//       $fullname_value = 'Unknown Person';
//     }

//     $update_gurantor_status = "UPDATE window_book SET guarantor_status='Approved' WHERE membership_id=$bookclub_id";
//         if ($connect_db->query($update_gurantor_status) != TRUE) {
//             // code...
//             $flash_message = "<b class='text-dark btn btn-danger btn-block'>Error saving response.<br /></b>";
//             $flash_message .= $connect_db->error; 
//         }else {
//             $flash_message = "<b class='text-white btn btn-success btn-block'>Success, thank you!</b>";
//             include('notify_gurrantor2.php');
//             #...if you need to redirect it to another page
//             #header("Refresh:2; url=index.php");
//         }
// }

// # Gurantor's Decline
// if (isset($_POST['guarantor_decline'])) {
//     // code...
//     $bookclub_id = $connect_db->real_escape_string($_POST['membership_id']);

//     # fetch club_member_applicant's fullname for guarantor's confirmation
//     $fullname = "SELECT * FROM window_book WHERE membership_id = $bookclub_id";
//     $fullname = $connect_db->query($fullname);
//     if ($fullname->num_rows > 0) {
//       // output data of each row
//       while($fullname_row = $fullname->fetch_assoc()) {
//         $fullname_value = $fullname_row["fullname"];
//         $g_email = $fullname_row["g_email"];
//       }
//     } else {
//       $fullname_value = 'Unknown Person';
//     }

//     $update_gurantor_status = "UPDATE window_book SET guarantor_status='Declined' WHERE membership_id=$bookclub_id";
//         if ($connect_db->query($update_gurantor_status) != TRUE) {
//             // code...
//             $flash_message = "<b class='text-dark btn btn-danger btn-block'>Error saving response.<br /></b>";
//             $flash_message .= $connect_db->error; 
//         }else {
//             $flash_message = "<b class='text-white btn btn-success btn-block'>Success, thank you!</b>";
//             include('notify_guarantor3.php');
//             #...if you need to redirect it to another page
//             #header("Refresh:2; url=index.php");
//         }
// }

# get number of signed in visitors
// $signed_in = "SELECT COUNT(id) FROM visitors WHERE sign_out = 'false'";
// $signed_in = $connect_db->query($signed_in);
// $sign_in_count = mysqli_fetch_array($signed_in);
// $sign_in_count = $sign_in_count[0];

# get signed in visitors
// $total_visits = "SELECT COUNT(id) FROM visitors";
// $total_visits = $connect_db->query($total_visits);
// $total_visits_value = mysqli_fetch_array($total_visits);
// $total_visits_value = $total_visits_value[0];

if (isset($_POST['scan_visitors_per_month'])) {
    // code...
    $given_month = $connect_db->real_escape_string($_POST['given_month']);

    # get number of visitors in a given month
    $monthly_visits = "SELECT COUNT(id) FROM visitors WHERE date LIKE '$given_month%'";
    $monthly_visits = $connect_db->query($monthly_visits);
    $monthly_visits_value = mysqli_fetch_array($monthly_visits);
    $monthly_visits_value = $monthly_visits_value[0];

    $flash_message = '<b class="bg-info card-header row text-white" style="font-size:20px;">Number of visitors in '.$given_month.' is: '.$monthly_visits_value. '</b>';

}


// # get number of pending users
// $pending_users = "SELECT COUNT(id) FROM window_book WHERE guarantor_status = 'Pending'";
// $pending_users = $connect_db->query($pending_users);
// $pending_count = mysqli_fetch_array($pending_users);
// $pending_count = $pending_count[0];

// # get number of declined users
// $declined_users = "SELECT COUNT(id) FROM window_book WHERE guarantor_status = 'Declined'";
// $declined_users = $connect_db->query($declined_users);
// $declined_count = mysqli_fetch_array($declined_users);
// $declined_count = $declined_count[0];

// # check if any declined exist for table highlighting
// $check_decline_status = "SELECT COUNT(membership_id) FROM window_book WHERE guarantor_status = 'Declined'";
// $check_decline_status = $connect_db->query($check_decline_status);
// $decline_value = mysqli_fetch_array($check_decline_status);
// $decline_value = $decline_value[0];

?>