<?php 
    include_once('../connect.php');
    // QUERY DATABASE FROM DATA
    $sql=" SELECT userdetail.UserDetailID, userdetail.UserFullName, userdetail.FirstName, userdetail.LastName, userdetail.ContactNumber1, userdetail.ContactNumber2, userdetail.Email, RoleType.RoleType, gender.Gender, race.Race FROM userdetail 

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
    race.RaceID =userdetail.RaceID; ";

    // $result = mysqli_query($conn, $sql);
    // $sql=" SELECT * FROM investor where id='".$InvestorID."'"; 
    $result = $conn->query($sql) or die($conn->error);
    $row = mysqli_fetch_assoc($result);

    // INVESTOR INSERTS
    if ( isset($_POST['submit']))
        {
            $UserFullName           = $_POST['UserFullName'];
            $FirstName              = $_POST['FirstName'];
            $LastName               = $_POST['LastName'];
            $ContactNumber1         = $_POST['ContactNumber1'];
            $ContactNumber2         = $_POST['ContactNumber2'];
            $Email                  = $_POST['Email'];
            $RoleType               = $_POST['RoleType'];
            $Gender                 = $_POST['Gender'];
            $Race                   = $_POST['Race'];

            $sqlUser ="INSERT INTO UserDetail(UserDetailID, CreatedDate, ModifiedDate, UserFullName, FirstName, LastName, ContactNumber1, ContactNumber2, Email, RoleTypeID, GenderID, RaceID) 
            VALUES (uuid(), now(), now(), (select CONCAT(FirstName, Coalesce(LastName, '')) from UserDetail ), '$FirstName','$LastName','$ContactNumber1','$ContactNumber2','$Email', (select RoleType.RoleTypeID FROM RoleType where RoleType.RoleType = '$RoleType'), (select Gender.GenderID FROM Gender where Gender.Gender = '$Gender') , (select Race.RaceID FROM Race where Race.Race = '$Race'))";

            $query3 = mysqli_query($conn, $sqlUser);
            if($query3){
                // echo 'Thanks for your contribution! You will be redirected in 3 sec...';
                header( "refresh: 3;url= contacts.php" );
            }else{
                echo 'Oops! There was an error creating new contact';
            }

            $tempUserDetailID = $row['UserDetailID'];

            $sqlUserFullName =" Update UserDetail( UserFullName) 
            VALUES ( (select CONCAT(FirstName, Coalesce(LastName, '')) from UserDetail )) WHERE UserDetailID ='$tempUserDetailID'";

            $update="UPDATE UserDetail SET UserFullName='SELECT CONCAT(FirstName, Coalesce(LastName, '') FROM UserDetail' where UserDetailID='".$tempUserDetailID."'";
            mysqli_query($conn, $update) or die($conn->error);
            $status = "Record Updated Successfully. </br></br>
            <a href='../tabs/contacts.php'>View Updated Record</a>";
            echo '<p style="color:#FF0000;">'.$status.'</p>';
            header( "refresh: 3;url= ../tabs/contacts.php" );
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
        <link rel="stylesheet" href="../../css/bootstrap.min.css">
        <link rel="stylesheet" href="../../css/bootstrap.css">
        <link rel="stylesheet" href="../../css/main.css">
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
                            <a class="nav-link" href="../../WebInterface.php">New Deal</a>
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
                                                        <label for="FirstName" class="form-label">First Name</label>
                                                        <input type="text" class="form-control" id="FirstName" name="FirstName" required>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="LastName" class="form-label">Last Name</label>
                                                        <input type="text" class="form-control" id="LastName" name="LastName" required>
                                                    </div>
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
                                                        <select name="RoleType" class="form-select" id="RoleType" required>
                                                            <option value="" selected >Choose...</option>
                                                            <option value="President">President</option>
                                                            <option value="CEO">CEO</option>
                                                            <option value="CFO">CFO</option>
                                                            <option value="COO">COO</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="Gender" class="form-label">Gender</label>
                                                        <select name="Gender" class="form-select" id="Gender" required>
                                                            <option value="" selected >Choose...</option>
                                                            <option value="Male">Male</option>
                                                            <option value="Female">Female</option>
                                                            <option value="Unknown">Unknown</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="Race" class="form-label">Race</label>
                                                        <select name="Race" class="form-select" id="Race" required>
                                                            <option value="" selected >Choose...</option>
                                                            <option value="Black">Black</option>
                                                            <option value="White">White</option>
                                                            <option value="Asian">Asian</option>
                                                            <option value="Indian">Indian</option>
                                                            <option value="Unknown">Unknown</option>
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
                            <form action="../InvestorExport.php" method="POST">
                                <button class="btn new-button" type="submit" name="export" formmethod="POST"> Export CSV</button>
                            </form>
                        </span>
                    </div>
                </div>
                <div class="table-responsive" style="overflow-x:auto;">
                    <table class=" table table-hover table-striped table-success table-bordered table-responsive" style="Width: 2400px;line-height: 30px;">
                        <t>
                            <th>User Full Name</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Contact Number 1</th>
                            <th>Contact Number 2 </th>
                            <th>Email</th>
                            <th>RoleType</th>
                            <th>Gender</th>
                            <th>Race  </th>
                            <th>Edit  </th>
                            <th>Delete </th>
                        </t>
                        <?php
                            while($rows = mysqli_fetch_assoc($result))
                            {
                        ?>
                            <tr>
                                <td> <?php echo $rows['UserFullName'] ?></td>
                                <td> <?php echo $rows['FirstName'] ?></td>
                                <td> <?php echo $rows['LastName'] ?></td>
                                <td> <?php echo $rows['ContactNumber1'] ?></td>
                                <td> <?php echo $rows['ContactNumber2'] ?></td>
                                <td> <?php echo $rows['Email'] ?></td>
                                <td> <?php echo $rows['RoleType'] ?></td>
                                <td> <?php echo $rows['Gender'] ?></td>
                                <td> <?php echo $rows['Race'] ?></td>
                                <td> <a href="../crud/edit_contact.php?UserDetailID=<?php echo $rows['UserDetailID']; ?>">Edit</a></td>
                                <td> <a href="../crud/delete_contact.php?UserDetailID=<?php echo $rows['UserDetailID']; ?>">Delete</a></td>
                            </tr>
                        <?php 
                            }
                        ?>
                    </table>
                </div>
            </div>
        </main>
        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.6.0.slim.js" integrity="sha256-HwWONEZrpuoh951cQD1ov2HUK5zA5DwJ1DNUXaM6FsY=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
        <script src="../../js/scripts.js"></script>
    </body>
</html>
