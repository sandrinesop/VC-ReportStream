<?php
    // ======================================
    // STARTING A NEW SESSION FOR EACH USER
    // ======================================
    session_start();  

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
                    PlatformContributorsID,FirstName, LastName, Email, Password 
                FROM 
                    PlatformContributors 
                WHERE  
                    PlatformContributors.Email ='$Email'
                AND  
                    PlatformContributors.Password ='$Password'
        ";
        $query = mysqli_query($conn, $sql);

        if($query -> num_rows >0){
            // ===========================================================
            // IF ROWS ARE RETURNED THEN USER EXISTS ON THE DB SO PROCEED
            // ===========================================================
            $row = mysqli_fetch_assoc($query);
            $UserID     = $row['PlatformContributorsID'];
            $Email      = $row['Email'] ;
            $UserName   = $row['FirstName'].' '.$row['LastName'];
            // echo 
            //     '<div style=" background-color:#d1e7dd; color: #0f5132; margin:0;">
            //         <H4>Loggged In!</H4>
            //         <p style="margin:0;"> <small>You have been successfully logged in.</small> </p>
            //     </div>'
            // ;
            $_SESSION['Email'] = $Email;
            $_SESSION['UserID'] = $UserID;
            $_SESSION['UserName'] = $UserName;

            // REDIRECT THE USER TO THE ADMIN PANEL
            header('refresh: 1; url = ../../Admin.php');
            exit;
        }else{
            // ===========================================================
            // USER DES NOTE EXIST SO DO NOT GIVE ACCESS
            // ===========================================================
            echo 
            '<div style="background-color:#f8d7da; color: #842029; margin:0;">
                <H4>Login Failed!</H4>
                <p style="margin:0;"> <small> Your login attempt failed. Please try again with the right credentials</small> </p>
            </div>'
            ;
            $_SESSION = [];
            session_destroy();  
            echo '<a href="./login.php">Back</a>';          
        }

        echo '<pre>';

        print_r($_SESSION);

        echo '</pre>';
    }
?>