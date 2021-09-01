<?php
    include('./connect.php');


    if ( isset($_POST['submit']))
    {
        // DEFINED VAR FOR THE SECOND TABLE
        // PORTFOLIO COMPANY TABLE
        $Email    = mysqli_real_escape_string($conn, $_POST['Email']);

        // echo $Email;

        // ===========================================================================================================
        // THE CODE BLOCK BELOW CHECKS IF RECORD ALREADY EXISTS IN THE DB OR NOT. WE'LL USE THIS TO PREVENT DUPLICATES
        // ===========================================================================================================
        $DuplicateCheck = " SELECT Email FROM NewsLetter_Subscriptions WHERE NewsLetter_Subscriptions.Email ='$Email'";
        $checkResult = mysqli_query($conn, $DuplicateCheck);

        if($checkResult -> num_rows >0){
            header( "refresh: 3; url = ../../index.php" );
            echo 
                '<div style="background-color:#f8d7da; color: #842029; margin:0;">
                    <H4>Heads Up!</H4>
                    <p style="margin:0;"> <small>You are already subscribed.</small> </p>
                </div>'
            ;
        }else{
            // PORTFOLIO COMPANY INSERT
            $sql = "INSERT INTO 
                        newsletter_subscriptions( NewsLetter_SubscriptionsID, CreatedDate, ModifiedDate, Deleted, DeletedDate, Email)
                    VALUES 
                        (uuid(), now(), now(), 0, NULL,'$Email')
            ";
            $query = mysqli_query($conn, $sql);
            header( "refresh: 3; url = ../../index.php" );
            echo 
                '<div style="background-color:green; color: #ffffff; margin:0;">
                    <H4>Thank You!</H4>
                    <p style="margin:0;"> <small>Succesfully Subscribed.</small> </p>
                </div>'
            ;

        }
    }
?>