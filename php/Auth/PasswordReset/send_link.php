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
                    $HashEmail=md5($row['Email']);
                    $HashPassword=md5($row['Password']);
                }

                $link="<a href='http://localhost/vc-reportstream/php/Auth/PasswordReset/reset_pass.php?key=".$HashEmail."&reset=".$HashPassword."'>Click To Reset password</a>";

                
                $mail = new PHPMailer\PHPMailer(true);

                $mail->CharSet =  "utf-8";
                $mail->IsSMTP();
                // enable SMTP authentication
                $mail->SMTPAuth = true;                  
                // GMAIL username
                $mail->Username = "henrysparks1@gmail.com";
                // GMAIL password
                $mail->Password = "KingHenry99";
                $mail->SMTPSecure = "ssl";  
                // sets GMAIL as the SMTP server
                $mail->Host = "smtp.gmail.com";
                // set the SMTP port for the GMAIL server
                $mail->Port = "465";
                $mail->From='henrysparks1@gmail.com';
                $mail->FromName='Henry Javangwe';
                $mail->AddAddress($Email);
                $mail->Subject  =  'Reset Password';
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