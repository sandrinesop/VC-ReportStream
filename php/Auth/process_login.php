<?php
    // ======================================
    // STARTING A NEW SESSION FOR EACH USER
    // ======================================
    session_start();  
    error_reporting(0); 

    // CONNECT TO DATABASE
    include_once('../App/connect.php');
 
    if ( isset($_POST['login']))
    {
        $Email             = mysqli_real_escape_string($conn,$_POST['Email']);
        $Password          = mysqli_real_escape_string($conn,$_POST['password1']);

        // echo 'Email: '.$Email.  '<br/>'.'Password '.$Password ;
        // ===========================================================================================================
        // THE CODE BLOCK BELOW CHECKS IF RECORD ALREADY EXISTS IN THE DB OR NOT. WE'LL USE THIS TO PREVENT DUPLICATES
        // ===========================================================================================================
        $sql = " SELECT 
                    PlatformContributorsID, FirstName, LastName, Email, Password 
                FROM 
                    PlatformContributors 
                WHERE 
                    PlatformContributors.Email ='$Email' 
        ";
        $query = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($query); 
        // GRAB PASSWORD FROM DB AND SAVE IT TO THIS HASH VARIABLE
        $hash = $row['Password'];

        if($query -> num_rows >0){
            // ===========================================================
            // IF ROWS ARE RETURNED THEN USER EXISTS ON THE DB SO PROCEED
            //NEXT STEP WE CHECK IF PASSWORD SUBMITTED MATCHES PASSWORD HASHED IN THE DB
            // ===========================================================
            if (password_verify($Password, $hash)) {
                // Success!
                // NOW CHECK IF USER IS VERIFIED YET
                $sql2 = " SELECT 
                        PlatformContributorsID,Verified, FirstName, LastName, Email, Password 
                    FROM 
                        PlatformContributors 
                    WHERE 
                        PlatformContributors.Verified = 1
                    AND  
                        PlatformContributors.Email ='$Email'
                ";
                $query2 = mysqli_query($conn, $sql2);
    
                $row2 = mysqli_fetch_assoc($query2);

                $Verified = $row2['Verified'];
                if($Verified == 0){
                    echo 
                    '<div style="background-color:#f8d7da; color: #842029; margin:0;">
                        <H4>Login Failed!</H4>
                        <p style="margin:0;"> <small> User not yet Verified. Please try again later. </small> </p>
                        <br/>
                        <button style="background-color:red; padding:5px;">
                            <a style="text-decoration:none; color:#ffffff;" href="../../index.php"> Home </a>
                        </button>
                    </div>';
                    exit;
                }else {
                    $UserID     = $row['PlatformContributorsID'];
                    $Email      = $row['Email'] ;
                    $UserName   = $row['FirstName'].' '.$row['LastName'];
    
                    $_SESSION['Email'] = $Email;
                    $_SESSION['UserID'] = $UserID;
                    $_SESSION['UserName'] = $UserName;
                    // REDIRECT THE USER TO THE ADMIN PANEL
                    header('refresh: 1; url = ../../Admin.php');
                    exit;
                }
            }
            else {
                echo 'Your login attempt failed. Password is incorrect'.'<br/>'
                .
                ' <button style="background-color:red; padding:5px;">
                     <a style="text-decoration:none; color:#ffffff;" href="../../index.php"> Home </a>
                  </button>';
            }

        }else{
            // ===========================================================
            // USER DES NOTE EXIST SO DO NOT GIVE ACCESS
            // ===========================================================
            echo 
            '<div style="background-color:#f8d7da; color: #842029; margin:0;">
                <H4>Login Failed!</H4>
                <p style="margin:0;"> <small> Your login attempt failed. User does not exist</small> </p>
            </div>'
            ;
            $_SESSION = [];
            session_destroy();  
            echo '<a href="./login.php">Back</a>';          
        }

        // echo '<pre>';

        // // print_r($_SESSION);

        // echo '</pre>';
    }
?>