<?php 
    // CONNECT TO DATABASE
    include_once('../App/connect.php');

    if ( isset($_POST['register']))
    {
        $FirstName           = mysqli_real_escape_string($conn,$_POST['FirstName']);
        $LastName           = mysqli_real_escape_string($conn,$_POST['LastName']);
        $Email              = mysqli_real_escape_string($conn,$_POST['Email']);
        $Password          = mysqli_real_escape_string($conn,$_POST['Password1']);
        $Password2          =  mysqli_real_escape_string($conn,$_POST['Password2']);
        
  
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
                            PlatformContributors(PlatformContributorsID, CreatedDate, ModifiedDate, Deleted, DeletedDate, FirstName, LastName, Email, Password) 
                        VALUES 
                            (uuid(), now(), now(),0,NULL,  '$FirstName','$LastName','$Email', '$Password')
            ";

            $queryUser = mysqli_query($conn, $sqlUser);
            
            if($queryUser){    
                
                // ===========================================================
                // REFRESH PAGE TO SHOW NEW ENTRIES IF INSERTION WAS A SUCCESS
                // ===========================================================
                header( "refresh: 3; url= login.php" ); 
                echo 
                '<div style="background-color:#d1e7dd; color: #0f5132; margin:0;">
                    <H4>Success!</H4>
                    <p style="margin:0;"> <small> Registration successful! Please login with your credentials.</small> </p>
                </div>'
                ;
               
            }else{
                echo 'Oops! There was an error creating new contact'.mysqli_error($conn);
            }
            $conn->close();
            
        }
    }
?>