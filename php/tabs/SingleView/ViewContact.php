<?php
    // INCLUDE DATABASE CONNECTION  
    include_once('../../App/connect.php');
    $UserDetailID =$_REQUEST['UserDetailID'];
    // echo 'You have viewed company with ID: '. $PortfolioCompanyID;
    // QUERY DATABASE FROM DATA
    $sql="  SELECT 
                UserDetail.UserDetailID, UserDetail.UserFullName, UserDetail.FirstName, UserDetail.LastName, GROUP_CONCAT(DISTINCT PortfolioCompanyName) AS PortfolioCompanyName, UserDetail.ContactNumber1, UserDetail.ContactNumber2, UserDetail.Email, RoleType.RoleType, Gender.Gender, Race.Race 
            FROM 
                UserDetail 
            LEFT JOIN 
                PortfolioCompanyUserDetail 
            ON 
                PortfolioCompanyUserDetail.UserDetailID = UserDetail.UserDetailID 
            LEFT JOIN 
                PortfolioCompany
            ON 
                PortfolioCompanyUserDetail.PortfolioCompanyID = PortfolioCompany.PortfolioCompanyID 
            LEFT JOIN 
                RoleType 
            ON 
                RoleType.RoleTypeID = UserDetail.RoleTypeID

            LEFT JOIN 
                Gender
            ON
                Gender.GenderID = UserDetail.GenderID

            LEFT JOIN 
                Race 
            ON 
                Race.RaceID = UserDetail.RaceID
            WHERE  
                UserDetail.Deleted = 0 AND UserDetail.UserDetailID='$UserDetailID'
            GROUP BY 
                UserDetailID, UserFullName, FirstName, LastName, ContactNumber1, ContactNumber2, Email, RoleType, Gender, Race
    "; 
    $result = mysqli_query($conn, $sql) or die($conn->error);
    
    if($result){
        // echo 'Query success';
        $row = mysqli_fetch_assoc($result);
    }else{
        echo 'Query Failed: '.mysqli_error($conn);
    };
    
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>VC ReportStream |View Company</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
        <link rel="stylesheet" href="../../../css/admin.css">
    </head>
    <body>
        <!-- NAVBAR -->
        <nav class="container navbar navbar-expand-lg align-middle" style="z-index: 1;">
            <div class="container-fluid">
                <a style="color:#ffffff;" class="navbar-brand" href="../../../Admin.php"><img style=" width: 50px;" class="home-ico" src="../../../resources/DCA_Admin.png" alt="Digital collective africa logo"> VC Reportstream  </a>
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
        <!-- MAIN SECTION -->
        <main class="single_View ">
            <div class="p-5">
                <div class="container card-view-body"  enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                            <h3 for="UserFullName" class="form-label"> Full Name</h3>
                            <p name="UserFullName">
                                <?php echo $row['UserFullName'] ?>
                            </p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                            <h3 for="FirstName" class="form-label">First Name</h3>
                            <p name="FirstName">
                                <?php echo $row['FirstName'] ?>
                            </p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                            <h3 for="LastName" class="form-label">Last Name</h3>
                            <p name="LastName">
                                <?php echo $row['LastName'] ?>
                            </p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                            <h3 for="ContactNumber1" class="form-label"> Contact Number 1</h3>
                            <p name="ContactNumber1">
                                <?php echo $row['ContactNumber1'] ?>
                            </p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                            <h3 for="ContactNumber2" class="form-label"> Contact Number 2 </h3>
                            <p name="ContactNumber2">
                                <?php echo $row['ContactNumber2'] ?>
                            </p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                            <h3 for="Email" class="form-label"> Email</h3>
                            <p name="Email">
                                <?php echo $row['Email'] ?>
                            </p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                            <h3 for="RoleType" class="form-label"> Role Type</h3>
                            <p name="RoleType">
                                <?php echo $row['RoleType'] ?>
                            </p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                            <h3 for="Gender" class="form-label"> Gender</h3>
                            <p name="Gender">
                                <?php echo $row['Gender']?>
                            </p>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                            <h3 for="Race" class="form-label"> Race</h3>
                            <p name="Race">
                                <?php echo $row['Race'] ?>
                            </p>
                        </div>
                    </div>
                    <button><a href="../contacts.php"> Close</a></button>
                </div>
            </div>
        </main>        
    </body>
</html>