<?php 
    // CONNECT TO DATABASE
    include_once('../App/connect.php');

    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function    
    // We need to send an email alerting the admin that there is a new user who registered.
    require('PasswordReset/PHPMailer/Exception.php');    
    require('PasswordReset/PHPMailer/SMTP.php');    
    require('PasswordReset/PHPMailer/PHPMailer.php');
    
    use PHPMailer\PHPMailer;
    use PHPMailer\SMTP;
    use PHPMailer\Exception;

    if ( isset($_POST['register']))
    {
        $FirstName           = mysqli_real_escape_string($conn,$_POST['FirstName']);
        $LastName           = mysqli_real_escape_string($conn,$_POST['LastName']);
        $Email              = mysqli_real_escape_string($conn,$_POST['Email']);
        $LinkedIn              = mysqli_real_escape_string($conn,$_POST['LinkedIn']);
        $Password          = mysqli_real_escape_string($conn,$_POST['Password1']);
        $Password2          =  mysqli_real_escape_string($conn,$_POST['Password2']);
        
        $hashedPassword = password_hash($Password, PASSWORD_DEFAULT);

        if($Password !== $Password2){
            echo '<div>';
                echo 
                    '<p>Passwords do not match, please try again.<p/>' 
                    .'<br/>'
                    .'<a href="./register.php" style="padding:3px; border:1px solid red;font-size:18px; text-decoration:none;"> Go Back </a>';
                ;
            echo '</div>';

            exit;
        }

        // ===========================================================================================================
        // THE CODE BLOCK BELOW CHECKS IF RECORD ALREADY EXISTS IN THE DB OR NOT. WE'LL USE THIS TO PREVENT DUPLICATES
        // ===========================================================================================================
        $DuplicateCheck = " SELECT 
                                Email 
                            FROM 
                                PlatformContributors 
                            WHERE 
                                PlatformContributors.Email ='$Email' 
        ";
        $checkResult = mysqli_query($conn, $DuplicateCheck);

        if($checkResult -> num_rows >0){
            // ===========================================================
            // REFRESH PAGE TO SHOW NEW ENTRIES IF INSERTION WAS A SUCCESS
            // ===========================================================
            header( "refresh: 5; url= register.php" ); 
            echo 
                '<div style="background-color:#f8d7da; color: #842029; margin:0;">
                    <H3>Heads Up!</H3>
                    <p style="margin:0;"> <small>User not registered, the user already exists.</small> </p>
                </div>'
            ;
            $conn->close();
        }else{
            
            // ===========================================================
            // REFRESH PAGE TO SHOW NEW ENTRIES IF INSERTION WAS A SUCCESS
            // ===========================================================
            $sqlUser =" INSERT INTO 
                            PlatformContributors(PlatformContributorsID, CreatedDate, ModifiedDate, Deleted, DeletedDate, FirstName, LastName, Email, LinkedIn, Password) 
                        VALUES 
                            (uuid(), now(), now(),0,NULL,  '$FirstName','$LastName','$Email','$LinkedIn', '$hashedPassword')
            ";

            $queryUser = mysqli_query($conn, $sqlUser);
            
            if($queryUser){    
                
                // ===========================================================
                // REFRESH PAGE TO SHOW NEW ENTRIES IF INSERTION WAS A SUCCESS
                // ===========================================================
                header( "refresh: 3; url= login.php" ); 
                echo 
                '<div style="background-color:#d1e7dd; color: #0f5132; margin:0;">
                    <H4>Registration successful!</H4>
                    <p style="margin:0;"> 
                        Please login with your credentials.
                        <br/>
                        <small>It may take 24hrs - 48hrs to verify your account. Thank you for your patience.</small> 
                    </p>
                </div>'
                ;


               
            }else{
                echo 'Oops! There was an error creating new contact'.mysqli_error($conn);
            }
            $conn->close();
            
        }
    }
?>