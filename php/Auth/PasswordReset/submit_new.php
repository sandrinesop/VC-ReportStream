<?php
  // Include Data base connection
  include_once('../../App/connect.php');
  // If the form was submitted, the script will capture the values and update the password in the database.
  if(isset($_POST['submit_Password']))
  {
    // set variables and make sure to prevent sql injection attacks by escaping all strings. 
    $Email= mysqli_real_escape_string($conn, $_POST['Email']);
    $Password= mysqli_real_escape_string($conn, $_POST['Password']);

    // first hash the password for security
    $hashedPassword = password_hash($Password, PASSWORD_DEFAULT);

    // update the password in the database table
    if(!empty($_POST['Password'])){      
      $sql=" UPDATE PlatformContributors SET Password='$hashedPassword' WHERE Email='$Email'";
      $result =mysqli_query($conn, $sql);
      // if query was successful then output a message and redirect users to go and login using their new password
      if($result){
        // we send headers before anything else otherwise we will encounter errors
        header( "refresh:2 ; url=../login.php" );
          echo 
          ' <h5>Password reset successful!<h5/>
            <p>Please login with your new password.</p> 
          ';
        
      }else{
        // we send headers before anything else otherwise we will encounter errors
        header( "refresh:2 ; url=../login.php" );
        echo 'Password reset failed! Please try again later.';
      }
    }else{
      // we send headers before anything else otherwise we will encounter errors
      header( "refresh:5 ; url=./reset_pass.php" );
      echo' Password cannot be blank';
    }
    exit;
  }
?>