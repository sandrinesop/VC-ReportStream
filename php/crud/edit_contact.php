<?php 
    include_once('../App/connect.php');
    // QUERY DATABASE FROM DATA
    $UserDetailID =$_REQUEST['UserDetailID'];
    $sql=" SELECT *  FROM UserDetail where UserDetailID = '$UserDetailID'"; 
    $result = mysqli_query($conn, $sql) or die($conn->error);
    $row = mysqli_fetch_assoc($result);

    $tempRoleType = $row['RoleTypeID'];
    $sql2 = " SELECT RoleType FROM RoleType WHERE RoleTypeID = '$tempRoleType' ";
    $result2 = mysqli_query($conn, $sql2) or die($conn->error);
    $row2 = mysqli_fetch_assoc($result2);
    
    // Access the Currency table to get currency name
    $tempGender = $row['GenderID'];
    $sql3 = " SELECT Gender FROM Gender WHERE GenderID = '$tempGender'";
    $result3 = mysqli_query($conn, $sql3) or die($conn->error);
    $row3 = mysqli_fetch_assoc($result3);

    // Access the Currency table to get currency name
    $tempRace = $row['RaceID'];
    $sql4 = " SELECT Race FROM Race WHERE RaceID = '$tempRace'";
    $result4 = mysqli_query($conn, $sql4) or die($conn->error);
    $row4 = mysqli_fetch_assoc($result4);

    $status = "";
    if(isset($_POST['new']) && $_POST['new']==1)
    {
        $status = "Record Updated Successfully. </br></br>
        <a href='../tabs/contacts.php'>View Updated Record</a>";
        echo '<p style="color:#FF0000;">'.$status.'</p>';
        header( "refresh: 5;url= ../tabs/contacts.php" );
        
        // CHECK IF VARIABLES ARE SET OR NOT BEFORE WORKING WITH THEM IN THE UPDATE QUERY
        // ALSO USE STRING ESCAPE TO ESCAPE OUT ALL SPECIAL CHARACTERS AND AVOID SQL INJECTION
        
        if(isset($_POST['UserFullName'])){ 
            $UserFullName          = mysqli_real_escape_string($conn, $_POST['UserFullName']);
        }else {
            // error_reporting(0);
        }        
        if(isset($_POST['FirstName'])){ 
            $FirstName              = mysqli_real_escape_string($conn, $_POST['FirstName']);
        }else {
            // error_reporting(0);
        }        
        if(isset($_POST['LastName'])){ 
            $LastName               = mysqli_real_escape_string($conn, $_POST['LastName']);
        }else {
            // error_reporting(0);
        }        
        if(isset($_POST['ContactNumber1'])){ 
            $ContactNumber1         = mysqli_real_escape_string($conn, $_POST['ContactNumber1']);
        }else {
            // error_reporting(0);
        }        
        if(isset($_POST['ContactNumber2'])){ 
            $ContactNumber2         = mysqli_real_escape_string($conn, $_POST['ContactNumber2']);
        }else {
            // error_reporting(0);
        }        
        if(isset($_POST['Email'])){ 
            $Email                  = mysqli_real_escape_string($conn, $_POST['Email']);
        }else {
            // error_reporting(0);
        }        
        if(isset($_POST['RoleType'])){ 
             $RoleType               = mysqli_real_escape_string($conn, $_POST['RoleType']);
        }else {
            // error_reporting(0);
        }       
        if(isset($_POST['Gender'])){ 
            $Gender                 = mysqli_real_escape_string($conn, $_POST['Gender']);
        }else {
            // error_reporting(0);
        }       
        if(isset($_POST['Race'])){ 
            $Race                   = mysqli_real_escape_string($conn, $_POST['Race']);
        }else {
            // error_reporting(0);
        }
        
        // DescriptionID='".$Description."',
        // $update="UPDATE UserDetail SET ModifiedDate=UUID(),UserFullName='".$UserFullName."', FirstName='".$FirstName."', LastName='".$LastName."', ContactNumber1='".$ContactNumber1."', ContactNumber2='".$ContactNumber2."', Email='".$Email."', RoleTypeID =  (select RoleType.RoleTypeID FROM RoleType where RoleType.RoleType = '$RoleType'), GenderID =(select Gender.GenderID FROM Gender where Gender.Gender = '$Gender'), RaceID = (select Race.RaceID FROM Race where Race.Race = '$Race') where UserDetailID='".$UserDetailID."'";
        // mysqli_query($conn, $update) or die($conn->error);$updatesNote = array();

        // BUILD A DYNAMIC QUERY TO UPDATE THE RECORD WITH ONLY VARIABLES THAT WERE SET OR FILLED IN ON THE UPDATE FORM
        $updateContact = array();

        if(!empty($UserFullName)){
            $updateContact[] ="UserFullName='".$UserFullName."'";
        };


        if(!empty($FirstName)){
            $updateContact[] ="FirstName='".$FirstName."'";
        };
        
        if(!empty($LastName)){
            $updateContact[] ="LastName='".$LastName."'";
        };

        if(!empty($ContactNumber1)){
            $updateContact[] ="ContactNumber1='".$ContactNumber1."'";
        };

        if(!empty($ContactNumber2)){
            $updateContact[] ="ContactNumber2='".$ContactNumber2."'";
        };

        if(!empty($Email)){
            $updateContact[] ="Email='".$Email."'";
        };

        if(!empty($RoleType)){
            $updateContact[] =" RoleTypeID = (SELECT RoleType.RoleTypeID FROM RoleType WHERE RoleType.RoleType = '$RoleType')";
        };

        if(!empty($Gender)){
            $updateContact[] =" GenderID = (SELECT Gender.GenderID FROM Gender WHERE Gender.Gender = '$Gender')";
        };

        if(!empty($Race)){
            $updateContact[] =" RaceID = (SELECT Race.RaceID FROM Race WHERE Race.Race = '$Race')";
        };
        // ECHO OUT THE ARRAY TO SEE IF VARIABLES ARE PULLING THROUGH PROPERLY.
        // print_r($updateContact);
        
        $updateContactString = implode(', ', $updateContact);
        
        $updateContactQuery = "UPDATE UserDetail SET ModifiedDate= NOW(), $updateContactString  WHERE UserDetailID='".$UserDetailID."'";
        // echo $updateNote;
        $resultContactUpdate = mysqli_query($conn, $updateContactQuery);
        if($resultContactUpdate){
            // do nothing
        }else{
            echo 'error: '.mysqli_error($conn); 
        }
    }else {
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="../../resources/DCA_Icon.png" type="image/x-icon">
        <title>Update Record </title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
        <link rel="stylesheet" href="../../css/main.css">
    </head>
    <body>
    <!-- HEADER CONTENT -->
        <nav class="container navbar navbar-expand-lg align-middle" style="z-index: 1;">
            <div class="container-fluid">
                <a style="color:#ffffff;" class="navbar-brand" href="../../index.php"><img style=" width: 80px;" class="home-ico" src="../../resources/DCA_Icon.png" alt="Digital collective africa logo"> VC Reportstream  </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="https://www.digitalcollective.africa/ " target="_blank" >Digital Collective Africa</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../../WebInterface.php">New Deal</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Contact</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <main  class="my-5 ">
            <form name="form" method="post" action="" class="form container"> 
                <div class="row">
                    <input type="hidden" name="new" value="1" />
                    <input name="UserDetailID" type="hidden" value="<?php echo $row['UserDetailID'];?>" />
                    <p class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                        <label for="UserFullName" class="form-label" >User Full Name </label> <br>
                        <input class="form-control col" type="text" name="UserFullName" placeholder=" Enter UserFullName" required value="<?php echo $row['UserFullName'];?>" />
                    </p>
                    <p class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                        <label for="FirstName" class="form-label" >First Name </label> <br>
                        <input class="form-control col" type="text" name="FirstName" placeholder="Enter FirstName"  value="<?php echo $row['FirstName'];?>" />
                    </p>
                    <p class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                        <label for="LastName" class="form-label" >Last Name </label> <br>
                        <input class="form-control col" type="text" name="LastName" placeholder="Enter LastName"  value="<?php echo $row['LastName'];?>" /></p>
                    <p class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                        <label for="ContactNumber1" class="form-label" >Contact Number 1 </label> <br>
                        <input class="form-control col" type="text" name="ContactNumber1" placeholder="Enter ContactNumber1"  value="<?php echo $row['ContactNumber1'];?>" />
                    </p>
                    <p class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                        <label for="ContactNumber2" class="form-label" >Contact Number 2</label> <br>
                        <input class="form-control col" type="text" name="ContactNumber2" placeholder="Enter ContactNumber2"  value="<?php echo $row['ContactNumber2'];?>" />
                        </p>
                    <p class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                        <label for="Email" class="form-label" >Email </label> <br>
                        <input class="form-control col" type="text" name="Email" placeholder="Enter Email"  value="<?php echo $row['Email'];?>" />
                        </p>
                    <p class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                        <label for="RoleType" class="form-label" >RoleType </label> <br>
                        <!-- <input class="form-control col" type="text" name="RoleType" placeholder="Enter RoleType"  value="<?php echo $row2['RoleType'];?>" /> -->
                        <select name="RoleType" class="form-select" id="RoleType" required>
                            <option selected ><?php echo $row2['RoleType'];?></option>
                            <option value="President">President</option>
                            <option value="CEO">CEO</option>
                            <option value="CFO">CFO</option>
                            <option value="COO">COO</option>
                        </select>
                    </p> 
                    <p class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                        <label for="Gender" class="form-label" >Gender </label> <br>
                        <!-- <input class="form-control col" type="text" name="Gender" placeholder="Enter Gender"  value="<?php echo $row3['Gender'];?>" /> -->
                        <select name="Gender" class="form-select" id="Gender" required>
                            <option selected ><?php echo $row3['Gender'];?></option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Unknown">Unknown</option>
                        </select>
                    </p>
                    <p class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                        <label for="Race" class="form-label" >Race </label> <br>
                        <!-- <input class="form-control col" type="text" name="Race" placeholder="Enter Race"  value="<?php echo $row4['Race'];?>" /> -->
                        <select name="Race" class="form-select" id="Race" required>
                            <option selected ><?php echo $row4['Race'];?></option>
                            <option value="Black">Black</option>
                            <option value="White">White</option>
                            <option value="Asian">Asian</option>
                            <option value="Indian">Indian</option>
                            <option value="Unknown">Unknown</option>
                        </select>
                    </p>
                </div>
                <p >
                    <Button name="Update" type="submit" value="Update" class="btn btn-primary" formmethod="POST">Update</Button>
                    <a href="../tabs/contacts.php" class="btn btn-danger" >Close</a>
                </p>
            </form>
            <?php } ?>
        </main>
    </body>
</html>
