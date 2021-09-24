<?php 
    include_once('../../App/connect.php');

    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function    
    
    require('PHPMailer/Exception.php');    
    require('PHPMailer/SMTP.php');    
    require('PHPMailer/PHPMailer.php');
    
    use PHPMailer\PHPMailer;
    use PHPMailer\SMTP;
    use PHPMailer\Exception;

    if(isset($_POST['submit_email'])){
        // check if variables are set
        if(isset($_POST['Email']) && !empty($_POST['Email'])){
            // ================================================
            // If the email is field is filled in and submitted
            // Create the var email and then populate it
            // ================================================
            $Email = mysqli_real_escape_string($conn, $_POST['Email']);

            $sql = " SELECT Email, Password FROM PlatformContributors WHERE Email='$Email'";
            $result = mysqli_query($conn, $sql);

            if( $result ->num_rows==1){
                while($row=mysqli_fetch_assoc($result))
                {
                    // Create variables and populate them with data from the query
                    $Email= $row['Email'];
                    $Password=$row['Password'];

                    // encode the variables to hide the data when transporting it via the reset link
                    $EncodedEmail = base64_encode($Email);
                    $EncodedPassword = base64_encode($Password);
                    
                }

                $link="<a href='https://vcrep.digitalcollective.africa/php/Auth/PasswordReset/reset_pass.php?key=".$EncodedEmail."&reset=".$EncodedPassword."'>Click To Reset password</a>";

                $mail = new PHPMailer\PHPMailer(true);

                $mail->CharSet =  "utf-8";
                $mail->IsSMTP();
                // enable SMTP authentication
                $mail->SMTPAuth = true;                  
                // GMAIL username
                $mail->Username = "****eneter your email****";
                // GMAIL password
                $mail->Password = "****eneter your passowrd****";
                $mail->SMTPSecure = "ssl";  
                // sets GMAIL as the SMTP server
                $mail->Host = "smtp.gmail.com";
                // set the SMTP port for the GMAIL server
                $mail->Port = "465";
                $mail->From='****eneter your email****';
                $mail->FromName='Digital Collective Afriva';
                $mail->AddAddress($Email);
                $mail->Subject  =  'Reset Password ';
                $mail->IsHTML(true);
                $mail->Body    = 'Click On This Link to Reset Password '.$link.'';
                if($mail->Send())
                {
                    echo "Check Your Email and Click on the link sent to your email" ."<br/>"."<br/>";
                    echo"<button style='background-color:red; padding:5px;'><a style='text-decoration:none; color:#ffffff;' href=\"../../../index.php\"> Home </a></button>";
                    
                }
                else
                {
                    echo "Mail Error - >".$mail->ErrorInfo;
                }
            }
        } else{
            // do nothing
            echo 'Nothing here: ';
        }
    }
?>
