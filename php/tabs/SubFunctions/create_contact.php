<?php 
    include_once('../../App/connect.php');

    // INVESTOR INSERTS
    if ( isset($_POST['submit']))
        {
            $UserFullName           = $_POST['UserFullName'];
            $FirstName              = $_POST['FirstName'];
            $LastName               = $_POST['LastName'];
            // $PortfolioCompanyName   = $_POST['PortfolioCompanyName'];
            $ContactNumber1         = $_POST['ContactNumber1'];
            $ContactNumber2         = $_POST['ContactNumber2'];
            $Email                  = $_POST['Email'];
            $RoleType               = $_POST['RoleType'];
            $Gender                 = $_POST['Gender'];
            $Race                   = $_POST['Race'];

            $sqlUser ="INSERT INTO UserDetail(UserDetailID, CreatedDate, ModifiedDate, Deleted, DeletedDate, UserFullName, FirstName, LastName, ContactNumber1, ContactNumber2, Email, RoleTypeID, GenderID, RaceID) 
            VALUES (uuid(), now(), now(),0,NULL, '$UserFullName', '$FirstName','$LastName','$ContactNumber1','$ContactNumber2','$Email', (select RoleType.RoleTypeID FROM RoleType where RoleType.RoleType = '$RoleType'), (select Gender.GenderID FROM Gender where Gender.Gender = '$Gender') , (select Race.RaceID FROM Race where Race.Race = '$Race'))";

            $query3 = mysqli_query($conn, $sqlUser);    

            if($query3){
                // header( "refresh: 5;url= contacts.php" );
            }else{
                echo 'Oops! There was an error creating new contact';
            }

            echo '<p> New contact created successfully! </p>'
                 .'<br/>'
                 .'<a class="btn btn-danger" href="javascript:window.open(\'\',\'_self\').close();">Close</a>'
                 .'<br/>';
            // header( "refresh: 5;url= ../../../index.php" );
            exit();
    }
    
    // POPULATING PORTFOLIO COMPANIES DROPDOWN
    $sql101 = " SELECT DISTINCT 
                    PortfolioCompanyName
                FROM 
                    PortfolioCompany 
                WHERE 
                    PortfolioCompanyName IS NOT NULL ORDER BY PortfolioCompanyName ASC
    ";
    $result101 = mysqli_query($conn, $sql101);

    // POPULATING ROLETYPE DROPDOWN
    $sqlRoleType = " SELECT DISTINCT 
                    RoleType
                FROM 
                    RoleType 
                WHERE 
                    RoleType IS NOT NULL ORDER BY RoleType ASC
    ";
    $resultRoleType = mysqli_query($conn, $sqlRoleType);

    // POPULATING GENDER DROPDOWN
    $sqlGender = " SELECT DISTINCT 
                    Gender
                FROM 
                    Gender 
                WHERE 
                    Gender IS NOT NULL ORDER BY Gender ASC
    ";
    $resultGender = mysqli_query($conn, $sqlGender);

    // POPULATING RACE DROPDOWN
    $sqlRace = " SELECT DISTINCT 
                    Race
                FROM 
                    Race 
                WHERE 
                    Race IS NOT NULL ORDER BY Race ASC
    ";
    $resultRace = mysqli_query($conn, $sqlRace);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="../../../resources/DCA_Icon.png" type="image/x-icon">
        <title>VC Reportstream | Investor</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
        <link rel="stylesheet" href="../../../css/select2.min.css">
        <link rel="stylesheet" href="../../../css/bootstrap.min.css">
        <link rel="stylesheet" href="../../../css/bootstrap.css">
        <link rel="stylesheet" href="../../../css/main.css">
    </head>
    <body class="pb-5">
        <!-- HEADER CONTENT -->
        <nav class="container navbar navbar-expand-lg align-middle" style="z-index: 1;">
            <div class="container-fluid">
                <a style="color:#ffffff;" class="navbar-brand" href="../../../index.php"><img style=" width: 80px;" class="home-ico" src="../../../resources/DCA_Icon.png" alt="Digital collective africa logo"> VC Reportstream  </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="https://www.digitalcollective.africa/ " target="_blank" >Digital Collective Africa</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Contact</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- BODY CONTENT -->
        <main class="container ">
            <!-- ==== LIST OF INVESTORS ==== -->
            <div class=" my-5"> 
                <form class="container" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                            <label for="UserFullName" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="UserFullName" name="UserFullName" required>
                        </div>
                        <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                            <label for="FirstName" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="FirstName" name="FirstName" required>
                        </div>
                        <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                            <label for="LastName" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="LastName" name="LastName" required>
                        </div>
                        <!-- <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                            <label for="PortfolioCompanyName" class="form-label">Portfolio Company </label>
                            <select class="form-select" id="PortfolioCompanyName" name="PortfolioCompanyName" required>
                                <option> Select Company</option>
                                <?php
                                    while ($row101 = mysqli_fetch_assoc($result101)) {
                                        # code...
                                        echo "<option>".$row101['PortfolioCompanyName']."</option>";
                                    }
                                ?>
                            </select>
                        </div> -->
                        <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                            <label for="Email" class="form-label">Email</label>
                            <input type="text" class="form-control" id="Email" name="Email" required>
                        </div> 
                        <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                            <label for="ContactNumber1" class="form-label">ContactNumber1</label>
                            <input type="text" class="form-control" id="ContactNumber1" name="ContactNumber1" required>
                        </div>
                        <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                            <label for="ContactNumber2" class="form-label">ContactNumber2</label>
                            <input type="text" class="form-control" id="ContactNumber2" name="ContactNumber2">
                        </div>
                        <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                            <label for="RoleType" class="form-label">RoleType</label>
                            <select class="form-select" id="RoleType" name="RoleType" required>
                                <option> Select RoleType</option>
                                <?php
                                    while ($rowRoleType = mysqli_fetch_assoc($resultRoleType)) {
                                        # code...
                                        echo "<option>".$rowRoleType['RoleType']."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                            <label for="Gender" class="form-label">Gender</label>
                            <select class="form-select" id="Gender" name="Gender" required>
                                <option> Select Gender</option>
                                <?php
                                    while ($rowGender = mysqli_fetch_assoc($resultGender)) {
                                        # code...
                                        echo "<option>".$rowGender['Gender']."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                            <label for="Race" class="form-label">Race</label>
                            <select class="form-select" id="Race" name="Race" required>
                                <option> Select Race</option>
                                <?php
                                    while ($rowRace = mysqli_fetch_assoc($resultRace)) {
                                        # code...
                                        echo "<option>".$rowRace['Race']."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" name="submit" value="submit">Submit</button>
                    <a class="btn btn-danger" href="javascript:window.open('','_self').close();">Close</a>
                </form>
            </div>
        </main>
        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.6.0.slim.js" integrity="sha256-HwWONEZrpuoh951cQD1ov2HUK5zA5DwJ1DNUXaM6FsY=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
        <script src="../../js/scripts.js"></script>
        <script src="../../js/select2.min.js"></script>
    </body>
</html>
