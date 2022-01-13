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
    $PlatformContributorsID = $_REQUEST['PlatformContributorsID'];
    // echo $PlatformContributorsID;

    $sql=" SELECT PlatformContributorsID, CreatedDate, Verified, FirstName, LastName, Email FROM PlatformContributors where PlatformContributorsID = '$PlatformContributorsID'
    "; 
    $result = mysqli_query($conn, $sql);
   
    // print_r($row);
    if(isset($_POST['new']) && $_POST['new']==1)
    {
        $status = "Admin Updated Successfully. </br></br>
        <a href='../AuthViews/contacts.php'>View Updated Record</a>";

        echo '<p style="color:#FF0000;">'.$status.'</p>';

        header( "refresh: 5;url= ../AuthViews/contacts.php" );
        
        // CHECK IF VARIABLES ARE SET OR NOT BEFORE WORKING WITH THEM IN THE UPDATE QUERY
        // ALSO USE STRING ESCAPE TO ESCAPE OUT ALL SPECIAL CHARACTERS AND AVOID SQL INJECTION
 
        if(isset($_POST['Verified'])){ 
            $VerifiedStatus  = $_POST['Verified'];
            // echo   $VerifiedStatus;
        }

        // BUILD A DYNAMIC QUERY TO UPDATE THE RECORD WITH ONLY VARIABLES THAT WERE SET OR FILLED IN ON THE UPDATE FORM
        $updateAdmin = array();

        if($VerifiedStatus !== null){
            $updateAdmin[] ="Verified='".$VerifiedStatus."'";
            // print_r( $VerifiedStatus);
        }
       
        $updateAdminString = implode(', ', $updateAdmin);
        // echo $updateAdminString;

        $updateAdmin = "UPDATE PlatformContributors SET ModifiedDate= NOW(), $updateAdminString WHERE PlatformContributorsID='".$PlatformContributorsID."'";

        $resultUpdate = mysqli_query($conn, $updateAdmin);

        if($resultUpdate){
            // do nothing
        }else{
            echo 'error: '.mysqli_error($conn); 
            echo "'".$PlatformContributorsID."'";
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
        <link rel="stylesheet" href="../../css/admin.css">
    </head>
    <body>
    <!-- HEADER CONTENT -->
        <nav class="container navbar navbar-expand-lg align-middle" style="z-index: 1;">
            <div class="container-fluid">
                <a style="color:#ffffff;" class="navbar-brand" href="../../Admin.php"><img style=" width: 48px;" class="home-ico" src="../../resources/DCA_Admin.png" alt="Digital collective africa logo"> VC Reportstream  </a>
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
        <main class="container "style="max-width:400px">
            <!-- ==== LIST OF INVESTORS ==== -->
            <div class=" my-5" >
                <div class="card">
                    <div class="card-body">
                        <form >
                            <?php
                                while( $row = mysqli_fetch_assoc($result))
                                {
                            ?>
                            <!-- WE'LL USE THIS TO VALIDATE THE UNIQUE FORM AND TO GET THE ID WHEN WE SUBMIT SO WE CAN UPDATE RECORD -->
                            <input type="hidden" name="new" value="1" />
                            <input name="PlatformContributorsID" type="hidden" value="<?php echo $row['PlatformContributorsID'];?>"/>
                            
                            <div class="mb-3">
                                <label for="FirstName" class="form-label">FirstName </label>
                                <input type="FirstName" class="form-control" id="FirstName" value="<?php echo $row['FirstName'] ?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="LastName" class="form-label">LastName </label>
                                <input type="LastName" class="form-control" id="LastName" value="<?php echo $row['LastName'] ?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="Email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="Email" value="<?php echo $row['Email'] ?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="VerifiedStatus" class="form-label">Admin Access </label>
                                <select class="form-select" id="VerifiedStatus" name="Verified" required>
                                    <option selected disabled>Current Status: <?php echo $row['Verified'] ?></option>
                                    <option value=0>0</option>
                                    <option value=1>1</option>
                                </select>
                                <small>set to 1 to grant admin access</small>
                            </div>
                            <Button name="Update" type="submit" value="Update" class="btn btn-primary" formmethod="POST">Update</Button>
                        </form>
                    </div>
                </div>
            </div>
            <?php } ?>
            <?php } ?>
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
