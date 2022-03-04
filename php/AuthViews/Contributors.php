<?php 
    // ======================================
    // STARTING A NEW SESSION FOR EACH USER
    // ======================================
    session_start();
    // NOW WE SET A CONDITION TO PREVENT UNAUTHORISED USERS TO ACCESS THIS PAGE.
    if( $_SESSION == []){
        header('refresh:5; url = ../../index.php');
        echo'
            <p> 
                Access denied. Only Admins can access this page. <br/>
                <small>You are being redirected back to the home page.</small>
            </p>
        ';
        exit;
    }

    // CONNECT TO DATABASE
    include_once('../App/connect.php');

    // QUERY DATABASE FROM DATA
    $sql="  SELECT 
                *
            FROM
                PlatformContributors
    ";
    // $result = mysqli_query($conn, $sql);
    // $sql=" SELECT * FROM investor where id='".$InvestorID."'"; 
    $result = $conn->query($sql) or die($conn->error);
    $row = mysqli_fetch_assoc($result);

    
    if(isset($_POST['submit'])){
        $Status = $_POST['Status'];
        // echo 'form submitted <br/>'.$Status;
        $sql = "UPDATE PlatformContributors SET ModifiedDate= NOW(), Verified='".$Status."'  WHERE PlatformContributorsID='".$PlatformContributorsID."'";
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
        <link rel="stylesheet" href="../../css/admin.css">
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
        <header class="mb-5">
            <nav class=" navbar navbar-expand-lg align-middle navbar-dark fixed-top" style="z-index: 1;">
                <div class="container px-0">
                    <a style="color:#ffffff;" class="navbar-brand" href="../../Admin.php"><img style=" width: 48px;" class="home-ico" src="../../resources/DCA_Admin.png" alt="Digital collective africa logo"> <small>VC ReportStream</small> </a>
                    <button class="navbar-toggler text-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="https://www.digitalcollective.africa/ " target="_blank" ><small>Digital Collective Africa</small> </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#"><small>Contact</small> </a>
                            </li>
                            <li class="nav-item">
                                <form action="../Auth/logout.php" method="POST"  class="profile">
                                    <input class="logout_btn" type="submit" name="logout"  value="logout" formmethod="POST">
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        <div style="height: 20px;"></div>
        <!-- BODY CONTENT -->
        <main class="container ">
            <!-- ==== LIST OF INVESTORS ==== -->
            <div class=" my-5">
                <div class="card" style="max-width:1020px; margin:0 auto;">
                    <div class="card-body" >
                        <div class="table-responsive" style="overflow-x:auto;">
                            <table class=" table table-hover table-striped table-success table-bordered table-responsive" style="line-height: 18px;"id="table_Contacts">
                                <thead>
                                    <th scope="col">First Name</th>
                                    <th scope="col">Last Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Verification Status</th>
                                    <th scope="col">Edit  </th>
                                </thead>
                                <tbody>
                                    <?php
                                        while($row = mysqli_fetch_assoc($result))
                                        {
                                    ?>
                                        <tr>
                                            <td class="text-truncate"> <small><?php echo $row['FirstName'] ?></small></td>
                                            <td class="text-truncate"> <small><?php echo $row['LastName'] ?></small></td>
                                            <td class="text-truncate"> <small><?php echo $row['Email'] ?></small></td>
                                            <td class="text-truncate"> 
                                                <?php echo $row['Verified'] ?>
                                            </td>
                                            <td class="text-truncate"> <a href="../crud/edit_Admin.php?PlatformContributorsID=<?php echo $row['PlatformContributorsID']; ?>">Edit</a></td>
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
