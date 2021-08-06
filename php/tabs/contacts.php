<?php 
    include_once('../App/connect.php');
    // QUERY DATABASE FROM DATA
    $sql="      SELECT 
                    userdetail.UserDetailID, userdetail.UserFullName, userdetail.FirstName, userdetail.LastName, GROUP_CONCAT(DISTINCT PortfolioCompanyName) AS PortfolioCompanyName, userdetail.ContactNumber1, userdetail.ContactNumber2, userdetail.Email, RoleType.RoleType, gender.Gender, race.Race 
                FROM 
                    userdetail 
                LEFT JOIN 
                    PortfolioCompanyUserDetail 
                ON 
                    PortfolioCompanyUserDetail.UserDetailID=userdetail.UserDetailID 
                LEFT JOIN 
                    PortfolioCompany
                ON 
                    PortfolioCompanyUserDetail.PortfolioCompanyID=PortfolioCompany.PortfolioCompanyID 
                LEFT JOIN 
                    roletype 
                ON 
                    roletype.RoleTypeID=userdetail.RoleTypeID

                LEFT JOIN 
                    gender
                ON
                    gender.GenderID = userdetail.GenderID

                LEFT JOIN 
                    race 
                ON 
                    race.RaceID =userdetail.RaceID
                WHERE  
                    userdetail.Deleted = 0 
                GROUP BY 
                UserDetailID, UserFullName, FirstName, LastName, ContactNumber1, ContactNumber2, Email, RoleType, Gender, Race
    ";
    // $result = mysqli_query($conn, $sql);
    // $sql=" SELECT * FROM investor where id='".$InvestorID."'"; 
    $result = $conn->query($sql) or die($conn->error);
    $row = mysqli_fetch_assoc($result);


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

    
    // $UserDetailID =$_REQUEST['UserDetailID'];

    // INVESTOR INSERTS
    if ( isset($_POST['submit']))
        {
            $UserFullName           = mysqli_real_escape_string($conn, $_POST['UserFullName']);
            $FirstName              = mysqli_real_escape_string($conn, $_POST['FirstName']);
            $LastName               = mysqli_real_escape_string($conn, $_POST['LastName']);
            $PortfolioCompanyName   = $_POST['PortfolioCompanyName'];
            $ContactNumber1         = $_POST['ContactNumber1'];
            $ContactNumber2         = $_POST['ContactNumber2'];
            $Email                  = mysqli_real_escape_string($conn, $_POST['Email']);
            $RoleType               = $_POST['RoleType'];
            $Gender                 = $_POST['Gender'];
            $Race                   = $_POST['Race'];

            $sqlUser ="INSERT INTO UserDetail(UserDetailID, CreatedDate, ModifiedDate, Deleted, DeletedDate, UserFullName, FirstName, LastName, ContactNumber1, ContactNumber2, Email, RoleTypeID, GenderID, RaceID) 
            VALUES (uuid(), now(), now(),0,NULL, '$UserFullName', '$FirstName','$LastName','$ContactNumber1','$ContactNumber2','$Email', (select RoleType.RoleTypeID FROM RoleType where RoleType.RoleType = '$RoleType'), (select Gender.GenderID FROM Gender where Gender.Gender = '$Gender') , (select Race.RaceID FROM Race where Race.Race = '$Race'))";

            $query3 = mysqli_query($conn, $sqlUser);
            

            if($query3){
                // echo ;
                // header( "refresh: 5;url= contacts.php" );
            }else{
                echo 'Oops! There was an error creating new contact';
            }
                              
            // LINK CONTACT TO PORTFOLIO COMPANY 
            $sql4 = "  INSERT INTO PortfolioCompanyUserDetail(PortfolioCompanyUserDetailID, CreatedDate, ModifiedDate, Deleted, DeletedDate, PortfolioCompanyID, UserDetailID)
            VALUES (uuid(), now(), now(), 0, NULL, (select PortfolioCompany.PortfolioCompanyID FROM PortfolioCompany where PortfolioCompany.PortfolioCompanyName = '$PortfolioCompanyName'), (select UserDetail.UserDetailID FROM UserDetail where UserDetail.UserFullName = '$UserFullName'))";
            $query4 = mysqli_query($conn, $sql4);
            if($query4){
                // echo '<script> Alert("Fund created successfully!")</script>';
                // header( "refresh: 3; url= fund.php" );
            } else {
                echo 'Oops! There was an error linking Investor to Company  . Please report bug to support.'.'<br/>'.mysqli_error($conn);
            }
            // echo '<p> Thanks for your contribution! You will be redirected in 5 sec...</p>';
            header( "refresh: 5;url= ../tabs/contacts.php" );
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="../../resources/DCA_Icon.png" type="image/x-icon">
        <title>VC Reportstream | Investor</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
        <link rel="stylesheet" href="../../css/select2.min.css">
        <link rel="stylesheet" href="../../css/bootstrap.min.css">
        <link rel="stylesheet" href="../../css/bootstrap.css">
        <link rel="stylesheet" href="../../css/main.css">
        <link rel="stylesheet" href="../../DataTables/datatables.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
        <!-- OVERWRITING THE STYLING OF THE PLUGIN -->
        <style>
            .dataTables_wrapper,
            .dataTables_length,
            .dataTables_wrapper,
            .dataTables_filter,
            .dataTables_wrapper,
            .dataTables_info,
            .dataTables_wrapper,
            .dataTables_processing,
            .dataTables_wrapper,
            .dataTables_paginate,
            .dataTables_paginate #table_investmentManager_previous,
            .dataTables_paginate #table_investmentManager_next {
                color: #ffffff !important;
            }
        </style>
    </head>
    <body class="pb-5">
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
                <div class="my-2">
                    <div class="row">
                        <!-- CREATE NEW INVESTOR MODAL -->
                        <span class="col-6 col-md-4 col-lg-2">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn new-button " data-bs-toggle="modal" data-bs-target="#exampleModal">
                                New Contact <img src="../../resources/icons/New.svg" alt="">
                            </button>
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Create New Contact</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
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
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
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
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="Email" class="form-label">Email</label>
                                                        <input type="text" class="form-control" id="Email" name="Email" required>
                                                    </div> 
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="ContactNumber1" class="form-label">ContactNumber1</label>
                                                        <input type="tel" class="form-control" id="ContactNumber1" name="ContactNumber1" required>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="ContactNumber2" class="form-label">ContactNumber2</label>
                                                        <input type="tel" class="form-control" id="ContactNumber2" name="ContactNumber2">
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
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </span>
                        <!-- EXPORT CSV FILE -->
                        <span class="col-6 col-md-4 col-lg-2"> 
                            <form action="../ContactExport.php" method="POST">
                                <button class="btn new-button" type="submit" name="export" formmethod="POST"> Export CSV</button>
                            </form>
                        </span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body"style="background-color:#5d8f18;">
                        <div class="table-responsive" style="overflow-x:auto;">
                            <table class=" table table-hover table-striped table-success table-bordered table-responsive" style="Width: 2400px;line-height: 18px;"id="table_Contacts">
                                <thead>
                                    <th scope="col">User Full Name</th>
                                    <th scope="col">First Name</th>
                                    <th scope="col">Last Name</th>
                                    <th scope="col">Company</th>
                                    <th scope="col">Contact Number 1</th>
                                    <th scope="col">Contact Number 2 </th>
                                    <th scope="col">Email</th>
                                    <th scope="col">RoleType</th>
                                    <th scope="col">Gender</th>
                                    <th scope="col">Race  </th>
                                    <th scope="col">Edit  </th>
                                    <th scope="col">Delete </th>
                                </thead>
                                <tbody>
                                    <?php
                                        while($rows = mysqli_fetch_assoc($result))
                                        {
                                    ?>
                                        <tr data-href="../crud/edit_Contact.php?UserDetailID=<?php echo $rows['UserDetailID']; ?>">
                                            <td class="text-truncate"> <small><?php echo $rows['UserFullName'] ?></small></td>
                                            <td class="text-truncate"> <small><?php echo $rows['FirstName'] ?></small></td>
                                            <td class="text-truncate"> <small><?php echo $rows['LastName'] ?></small></td>
                                            <td class="text-truncate"> <small><?php echo $rows['PortfolioCompanyName'] ?></small></td>
                                            <td class="text-truncate"> <small><?php echo $rows['ContactNumber1'] ?></small></td>
                                            <td class="text-truncate"> <small><?php echo $rows['ContactNumber2'] ?></small></td>
                                            <td class="text-truncate"> <small><?php echo $rows['Email'] ?></small></td>
                                            <td class="text-truncate"> <small><?php echo $rows['RoleType'] ?></small></td>
                                            <td class="text-truncate"> <small><?php echo $rows['Gender'] ?></small></td>
                                            <td class="text-truncate"> <small><?php echo $rows['Race'] ?></small></td>
                                            <td class="text-truncate"> <a href="../crud/edit_contact.php?UserDetailID=<?php echo $rows['UserDetailID']; ?>">Edit</a></td>
                                            <td class="text-truncate"> <a href="../crud/delete_contact.php?UserDetailID=<?php echo $rows['UserDetailID']; ?>">Delete</a></td>
                                        </tr>
                                    <?php 
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.6.0.slim.js" integrity="sha256-HwWONEZrpuoh951cQD1ov2HUK5zA5DwJ1DNUXaM6FsY=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
        <script src="../../js/scripts.js"></script>
        <script src="../../js/select2.min.js"></script>
        <script src="../../DataTables/datatables.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
        <script>
            // Initializing the datatable plugin
            $(document).ready( function () {
                $('#table_Contacts').DataTable();

                // Trigger the double tap to edit function
                $(document.body).on("dblclick", "tr[data-href]", function (){
                    window.location.href = this.dataset.href;
                })
            });
        </script>
    </body>
</html>
