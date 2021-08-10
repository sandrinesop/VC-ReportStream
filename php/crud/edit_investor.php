<?php 
    include_once('../App/connect.php');
    // QUERY DATABASE FROM DATA
    $InvestorID =$_REQUEST['InvestorID'];
    $sql=" SELECT  
                Investor.InvestorID, Investor.Deleted, Investor.DeletedDate, Investor.InvestorName, GROUP_CONCAT(DISTINCT Investor.Website) AS Website, GROUP_CONCAT(DISTINCT FundName) AS FundName, GROUP_CONCAT(DISTINCT PortfolioCompanyName) AS PortfolioCompanyName, Note.Note, Description.Description, Currency.Currency, Investor.ImpactTag, Investor.YearFounded, GROUP_CONCAT(DISTINCT Country) AS Country, Investor.Logo 
            FROM 
                Investor
                -- Joining linking table so that we can access funds linked to investor
            LEFT JOIN 
                FundInvestor 
            ON 
                FundInvestor.InvestorID = Investor.InvestorID
            LEFT JOIN 
                Fund 
            ON 
                Fund.FundID = FundInvestor.FundID
                -- Joining linking table so that we can access Portfolio Companies linked to investor
            LEFT JOIN 
                InvestorPortfolioCompany 
            ON 
                InvestorPortfolioCompany.InvestorID = Investor.InvestorID
            LEFT JOIN 
                PortfolioCompany 
            ON 
                PortfolioCompany.PortfolioCompanyID = InvestorPortfolioCompany.PortfolioCompanyID
                -- Joining linking table so that we can access Notes linked to investor
            LEFT JOIN 
                InvestorNote 
            ON 
                InvestorNote.InvestorID = Investor.InvestorID
            LEFT JOIN 
                Note 
            ON 
                Note.NoteID = InvestorNote.NoteID
            LEFT JOIN 
                Currency 
            ON 
                Currency.CurrencyID=Investor.CurrencyID
                
            LEFT JOIN 
                Description 
            ON 
                Description.DescriptionID=Investor.DescriptionID 
            LEFT JOIN 
                Country 
            ON 
                Country.CountryID = Investor.Headquarters 
            WHERE 
                Investor.Deleted= 0 AND Investor.InvestorID = '$InvestorID'

            GROUP BY InvestorID, Deleted, DeletedDate, InvestorName, Description, Currency, ImpactTag, YearFounded, Logo
            ORDER BY InvestorName;
    "; 
    $result = mysqli_query($conn, $sql) or die($conn->error);
    $row = mysqli_fetch_assoc($result);


    // POPULATING FUND DROPDOWN 
    $sql100 = "  SELECT DISTINCT 
                    FundName
                FROM 
                    Fund 
                WHERE 
                    FundName IS NOT NULL ORDER BY FundName ASC
    ";
    $result100 = mysqli_query($conn, $sql100);
    // POPULATING PORTFOLIO COMPANIES DROPDOWN
    $sql101 = " SELECT DISTINCT 
                    PortfolioCompanyName
                FROM 
                    PortfolioCompany 
                WHERE 
                    PortfolioCompanyName IS NOT NULL ORDER BY PortfolioCompanyName ASC
    ";
    $result101 = mysqli_query($conn, $sql101);
    // POPULATING DESCRIPTION DROPDOWN
    $sql102 = " SELECT DISTINCT 
                    Description
                FROM 
                    Description 
                WHERE 
                    Description IS NOT NULL ORDER BY Description ASC
    ";
    $result102 = mysqli_query($conn, $sql102);
    // POPULATING IMPACT-TAG DROPDOWN
    $sql103 = " SELECT DISTINCT 
                    ImpactTag
                FROM 
                    Investor 
                WHERE 
                    ImpactTag IS NOT NULL ORDER BY ImpactTag ASC
    ";
    $result103 = mysqli_query($conn, $sql103);
    // POPULATING COUNTRIES DROPDOWN
    $sql104 = " SELECT DISTINCT 
                    Country
                FROM 
                    Country 
                WHERE 
                    Country IS NOT NULL ORDER BY Country ASC
    ";
    $result104 = mysqli_query($conn, $sql104);
    // POPULATING CURRENCIES DROPDOWN
    $sql105 = " SELECT DISTINCT 
                    Currency
                FROM 
                    Currency 
                WHERE 
                    Currency IS NOT NULL ORDER BY Currency ASC
    ";
    $result105 = mysqli_query($conn, $sql105);
    /* 
        --------------------------------------------------------------------
        CODE BLOCKS TO UPDATE/EDIT THE INVESTMENT MANAGER RECORDS
        --------------------------------------------------------------------
    */
    $status = "";
    if(isset($_POST['new']) && $_POST['new']==1)
    {
        // HEADERS ARE SENT BEFORE ANYTHING ELSE OTHERWISE THEY WON'T WORK
        $status = "Record Updated Successfully. </br></br>
        <a href='../tabs/investor.php'>View Updated Record</a>";
        echo '<p style="color:#FF0000;">'.$status.'</p>';
        header( "refresh: 5;url= ../tabs/investor.php" );

        // SET THE VARIABLES AFTER CHECKING IF THE CORRESPONDING FORM FIELDS ARE SET OR NOT.
        if(isset($_REQUEST['InvestorName'])){ 
            $InvestorName           =   mysqli_real_escape_string($conn,$_REQUEST['InvestorName']);
        }else {
            // error_reporting(0);
        }

        if(isset($_REQUEST['Currency'])){ 
            $Currency               =   $_REQUEST['Currency'];
        }else {
            // error_reporting(0);
        }

        if(isset($_REQUEST['FundName'])){ 
            $Funds               =   $_REQUEST['FundName'];
        }else {
            // error_reporting(0);
        }

        if(isset($_REQUEST['PortfolioCompanyName'])){ 
            $Companies   =   $_REQUEST['PortfolioCompanyName'];
        }else {
            // error_reporting(0);
        }

        if(isset($_REQUEST['Website'])){ 
            $Website                =   mysqli_real_escape_string($conn,$_REQUEST['Website']);
        }else {
            // error_reporting(0);
        }

        if(isset($_REQUEST['InvestorNote'])){ 
            $Note           =   mysqli_real_escape_string($conn, $_POST['InvestorNote']);
        }else {
            // error_reporting(0);
        }

        if(isset($_REQUEST['Description'])){ 
            $Description            =   $_REQUEST['Description'];
        }else {
            // error_reporting(0);
        }

        if(isset($_REQUEST['YearFounded'])){ 
            $YearFounded            =   $_REQUEST['YearFounded'];
        }else {
            // error_reporting(0);
        }

        if(isset($_REQUEST['Headquarters'])){ 
            $Headquarters           =   $_REQUEST['Headquarters'];
        }else {
            // error_reporting(0);
        }
 
        if(isset($_FILES['img']['name'])){ 
            $logoName = $_FILES['img']['name'];
            $logoSize = $_FILES['img']['size'];

            if($logoSize>0):
                // echo'file uploaded' .$logo;
                $logo =mysqli_real_escape_string($conn, (file_get_contents($_FILES['img']['tmp_name'])));
            else:
                // echo 'Image not set';
            endif;
        }else {
            // error_reporting(0);
        }

        //  BUILDING A DYNAMIC MYSQL UPDATE QUERY BY CREATING AN EMPTY ARRAY AND THEN SETTING CONDITIONAL STATEMENTS TO CHECK IF A VARIABLE IS NOT EMPTY FIRST, IF EMPTY DO NOTHING AND IF SET, THE APPEND IT TO THE ARRAY. THERE ON EXPLODE THE ARRAY TO CONVERT IT INOT A STRING THEN APPEND STRING TO THE UPDATE STATEMENT.
        $updates = array();
        
        if(!empty($InvestorName)){
            $updates[] ='InvestorName="'.$InvestorName.'"';
        } 

        if(!empty($Currency)){
            $updates[] ="CurrencyID = (select Currency.CurrencyID FROM Currency where Currency.Currency = '$Currency')";
        }

        if(!empty($Website)){
            $updates[] ="Website='".$Website."'";
        }

        if(!empty($YearFounded)){
            $updates[] ="YearFounded='".$YearFounded."'";
        }

        if(!empty($logo)){
            $updates[] ='Logo="'.$logo.'"';
        };
        // print_r($updates);
        $updateString = implode(', ', $updates);
        // echo $updateString;
        $updateInvestor = "UPDATE Investor SET ModifiedDate= NOW(), $updateString WHERE InvestorID='".$InvestorID."'";
        $resultUpdate = mysqli_query($conn, $updateInvestor) or die($conn->error);

        // ===================================================================================
        // A CONDITIONAL STATEMENT TO CHECK IF LINKS BETWEEN INVESTOR AND FUND ALREADY EXISTS
        // ===================================================================================
        // $msg = array();
        if(!empty($Funds)){
            foreach($Funds AS $Fund){
                $prevQuery1 = "  SELECT 
                                    FundID 
                                FROM 
                                    FundInvestor
                                WHERE 
                                    FundID = (select Fund.FundID FROM Fund where Fund.FundName = '$Fund') AND InvestorID='".$InvestorID."'
                ";
                $prevResult1 = mysqli_query($conn,$prevQuery1);
                if($prevResult1 !== false && $prevResult1-> num_rows>0){
                    // $msg[] =$Fund;
                    // IF THIS CONDITION RETURNS TRUE, THAT MEANS A LINK BETWEEN THE FUND AND THE INVESTOR ALREADY EXISTS IN THE DATABASE. IN THAT CASE, WE WILL DELETE THE RECORD AND THEN CREATE UPDATED LINKS IN THE NEXT QUERY.
                    $deleteQuery1 = "DELETE FROM FundInvestor WHERE FundID = (select Fund.FundID FROM Fund where Fund.FundName = '$Fund') AND InvestorID='".$InvestorID."'";
                    mysqli_query($conn, $deleteQuery1);
                }else{
                    // IF NO LINKS ARE FOUND BETWEEN A COMPANY AND THE FUND, WE WILL THEN CREATE A NEW LINK BETWEEN THAT FUND AND THE COMPANY.
                    $sql105 = "     INSERT INTO 
                                        FundInvestor(FundInvestorID, CreatedDate, ModifiedDate, Deleted, DeletedDate, FundID, InvestorID)
                                    VALUES 
                                        (uuid(), now(), now(), 0, NULL, (select Fund.FundID FROM Fund where Fund.FundName = '$Fund'),'$InvestorID')";
                    $query105 = mysqli_query($conn, $sql105);
                    if($query105){
                        // do nothing
                    }else{
                        echo 'error: '.mysqli_error($conn);
                    }
                }
            };
        };

        // ===================================================================================
        // A CONDITIONAL STATEMENT TO CHECK IF LINKS BETWEEN INVESTOR AND COMPANIES ALREADY EXISTS
        // ===================================================================================
        // $msg = array();
        if(!empty($Companies)){
            foreach($Companies AS $Company){
                $prevQuery2 = "  SELECT 
                                    PortfolioCompanyID 
                                FROM 
                                    InvestorPortfolioCompany
                                WHERE 
                                    InvestorID='$InvestorID' AND PortfolioCompanyID = (select PortfolioCompany.PortfolioCompanyID FROM PortfolioCompany where PortfolioCompany.PortfolioCompanyName = '$Company')  
                ";
                $prevResult2 = mysqli_query($conn,$prevQuery2);
                if($prevResult2 !== false && $prevResult2-> num_rows>0){
                    // $msg[] =$Fund;
                    // IF THIS CONDITION RETURNS TRUE, THAT MEANS A LINK BETWEEN THE FUND AND THE INVESTOR ALREADY EXISTS IN THE DATABASE. IN THAT CASE, WE WILL DELETE THE RECORD AND THEN CREATE UPDATED LINKS IN THE NEXT QUERY.
                    $deleteQuery2 = "DELETE FROM 
                                        InvestorPortfolioCompany 
                                    WHERE 
                                        InvestorID='$InvestorID' 
                                    AND 
                                        PortfolioCompanyID = (select PortfolioCompany.PortfolioCompanyID FROM PortfolioCompany where PortfolioCompany.PortfolioCompanyName = '$Company')  
                    ";
                    mysqli_query($conn, $deleteQuery2);
                }else{
                    // IF NO LINKS ARE FOUND BETWEEN A COMPANY AND THE FUND, WE WILL THEN CREATE A NEW LINK BETWEEN THAT FUND AND THE COMPANY.
                    $sql2 = "     INSERT INTO 
                                        InvestorPortfolioCompany(InvestorPortfolioCompanyID, CreatedDate, ModifiedDate, Deleted, DeletedDate, InvestorID, PortfolioCompanyID )
                                    VALUES 
                                        (uuid(), now(), now(), 0, NULL,'$InvestorID', (select PortfolioCompany.PortfolioCompanyID FROM PortfolioCompany where PortfolioCompany.PortfolioCompanyName = '$Company'))";
                    $query2 = mysqli_query($conn, $sql2);
                    if($query2){
                        // do nothing
                    }else{
                        echo 'error: '.mysqli_error($conn);
                    }
                }
            };
        };

        // ===================================================================================
        // A CODE BLOCK TO UPDATE THE INVESTOR NOTE
        // ===================================================================================
        // $msg = array();
        $updates2 = array();
        if(!empty($Note)){
            $updates2[] ='Note="'.$Note.'"';
        };
        // print_r($updates2);
        $updateString2 = implode(', ', $updates2);
        // echo $updateString2;
        $updateNote = "UPDATE Note SET ModifiedDate= NOW(), $updateString2 WHERE NoteID= (SELECT InvestorNote.NoteID FROM InvestorNote WHERE InvestorNote.InvestorID='".$InvestorID."')";
        $resultUpdate2 = mysqli_query($conn, $updateNote) or die($conn->error);

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
        <link rel="stylesheet" href="../../css/select2.min.css">
        <link rel="stylesheet" href="../../css/bootstrap.min.css">
        <link rel="stylesheet" href="../../css/bootstrap.css">
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
            <form class="container" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <input type="hidden" name="new" value="1" />
                    <input name="InvestorID" type="hidden" value="<?php echo $row['InvestorID'];?>" />
                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label for="InvestorName" class="form-label"> Name</label>
                        <input type="text" class="form-control" id="InvestorName" name="InvestorName" value="<?php echo $row['InvestorName'];?>">
                    </div>  
                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label for="InvestorWebsite" class="form-label">Website</label>
                        <input type="text" class="form-control" id="InvestorWebsite" name="InvestorWebsite" value="<?php echo $row['Website'];?>">
                    </div>
                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                        <label for="FundName" class="form-label" >Fund </label> <br/>
                        <select class="form-select FundName mb-1" id="FundName" name="FundName[]" multiple="true" >
                            <option value="<?php echo $row['FundName'];?>"> <?php echo $row['FundName'];?> </option>
                            <?php
                                while ($row100 = mysqli_fetch_assoc($result100)) {
                                    # code...
                                    echo "<option>".$row100['FundName']."</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                        <label for="PortfolioCompanyName" class="form-label">Portfolio Company</label><br/>
                        <select class="form-select PortfolioCompanyName mb-1" id="PortfolioCompanyName" name="PortfolioCompanyName[]" multiple="true" >
                            <option value="<?php echo $row['PortfolioCompanyName'];?>"> <?php echo $row['PortfolioCompanyName'];?> </option>
                            <?php
                                while ($row101 = mysqli_fetch_assoc($result101)) {
                                    # code...
                                    echo "<option>".$row101['PortfolioCompanyName']."</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3 ">
                        <label for="InvestorNote" class="form-label"> Note</label>
                        <textarea class="form-control InvestorNote" aria-label="With textarea" id=" InvestorNote" name=" InvestorNote"><?php echo $row['Note'];?></textarea>
                    </div>
                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                        <label for="Description" class="form-label">Description</label>
                        <select class="form-select" id="Description" name="Description">
                            <option value="<?php echo $row['Description'];?>"> <?php echo $row['Description'];?> </option>
                            <?php
                                while ($row102 = mysqli_fetch_assoc($result102)) {
                                    # code...
                                    echo "<option>".$row102['Description']."</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <!-- Actual Currencies as in the DB --> 
                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                        <label for="Currency" class="form-label">Currency</label>
                        <select class="form-select" id="Currency" name="Currency">
                            <option value="<?php echo $row['Currency'];?>"> <?php echo $row['Currency'];?> </option>
                            <?php
                                while ($row105 = mysqli_fetch_assoc($result105)) {
                                    # code...
                                    echo "<option>".$row105['Currency']."</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label for="YearFounded" class="form-label">Year Founded</label>
                        <select class="form-control" name="YearFounded" id="YearFounded">
                                <option value=""> Select...</option>
                        </select>
                    </div>
                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                        <label for="Headquarters" class="form-label">Country</label>
                        <select class="form-select" id="Headquarters" name="Headquarters">
                            <option> Select...</option>
                            <?php
                                while ($row104 = mysqli_fetch_assoc($result104)) {
                                    # code...
                                    echo "<option>".$row104['Country']."</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label for="img" class="form-label">Logo</label>
                        <input type="file" class="form-control" id="img" name="img" >
                    </div>
                </div>
                <p>
                    <Button name="Update" type="submit" value="Update" class="btn btn-primary" formmethod="POST">Update</Button>
                    <a href="../tabs/investor.php" class="btn btn-danger" >Close</a>
                </p>
            </form>
            <?php } ?>
        </main>
        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.6.0.slim.js" integrity="sha256-HwWONEZrpuoh951cQD1ov2HUK5zA5DwJ1DNUXaM6FsY=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
        <script src="../../js/scripts.js"></script>
        <script src="../../js/select2.min.js"></script>
        <script src="../../js/MultiSelect.js"></script>
        <script src="../../js/DateDropDown.js"></script>
    </body>
</html>
