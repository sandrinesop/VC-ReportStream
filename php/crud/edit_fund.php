<?php 
    include_once('../App/connect.php');
    // QUERY DATABASE FROM DATA
    $FundID =$_REQUEST['FundID'];
    $sql="  SELECT 
                Fund.FundID, Fund.Deleted, Fund.DeletedDate, Fund.FundName, GROUP_CONCAT(DISTINCT InvestorName) AS InvestorName, GROUP_CONCAT(DISTINCT PortfolioCompanyName) AS PortfolioCompanyName, Currency.Currency, Fund.CommittedCapital, Fund.MinimumInvestment, Fund.MaximumInvestment, GROUP_CONCAT(DISTINCT InvestmentStage) AS InvestmentStage, GROUP_CONCAT(DISTINCT Industry) AS Industry , Note.Note
            FROM 
                Fund 
                -- JOINING FUNDINVESTOR TO ACCESS LINKED INVESTORS 
            LEFT JOIN 
                FundInvestor 
            ON 
            FundInvestor.FundID = Fund.FundID
            LEFT JOIN 
                Investor 
            ON 
            Investor.InvestorID = FundInvestor.InvestorID
            --    JOINING FUNDPORTFOLIOCOMPANY TO ACCESS LINKED COMPANIES
            LEFT JOIN 
                FundPortfolioCompany 
            ON 
            FundPortfolioCompany.FundID = Fund.FundID
            LEFT JOIN 
                PortfolioCompany 
            ON 
                PortfolioCompany.PortfolioCompanyID = FundPortfolioCompany.PortfolioCompanyID
            --    JOINING FUNDPINVESTMENTSTAGE TO ACCESS LINKED INVESTMENTSTAGE
            LEFT JOIN 
                FundInvestmentStage 
            ON 
            FundInvestmentStage.FundID = Fund.FundID
            LEFT JOIN 
                InvestmentStage 
            ON 
            InvestmentStage.InvestmentStageID = FundInvestmentStage.InvestmentStageID
            --    JOINING FUNDINDUSTRY TO ACCESS LINKED INDUSTRY
            LEFT JOIN 
                FundIndustry 
            ON 
            FundIndustry.FundID = Fund.FundID
            LEFT JOIN 
                Industry 
            ON 
            Industry.IndustryID = FundIndustry.IndustryID
            --    JOINING FUNDNOTE TO ACCESS LINKED NOTE
            LEFT JOIN 
                FundNote 
            ON 
            FundNote.FundID = Fund.FundID
            LEFT JOIN 
                Note 
            ON 
            Note.NoteID = FundNote.NoteID

            LEFT JOIN 
                Currency 
            ON 
                Currency.CurrencyID = Fund.CurrencyID 
            WHERE  
                Fund.Deleted = 0 AND Fund.FundID = '$FundID'

            GROUP BY FundID, Deleted, DeletedDate, FundName, Currency, CommittedCapital, MinimumInvestment, MaximumInvestment,  Note 
    "; 

    $result = mysqli_query($conn, $sql) or die($conn->error);
    $row = mysqli_fetch_assoc($result);

    // PULLING DATA INTO THE DROPDOWN ON THE EDIT/UPDATE SCREEN
    $sql100 = " SELECT DISTINCT 
                    Currency
                FROM 
                    Currency 
                WHERE 
                    Currency IS NOT NULL ORDER BY Currency ASC
    ";
    $result100 = mysqli_query($conn, $sql100);
    // INVESTMENT STAGES
    $sql101 = " SELECT DISTINCT 
                    InvestmentStage
                FROM 
                    InvestmentStage 
                WHERE 
                    InvestmentStage IS NOT NULL ORDER BY InvestmentStage ASC
    ";
    $result101 = mysqli_query($conn, $sql101);

    // ACCESSING INVESTOR TO POPULATE INVESTOR DROPDOWN
    $sql102 = "  SELECT DISTINCT 
                    InvestorName
                FROM 
                    Investor 
                WHERE 
                    InvestorName IS NOT NULL ORDER BY InvestorName ASC
    ";
    $result102 = mysqli_query($conn, $sql102);

    // ACCESSING P.C TO POPULATE INVESTOR DROPDOWN
    $sql103 = "  SELECT DISTINCT 
                    PortfolioCompanyName
                FROM 
                    PortfolioCompany 
                WHERE 
                    PortfolioCompanyName IS NOT NULL ORDER BY PortfolioCompanyName ASC
    ";
    $result103 = mysqli_query($conn, $sql103);
    // ======================================================
    // THE BEGINNING OF THE UPDATE FUND QUERY
    // ======================================================
    $status = "";
    if(isset($_POST['new']) && $_POST['new']==1)
    {
        // mysqli_query($conn, $update) or die($conn->error);
        $status = "Record Updated Successfully. </br></br>
        <a href='../tabs/fund.php'>View Updated Record</a>";
        echo '<p class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12" style="color:#FF0000;">'.$status.'</p>';
        header( "refresh: 3;url= ../tabs/fund.php" );

        // DECLARE VARIABLES
        $FundName                   = mysqli_real_escape_string($conn, $_REQUEST['FundName']);
        $Currency                   = mysqli_real_escape_string($conn, $_REQUEST['Currency']);
        $CommittedCapital           = mysqli_real_escape_string($conn, $_REQUEST['CommittedCapital']);
        $MinimumInvestment          = mysqli_real_escape_string($conn, $_REQUEST['MinimumInvestment']);
        $MaximumInvestment          = mysqli_real_escape_string($conn, $_REQUEST['MaximumInvestment']);

        if(isset($_REQUEST['InvestorName'])){ 
            $Investors          = $_REQUEST['InvestorName'];
        }else {
            // error_reporting(0);
        }

        if(isset($_REQUEST['PortfolioCompanyName'])){ 
            $Companies          = $_REQUEST['PortfolioCompanyName'];
        }else {
            // error_reporting(0);
        }
        if(isset($_REQUEST['InvestmentStage'])){ 
        $InvestmentStages           = $_REQUEST['InvestmentStage'];
        }else {
            // error_reporting(0);
        }
        
        if(isset($_REQUEST['Note'])){ 
            $Note           =   mysqli_real_escape_string($conn, $_POST['Note']);
        }else {
            // error_reporting(0);
        }

        //  BUILDING A DYNAMIC MYSQL UPDATE QUERY BY CREATING AN EMPTY ARRAY AND THEN SETTING CONDITIONAL STATEMENTS TO CHECK IF A VARIABLE IS NOT EMPTY FIRST, IF EMPTY DO NOTHING AND IF SET, THE APPEND IT TO THE ARRAY. THERE ON EXPLODE THE ARRAY TO CONVERT IT INOT A STRING THEN APPEND STRING TO THE UPDATE STATEMENT.
        $updates = array();

        if(!empty($FundName)){
            $updates[] ='FundName="'.$FundName.'"';
        }

        if(!empty($Currency)){
            $updates[] =" CurrencyID = (SELECT C.CurrencyID FROM Currency C WHERE C.Currency = '$Currency')";
        }

        if(!empty($CommittedCapital)){
            $updates[] ='CommittedCapital="'.$CommittedCapital.'"';
        }
        
        if(!empty($MinimumInvestment)){
            $updates[] ='MinimumInvestment="'.$MinimumInvestment.'"';
        }

        if(!empty($MaximumInvestment)){
            $updates[] ='MaximumInvestment="'.$MaximumInvestment.'"';
        }

        $updateString = implode(', ', $updates);

        $updateFund = "UPDATE Fund SET ModifiedDate= NOW(), $updateString WHERE FundID='".$FundID."'";
        $resultUpdate = mysqli_query($conn, $updateFund) or die($conn->error);


        // ===================================================================================
        // A CONDITIONAL STATEMENT TO CHECK IF LINKS BETWEEN FUND AND INVESTORS ALREADY EXISTS
        // ===================================================================================
        // $msg = array();
        if(!empty($Investors)){
            foreach($Investors AS $Investor){
                $prevQuery = "  SELECT 
                                    InvestorID 
                                FROM 
                                    FundInvestor
                                WHERE 
                                FundID = (select Fund.FundID FROM Fund where Fund.FundName = '$FundName') AND InvestorID = (select Investor.InvestorID FROM Investor  where Investor.InvestorName = '$Investor')
                ";
                $prevResult = mysqli_query($conn,$prevQuery);
                if($prevResult->num_rows>0){
                    // IF THIS CONDITION RETURNS TRUE, THAT MEANS A LINK BETWEEN THE INVESTOR AND THE FUND ALREADY EXISTS IN THE DATABASE. IN THAT CASE, WE WILL DELETE THE RECORD AND THEN CREATE UPDATED LINKS IN THE NEXT QUERY.
                    $deleteQuery = "DELETE FROM 
                                        FundInvestor WHERE FundID = (select Fund.FundID FROM Fund where Fund.FundName = '$FundName') AND InvestorID = (select Investor.InvestorID FROM Investor  where Investor.InvestorName = '$Investor') 
                    ";
                    $resultDelete = mysqli_query($conn, $deleteQuery);
                    if($resultDelete){
                        // do nothing
                    }else{
                        echo'There was an error deleting this link: '.mysqli_error($conn);
                    }
                }else{
                    // IF NO LINKS ARE FOUND BETWEEN A COMPANY AND THE INVESTOR, WE WILL THEN CREATE A NEW LINK BETWEEN THAT INVESTOR AND THE COMPANY.
                    $sql4 = "   INSERT INTO 
                                    FundInvestor(FundInvestorID, CreatedDate, ModifiedDate, Deleted, DeletedDate, FundID, InvestorID)
                                VALUES 
                                    (uuid(), now(), now(), 0, NULL, (select Fund.FundID FROM Fund where Fund.FundName = '$FundName'), (select Investor.InvestorID FROM Investor  where Investor.InvestorName = '$Investor'))
                    ";
                    $query4 = mysqli_query($conn, $sql4);
                }
            };
        };

        // ===================================================================================
        // A CONDITIONAL STATEMENT TO CHECK IF LINKS BETWEEN FUND AND COMPANIES ALREADY EXISTS
        // ===================================================================================
        if(!empty($Companies)){
            foreach($Companies AS $PortfolioCompany){
                $prevQuery = "  SELECT 
                                    PortfolioCompanyID 
                                FROM 
                                    FundPortfolioCompany
                                WHERE 
                                    FundID = (select Fund.FundID FROM Fund where Fund.FundName = '$FundName') AND PortfolioCompanyID = (select PortfolioCompany.PortfolioCompanyID FROM PortfolioCompany  where PortfolioCompany.PortfolioCompanyName = '$PortfolioCompany')
                ";
                $prevResult = mysqli_query($conn,$prevQuery);
                if($prevResult->num_rows>0){
                    // IF THIS CONDITION RETURNS TRUE, THAT MEANS A LINK BETWEEN THE INVESTOR AND THE FUND ALREADY EXISTS IN THE DATABASE. IN THAT CASE, WE WILL DELETE THE RECORD AND THEN CREATE UPDATED LINKS IN THE NEXT QUERY.
                    $deleteQuery = "DELETE FROM 
                                        FundPortfolioCompany WHERE FundID = (select Fund.FundID FROM Fund where Fund.FundName = '$FundName') AND PortfolioCompanyID = (select PortfolioCompany.PortfolioCompanyID FROM PortfolioCompany  where PortfolioCompany.PortfolioCompanyName = '$PortfolioCompany')
                    ";
                    $resultDelete = mysqli_query($conn, $deleteQuery);
                    if($resultDelete){
                        // do nothing
                    }else{
                        echo'There was an error deleting this link: '.mysqli_error($conn);
                    }
                }else{
                    // IF NO LINKS ARE FOUND BETWEEN A COMPANY AND THE FUND, WE WILL THEN CREATE A NEW LINK BETWEEN THAT FUND AND THE COMPANY.
                    $sql5 = "   INSERT INTO 
                                    FundPortfolioCompany(FundPortfolioCompanyID, CreatedDate, ModifiedDate, Deleted, DeletedDate, FundID, PortfolioCompanyID)
                                VALUES 
                                    (uuid(), now(), now(), 0, NULL, (select Fund.FundID FROM Fund where Fund.FundName = '$FundName'), (select PortfolioCompany.PortfolioCompanyID FROM PortfolioCompany  where PortfolioCompany.PortfolioCompanyName = '$PortfolioCompany'))
                    ";
                    // echo $sql5;
                    $query5 = mysqli_query($conn, $sql5);

                    if($query5){
                        // do nothing
                    }else{
                        echo'There was an error creating this link: '.$PortfolioCompany.mysqli_error($conn);
                    }
                }
            };
        }

        // ===================================================================================
        // A CONDITIONAL STATEMENT TO CHECK IF LINKS BETWEEN FUND AND COMPANIES ALREADY EXISTS
        // ===================================================================================
        if(!empty($InvestmentStages)){
            foreach($InvestmentStages AS $InvestmentStage){
                $prevQuery = "  SELECT 
                                    InvestmentStageID 
                                FROM 
                                    FundInvestmentStage
                                WHERE 
                                    FundID = (select Fund.FundID FROM Fund where Fund.FundName = '$FundName') AND InvestmentStageID = (select InvestmentStage.InvestmentStageID FROM InvestmentStage  where InvestmentStage.InvestmentStage = '$InvestmentStage')
                ";
                $prevResult = mysqli_query($conn,$prevQuery);
                if($prevResult->num_rows>0){
                    // IF THIS CONDITION RETURNS TRUE, THAT MEANS A LINK BETWEEN THE INVESTOR AND THE FUND ALREADY EXISTS IN THE DATABASE. IN THAT CASE, WE WILL DELETE THE RECORD AND THEN CREATE UPDATED LINKS IN THE NEXT QUERY.
                    $deleteQuery = "DELETE FROM 
                                        FundInvestmentStage WHERE FundID = (select Fund.FundID FROM Fund where Fund.FundName = '$FundName') AND InvestmentStageID = (select InvestmentStage.InvestmentStageID FROM InvestmentStage  where InvestmentStage.InvestmentStage = '$InvestmentStage')
                    ";
                    $resultDelete = mysqli_query($conn, $deleteQuery);
                    if($resultDelete){
                        // do nothing
                    }else{
                        echo'There was an error deleting this link: '.mysqli_error($conn);
                    }
                }else{
                    // IF NO LINKS ARE FOUND BETWEEN A COMPANY AND THE FUND, WE WILL THEN CREATE A NEW LINK BETWEEN THAT FUND AND THE COMPANY.
                    $sql6 = "   INSERT INTO 
                                    FundInvestmentStage(FundInvestmentStageID, CreatedDate, ModifiedDate, Deleted, DeletedDate, FundID, InvestmentStageID)
                                VALUES 
                                    (uuid(), now(), now(), 0, NULL, (select Fund.FundID FROM Fund where Fund.FundName = '$FundName'),(select InvestmentStage.InvestmentStageID FROM InvestmentStage where InvestmentStage.InvestmentStage = '$InvestmentStage'))
                    ";
                    $query6 = mysqli_query($conn, $sql6);

                    if($query6){
                        // do nothing
                    }else{
                        echo'There was an error creating this link: '.mysqli_error($conn);
                    }
                }
            };
        }
        // ===================================================================================
        // A CODE BLOCK TO UPDATE THE FUND NOTE
        // ===================================================================================
        // $msg = array();
        $updates2 = array();
        if(!empty($Note)){
            $updates2[] ='Note="'.$Note.'"';
        };
        // print_r($updates2);
        $updateString2 = implode(', ', $updates2);
        // echo $updateString2;
        $updateNote = "UPDATE Note SET ModifiedDate= NOW(), $updateString2 WHERE NoteID= (SELECT FundNote.NoteID FROM FundNote WHERE FundNote.FundID='".$FundID."')";
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
            <form name="form" method="post" action="" class="form container">
                <div class="row">
                    <input type="hidden" name="new" value="1" />
                    <input name="FundID" type="hidden" value="<?php echo $row['FundID'];?>"/>
                    <p class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label for="Website" class="form-label"> Fund Name </label>
                        <input class="form-control col" type="text" name="FundName" placeholder="Enter FundName"  value="<?php echo $row['FundName'];?>" />
                    </p>
                    <p class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label for="InvestorName" class="form-label">Investment Manager(s)</label>
                        <select  class="form-select InvestorName" id="InvestorName" name="InvestorName[]" multiple="true" >
                            <option value="<?php echo $row['InvestorName'];?>"> <?php echo $row['InvestorName'];?> </option>
                            <?php
                                while ($row102 = mysqli_fetch_assoc($result102)) {
                                    # code...
                                    echo "<option>".$row102['InvestorName']."</option>";
                                }
                            ?>
                        </select>
                    </p>
                    <p class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label for="PortfolioCompanyName" class="form-label">Portfolio Company List</label>
                        <select class="form-control PortfolioCompanyName" name="PortfolioCompanyName[]" multiple="true" id="PortfolioCompanyName">
                            <option value="<?php echo $row['PortfolioCompanyName'];?>"> <?php echo $row['PortfolioCompanyName'];?> </option>
                            <?php
                                while ($row103 = mysqli_fetch_assoc($result103)) {
                                    # code...
                                    echo "<option>".$row103['PortfolioCompanyName']."</option>";
                                }
                            ?>
                        </select>
                    </p>
                    <p class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label for="Currency" class="form-label">Currency</label>
                        <select class="form-select" id="Currency" name="Currency" >
                            <option value="<?php echo $row['Currency'];?>"> <?php echo $row['Currency'];?> </option>
                            <?php
                                while ($row100 = mysqli_fetch_assoc($result100)) {
                                    # code...
                                    echo "<option>".$row100['Currency']."</option>";
                                }
                            ?>
                        </select>
                    </p>
                    <p class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label for="CommittedCapital" class="form-label">Committed Capital</label>
                        <input class="form-control col" type="text" name="CommittedCapital" placeholder="Enter CommittedCapital" value="<?php echo $row['CommittedCapital'];?>"/>
                    </p>
                    <p class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label for="MinimumInvestment" class="form-label">Minimum Investment</label>
                        <input class="form-control col" type="text" name="MinimumInvestment" placeholder="Enter MinimumInvestment"  value="<?php echo $row['MinimumInvestment'];?>"/>
                    </p>
                    <p class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label for="MaximumInvestment" class="form-label">Maximum Investment</label>
                        <input class="form-control col" type="text" name="MaximumInvestment" placeholder="Enter MaximumInvestment"  value="<?php echo $row['MaximumInvestment'];?>"/>
                    </p>
                    <p class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label for="InvestmentStage" class="form-label">Investment Stage</label>
                        <select class="form-select InvestmentStage" id="InvestmentStage" name="InvestmentStage[]" multiple="true" >
                            <option value="<?php echo $row['InvestmentStage'];?>"> <?php echo $row['InvestmentStage'];?> </option>
                            <?php
                                while ($row101 = mysqli_fetch_assoc($result101)) {
                                    # code...
                                    echo "<option>".$row101['InvestmentStage']."</option>";
                                }
                            ?>
                        </select>
                    </p>
                    <p class="mb-3 ">
                        <label for="Note" class="form-label">Note</label>
                        <textarea class="form-control col" type="text" name="Note" placeholder="Enter Note"> <?php echo $row['Note'];?></textarea>
                    </p>
                </div>
                <p>
                    <Button name="Update" type="submit" value="Update" class="btn btn-primary" formmethod="POST">Update</Button>
                    <a href="../tabs/fund.php" class="btn btn-danger" >Close</a>
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