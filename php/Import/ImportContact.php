<?php
    include_once('../App/connect.php');
    if(isset($_POST['ImportSubmit'])){
        // ONLY ALLOWED MIME TYPES
        $csvMimes = array('application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', );
        // VALIDATE WHETHER OR NOT A FILE UPLOADED IS A CSV FILE TYPE 
        if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)){
            // CHECK IF FILE UPLOADED SUCCESSFULLY
            if(is_uploaded_file($_FILES['file']['tmp_name'])){
                // OPEN UPLOADED FILE IN READ ONLY MODE
                $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
                // SKIP THE FIRST LINE
                fgetcsv($csvFile);
                // PARSE DATA FROM CSV FILE, LINE BY LINE USING A WHILE LOOP
                // DEFINE AN ARRAY OUTSIDE TEH LOOP AND THEN POPULATE IT WITH VALUES FROM THE WHILE LOOP WITH EVERY ITERATION AND THEN USE IT TO OUTOUT A LIST WITH NAMES OF TEH COMPANIES WWHICH ALREADY EXIST IN THE DB.
                $msg = array();
                while(($line = fgetcsv($csvFile))!== FALSE){
                    // Declare variables and pass in data from the csv file
                    $UserFullName         = mysqli_real_escape_string($conn,$line[0]);
                    $FirstName            = mysqli_real_escape_string($conn,$line[1]);
                    $LastName             = mysqli_real_escape_string($conn,$line[2]);
                    $ContactNumber1       = mysqli_real_escape_string($conn,$line[3]);
                    $ContactNumber2       = mysqli_real_escape_string($conn,$line[4]);
                    $Email                = mysqli_real_escape_string($conn,$line[5]);
                    $RoleType             = mysqli_real_escape_string($conn,$line[6]);  
                    $Gender               = mysqli_real_escape_string($conn,$line[7]); 
                    $Race                 = mysqli_real_escape_string($conn,$line[8]);    

                    // echo 'Contacts CSV Data: <br/>'
                    // .$UserFullName.'<br/>'
                    // .$FirstName.'<br/>'
                    // .$LastName .'<br/>'
                    // .$ContactNumber1.'<br/>'
                    // .$ContactNumber2 .'<br/>'
                    // .$Email.'<br/>'
                    // .$RoleTypeID .'<br/>'
                    // .$GenderID.'<br/>'
                    // .$RaceID .'<br/>';

                    // =============================================
                    // CHECK FOR RECORDS IN THE DB TABLE THAT ARE ON THE CSV BEFORE INSERTING
                    // =============================================
                    $sql = "  SELECT 
                                UserFullName 
                            FROM 
                                UserDetail 
                            WHERE 
                                UserFullName = '".$line[0]."'
                    ";
                    $result = mysqli_query($conn,$sql);

                    if(!empty($result) && $result->num_rows>0){
                        // means contact is already in the database so simply ignore and add the found record into the array so we can know which records already exists.
                        $msg[] =$UserFullName;
                    }else{
                        // THIS CODE BLOCK FIRES IF NO RECORDS ARE FOUND, SO THE PROGRAM WILL PROCEED TO INSERT NEW RECORDS TO THE DB
                        
                        // Refresh the page after exceuting below functions logic
                        header( "refresh: 5; url= ../AuthViews/contacts.php" );

                        // Insert and create a new contact then redirect back to thecontact page(added header function first because if set below or after echos then it will not work.)
                        $UserDetail = "INSERT INTO 
                                        UserDetail(UserDetailID, CreatedDate, ModifiedDate, Deleted, DeletedDate, UserFullName, FirstName, LastName, ContactNumber1, ContactNumber2, Email, RoleTypeID, GenderID, RaceID )
                                    VALUES 
                                        (uuid(), now(), now(), 0, null,'$UserFullName','$FirstName','$LastName','$ContactNumber1','$ContactNumber2','$Email', (SELECT R.RoleTypeID FROM RoleType R WHERE R.RoleType = '$RoleType' ), (SELECT G.GenderID FROM Gender G WHERE G.Gender = '$Gender' ), (SELECT RC.RaceID FROM Race RC WHERE RC.Race = '$Race' ) )
                        ";
                        $result = mysqli_query($conn, $UserDetail);

                        // check if the record inserted/created succesfully. otherwise print the error
                        if ($result ){
                            // Success
                        } else {
                            echo 'Oops! There was an error importing contacts. Please report bug to support.'
                            .'<br/>'.mysqli_error($conn);
                        }
                    }
                }
                // ==================================================================
                // FROM HERE THE MSG ARRAY  HAS BEEN CREATED AND NOW READY TO BE USED.
                // NEXT UP, CREATE A VARIABLE AND THEN POPULATE IT WITH THE LENGTH OF THE ARRAY -> WHICH YOU WILL GET BY USING THE PHP COUNT() FUNCTION TO GET THE ARRAY LENGTH.
                // ==================================================================

                $arrLength = count($msg);
                if($arrLength>0){
                    echo'
                        <p style="color:green; font-size:20px;">
                            All unique records were imported successfully!
                        <p/>
                        <small>
                            Except for the following contact(s) which already exists in the database:
                        </small>
                        ';
                    for($i=0; $i<$arrLength; $i++){
                        // Used the HTML elements and inlise CSS to format the output in a desirable way by making the font red. 
                        // concatenated the varible i which we initialzed if the for loop, added one to it and then appended that next to the value of the array to create an ordered-numbered list. Added an empty string to add space between.
                        echo '<p style="color:red;">'.$i+'+1'.'. '.$msg[$i].'<p/>';
                    }

                    // Add a button to go back 
                    echo '<br>'.'<a href="../AuthViews/contacts.php" style="padding:3px; border:1px solid red;font-size:18px; text-decoration:none;"> Go Back </a>';
                }else{
                    echo
                    '<div style="color:green; font-size:20px;">
                        <p>All records imported successfully! You will be redirected back in 5 sec... <p/>
                        <a href="../AuthViews/contacts.php" style="padding:3px; border:1px solid red;font-size:18px; text-decoration:none;"> Go Back </a>
                    </div>';
                }
                // CLOSE CSV FILE
                fclose($csvFile);
            }else{
                echo 'file not uploaded';
            }
        }else{
            // die("Error: ");
            die(
                '<div style="color:red; font-size:20px;">
                    <p>Error: file not uploaded/ file type not a csv  <p/>
                    <a href="../AuthViews/contacts.php" style="margin-top:5px; padding:5px; border:1px solid red;font-size:18px; text-decoration:none;"> Go Back </a>
                </div>'
            );
        }
    }
?>