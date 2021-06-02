<?php 
    include_once('../connect.php');
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
        $UserFullName           = $_REQUEST['UserFullName'];
        $FirstName              = $_REQUEST['FirstName'];
        $LastName               = $_REQUEST['LastName'];
        $ContactNumber1         = $_REQUEST['ContactNumber1'];
        $ContactNumber2         = $_REQUEST['ContactNumber2'];
        $Email                  = $_REQUEST['Email'];
        $RoleTypeID             = $_REQUEST['RoleTypeID'];
        $GenderID               = $_REQUEST['GenderID'];
        $RaceID                 = $_REQUEST['RaceID'];
        
        // DescriptionID='".$Description."',
        $update="UPDATE Investor SET ModifiedDate='".$ModifiedDate."',InvestorName='".$InvestorName."', Website='".$Website."', ImpactTag='".$ImpactTag."', YearFounded='".$YearFounded."', Headquarters='".$Headquarters."', Logo='".$Logo."' where InvestorID='".$InvestorID."'";
        mysqli_query($conn, $update) or die($conn->error);
        $status = "Record Updated Successfully. </br></br>
        <a href='../tabs/contacts.php'>View Updated Record</a>";
        echo '<p style="color:#FF0000;">'.$status.'</p>';
        header( "refresh: 3;url= ../tabs/contacts.php" );
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
                <input type="hidden" name="new" value="1" />
                <input name="UserDetailID" type="hidden" value="<?php echo $row['UserDetailID'];?>" />
                <p><input class="form-control col" type="text" name="UserFullName" placeholder=" Enter UserFullName" required value="<?php echo $row['UserFullName'];?>" /></p>
                <p><input class="form-control col" type="text" name="FirstName" placeholder="Enter FirstName"  value="<?php echo $row['FirstName'];?>" /></p>
                <p><input class="form-control col" type="text" name="LastName" placeholder="Enter LastName"  value="<?php echo $row['LastName'];?>" /></p>
                <p><input class="form-control col" type="text" name="ContactNumber1" placeholder="Enter ContactNumber1"  value="<?php echo $row['ContactNumber1'];?>" /></p>
                <p><input class="form-control col" type="text" name="ContactNumber2" placeholder="Enter ContactNumber2"  value="<?php echo $row['ContactNumber2'];?>" /></p>
                <p><input class="form-control col" type="text" name="Email" placeholder="Enter Email"  value="<?php echo $row['Email'];?>" /></p>
                <p><input class="form-control col" type="text" name="RoleTypeID" placeholder="Enter RoleTypeID"  value="<?php echo $row2['RoleType'];?>" /></p>
                <p><input class="form-control col" type="text" name="GenderID" placeholder="Enter GenderID"  value="<?php echo $row3['Gender'];?>" /></p>
                <p><input class="form-control col" type="text" name="RaceID" placeholder="Enter RaceID"  value="<?php echo $row4['Race'];?>" /></p>

                <p><input name="submit" type="submit" value="Update" /></p>
            </form>
            <?php } ?>
        </main>
    </body>
</html>
