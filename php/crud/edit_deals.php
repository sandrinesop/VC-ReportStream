<?php 
    include_once('../App/connect.php');
    // QUERY DATABASE FROM DATA
    $DealsID =$_REQUEST['DealsID'];
    $sql=" SELECT DISTINCT
                Deals.DealsID, News.NewsID, News.NewsURL, News.NewsDate, PortfolioCompany.PortfolioCompanyName, GROUP_CONCAT(DISTINCT InvestorName) AS InvestorName, GROUP_CONCAT(DISTINCT FundName) AS FundName, FORMAT(deals.InvestmentValue, 'c', 'en-US') AS 'InvestmentValue', deals.stake, GROUP_CONCAT(DISTINCT Industry) AS Industry , GROUP_CONCAT(DISTINCT Sector.Sector) AS Sector, GROUP_CONCAT(DISTINCT InvestmentStage) AS InvestmentStage, Country.Country, UserDetail.UserFullName,Note.Note, Roletype.RoleType
            FROM 
                Deals 
            -- Include investor table data through the linking table dealsinvestor
            LEFT JOIN
                DealsInvestor
            ON 
                DealsInvestor.DealsID = Deals.DealsID
            -- Include Investor table data
            LEFT JOIN
                Investor
            ON
                Investor.InvestorID = DealsInvestor.InvestorID
            -- Include fund table data through the linking table dealsfund
            LEFT JOIN
                DealsFund
            ON 
                DealsFund.DealsID = Deals.DealsID 
            -- include Fund table data
            LEFT JOIN
                Fund
            ON
                Fund.FundID = DealsFund.FundID 
            -- Include News table data 
            LEFT JOIN 
                News 
            ON
                News.NewsID = deals.NewsID 
            LEFT JOIN 
            -- Include PortfoliCompany table data
                PortfolioCompany
            ON
                PortfolioCompany.PortfolioCompanyID = deals.PortfolioCompanyID
            LEFT JOIN 
            -- Link investment stage to fund
                FundInvestmentStage      
            ON          
                FundInvestmentStage.FundID = Fund.FundID 
            LEFT JOIN
                InvestmentStage
            ON
                InvestmentStage.InvestmentStageID = FundInvestmentStage.InvestmentStageID 
            LEFT JOIN 
                PortfolioCompanyCountry
            ON
                PortfolioCompanyCountry.PortfolioCompanyID = deals.PortfolioCompanyID
            LEFT JOIN 
                Country
            ON 
                Country.CountryID = PortfolioCompanyCountry.CountryID
            LEFT JOIN 
                DealsIndustry
            ON 
                DealsIndustry.DealsID = deals.DealsID
            LEFT JOIN 
                Industry
            ON 
                Industry.IndustryID = DealsIndustry.IndustryID
            LEFT JOIN 
                DealsSector
            ON 
                DealsSector.DealsID = Deals.DealsID
            LEFT JOIN 
                Sector
            ON 
                Sector.SectorID = DealsSector.SectorID
            LEFT JOIN 
                UserDetail
            ON 
                UserDetail.UserDetailID = Deals.UserDetailID
            LEFT JOIN 
                DealsNote
            ON 
                DealsNote.DealsID = Deals.DealsID
            LEFT JOIN 
                Note
            ON 
                Note.NoteID = DealsNote.NoteID
            LEFT JOIN 
                RoleType
            ON 
                RoleType.RoleTypeID = UserDetail.RoleTypeID
            WHERE 
                Deals.Deleted = 0 AND Deals.DealsID = '$DealsID'
            GROUP BY Deals.DealsID, NewsID, NewsURL, NewsDate, PortfolioCompanyName, Deals.InvestmentValue, Deals.stake, Country, UserFullName, RoleType
            ORDER BY  news.NewsDate
    "; 
    $result = mysqli_query($conn, $sql) or die($conn->error);
    $row = mysqli_fetch_assoc($result);

    
    //==================================================== 
    // BELOW IS CODE DISPLAYING DATA ON deals SCREEN TABLE
    //====================================================
    //========== | PORTFOLIO COMPANY TABLE | =============
    //====================================================
    // PORTFOLIO COMPANY DETAILS. THIS OVERFLOWS IN THE <OPTION ELEMENT> AND THAT IS WHY I USED THE SUBSTRING METHOD TO TRUNCATE THE STRONG
    $sql1 = " SELECT DISTINCT 
                PortfolioCompanyName, Website, SUBSTRING(Details, 1, 55) AS Details FROM PortfolioCompany 
            JOIN 
                Country ON country.CountryID = PortfolioCompany.Headquarters 
            WHERE Website IS NOT NULL AND Details IS NOT NULL";
            
    $result1 = mysqli_query($conn, $sql1);
    $sql3 = "   SELECT DISTINCT 
                    Country 
                FROM 
                    PortfolioCompany 
                LEFT JOIN 
                    Country ON Country.CountryID = PortfolioCompany.Headquarters 
                WHERE Country IS NOT NULL";
    $result3 = mysqli_query($conn, $sql3);
    // Pulling Startup Data into the Currency dropdown
    $sql4 = "   SELECT DISTINCT 
                    Currency 
                FROM 
                    PortfolioCompany 
                LEFT JOIN 
                    Currency ON currency.CurrencyID = PortfolioCompany.CurrencyID 
                WHERE Currency IS NOT NULL";
    $result4 = mysqli_query($conn, $sql4);
    //=================================================== 
    //============== | USERDETAIL TABLE | ===============
    //===================================================
    $sql5 = "   SELECT DISTINCT 
                    UserFullName
                FROM 
                    UserDetail 
                WHERE 
                    UserFullName IS NOT NULL ORDER BY UserFullName ASC";
    $result5 = mysqli_query($conn, $sql5);
    // Pulling UserDetail Data into the Email dropdown
    $sql6 = "   SELECT DISTINCT 
                    Email, ContactNumber1 
                FROM 
                    UserDetail 
                WHERE Email IS NOT NULL";
    $result6 = mysqli_query($conn, $sql6);
    // Pulling UserDetail Data into the RoleType dropdown
    $sql7 = "   SELECT DISTINCT 
                    RoleType.RoleType
                FROM 
                    UserDetail 
                LEFT JOIN 
                    RoleType ON RoleType.RoleTypeID = UserDetail.RoleTypeID ";
    $result7 = mysqli_query($conn, $sql7);
    // Pulling UserDetail Data into the Gender dropdown
    $sql8 = "   SELECT DISTINCT 
                     Gender.Gender 
                FROM 
                    UserDetail 
                LEFT JOIN 
                    Gender ON Gender.GenderID = UserDetail.GenderID ";
    $result8 = mysqli_query($conn, $sql8);
    // Pulling UserDetail Data into the Race dropdown
    $sql9 = "   SELECT DISTINCT 
                     Race.Race 
                FROM 
                    UserDetail 
                LEFT JOIN 
                    Race ON Race.RaceID = UserDetail.RaceID ";
    $result9 = mysqli_query($conn, $sql9);
    //=================================================== 
    //============== | INVESTOR TABLE | =================
    //===================================================
    $sqlA1 = "   SELECT DISTINCT 
                InvestorName
            FROM 
                Investor 
            WHERE 
                InvestorName IS NOT NULL ORDER BY InvestorName ASC";
            $resultA1 = mysqli_query($conn, $sqlA1);

            $sqlA2 = "   SELECT DISTINCT 
                Website
            FROM 
                Investor 
            WHERE 
                Website IS NOT NULL ORDER BY Website ASC";
    $resultA2 = mysqli_query($conn, $sqlA2);
    // INVETSOR NOTES. THIS OVERFLOWS IN THE <OPTION ELEMENT> AND THAT IS WHY I USED THE SUBSTRING METHOD TO TRUNCATE THE STRONG
    // Pulling Investor Data into the Notes dropdown
    $sqlA3 = "  SELECT DISTINCT
                    SUBSTRING(Note, 1, 55) AS Note 
                FROM 
                    Note
                WHERE 
                    Note IS NOT NULL";
    $resultA3 = mysqli_query($conn, $sqlA3);
    // Pulling Investor Data into the impact tag dropdown
    $sqlA4 = "  SELECT DISTINCT
                    ImpactTag
                FROM 
                Investor
                WHERE 
                    ImpactTag IS NOT NULL";
    $resultA4 = mysqli_query($conn, $sqlA4);
    // Pulling Investor Data into the Investor Headquarters dropdown
    $sqlA5 = "  SELECT DISTINCT
                    Country
                FROM 
                    Investor
                JOIN 
                    Country ON country.CountryID = Investor.Headquarters";
    $resultA5 = mysqli_query($conn, $sqlA5);
    // Pulling Investor Data into the impact tag dropdown
    $sqlA6 = "  SELECT DISTINCT
                    Description
                FROM 
                    Investor
                JOIN 
                    Description ON Description.DescriptionID = Investor.DescriptionID";
    $resultA6 = mysqli_query($conn, $sqlA6);
    //=================================================== 
    //================ | FUND TABLE | ===================
    //===================================================
    // Pulling Fund Data into the Fund Section dropdown
    $sqlB = "  SELECT DISTINCT
                    FundName, CommittedCapitalOfFund, CommittedCapital, MinimumInvestment, MaximumInvestment
                FROM 
                    Fund
                WHERE FundName IS NOT NULL AND CommittedCapitalOfFund IS NOT NULL AND CommittedCapital IS NOT NULL AND MinimumInvestment IS NOT NULL AND MaximumInvestment IS NOT NULL ";
    $resultB = mysqli_query($conn, $sqlB);
    // Pulling Fund Data into the FundName dropdown
    $sqlB1 = "  SELECT DISTINCT
                    InvestmentStage
                FROM 
                    InvestmentStage
                WHERE InvestmentStage IS NOT NULL ORDER BY InvestmentStage ASC";
    $resultB1 = mysqli_query($conn, $sqlB1);
    // THE CODE SKELETON FOR EDITING AND UPDATING DEALS
    if(isset($_POST['new']) && $_POST['new']==1)
    {
        // HEADERS ARE SENT BEFORE ANYTHING ELSE OTHERWISE THEY WON'T WORK
        $status = "Record Updated Successfully. </br></br>
        <a href='../tabs/NewDeals.php'>View Updated Record</a>";
        echo '<p style="color:#FF0000;">'.$status.'</p>';
        header( "refresh: 5;url= ../tabs/NewDeals.php" );

        /*
            // -------------------------------------------------------------------------//
            //                           UPDATE THE NEWS TABLE                          //
            // -------------------------------------------------------------------------//
        */ 
        if(isset($_REQUEST['NewsDate'])){ 
            $NewsDate       = date('Y-m-d', strtotime($_REQUEST['NewsDate']));
        }else {
            // error_reporting(0);
        }   

        if(isset($_REQUEST['NewsURL'])){ 
            $NewsURL        = mysqli_real_escape_string($conn, $_REQUEST['NewsURL']);
        }else {
            // error_reporting(0);
        }
        
        // ===========================================================
        // ===========================================================
        // BUILDING A DYNAMIC QUERY TO UPDATE THE NEWS TABLE
        // ===========================================================
        // ===========================================================
        $updateNews = array();

        if(!empty($NewsDate)){
            $updateNews[] ='NewsDate="'.$NewsDate.'"';
        }

        if(!empty($NewsURL)){
            $updateNews[] ='NewsURL="'.$NewsURL.'"';
        }
        // CONVERT THE NEWS ARRAY WITH THE DYNAMIC PARAMS INTO A STRING USING THE IMPLODE METHOD
        $updateNewsString = implode(', ', $updateNews);
        // THE NEWS UPDATE QUERY
        $updateNewsQuery = "UPDATE News SET ModifiedDate= NOW(), $updateNewsString WHERE NewsID=(select distinct Deals.NewsID FROM Deals where Deals.DealsID = '$DealsID')";
        $resultNewsUpdate = mysqli_query($conn, $updateNewsQuery) or die($conn->error);

        if($resultNewsUpdate){
            // echo 'Success!';
        }else{
            echo 'Error Updating the Deal Date or Link: '.mysqli_error($conn);
        }
        /*
            // ==========================================================================//
            //                            UPDATE THE DEALS TABLE                         //
            // ==========================================================================//
        */
        if(isset($_REQUEST['PortfolioCompanyName'])){ 
            $PortfolioCompanyName    = $_REQUEST['PortfolioCompanyName'];
        }else {
            // error_reporting(0);
        }

        if(isset($_REQUEST['Stake'])){ 
            $Stake               = mysqli_real_escape_string($conn, $_REQUEST['Stake']);
        }else {
            // error_reporting(0);
        }

        if(isset($_REQUEST['InvestmentValue'])){ 
            $InvestmentValue     = mysqli_real_escape_string($conn, $_REQUEST['InvestmentValue']);
        }else {
            // error_reporting(0);
        }        
        if(isset($_REQUEST['UserFullName'])){ 
            $StartUpContact     = mysqli_real_escape_string($conn, $_REQUEST['UserFullName']);
        }else {
            // error_reporting(0);
        }

        // if(isset($_REQUEST['NewsNote'])){ 
        //     $NewsNote       = mysqli_real_escape_string($conn, $_POST['NewsNote']);
        // }else {
        //     // error_reporting(0);
        // }

        // ===========================================================
        // ===========================================================
        // BUILDING A DYNAMIC QUERY TO UPDATE THE COMPANIES TABLE
        // ===========================================================
        // ===========================================================
        $updateDeal = array();

        if(!empty($PortfolioCompanyName)){
            $updateDeal[] ="PortfolioCompanyID= (select PortfolioCompany.PortfolioCompanyID FROM PortfolioCompany where PortfolioCompany.PortfolioCompanyName = '$PortfolioCompanyName')";
        }

        if(!empty($Stake)){
            $updateDeal[] ='stake="'.$Stake.'"';
        }

        if(!empty($InvestmentValue)){
            $updateDeal[] ='InvestmentValue="'.$InvestmentValue.'"';
        }
        
        if(!empty($StartUpContact)){
            $updateDeal[] ="UserDetailID= (select UserDetail.UserDetailID FROM UserDetail where UserDetail.UserFullName = '$StartUpContact')";
        }
        // print_r($updateDeal);
        // CONVERT THE NEWS ARRAY WITH THE DYNAMIC PARAMS INTO A STRING USING THE IMPLODE METHOD
        $updateDealString = implode(', ', $updateDeal);
        // THE NEWS UPDATE QUERY
        $updateDealsQuery = "UPDATE Deals SET ModifiedDate= NOW(), $updateDealString WHERE DealsID = '".$DealsID."'";
        $resultDealsUpdate = mysqli_query($conn, $updateDealsQuery);

        if($resultDealsUpdate){
            // echo 'Success!';
        }else{
            echo 'Error Updating the Deal: '.mysqli_error($conn);
        }
        // $NewsNote       = mysqli_real_escape_string($conn, $_REQUEST['NewsNote']);

        if(isset($_REQUEST['InvestorName'])){ 
            $Investors          = $_REQUEST['InvestorName'];
        }else {
            // error_reporting(0);
        }
        
        if(isset($_REQUEST['FundName'])){ 
            $Funds                   = $_REQUEST['FundName'];
        }else {
            // error_reporting(0);
        }

        if(isset($_REQUEST['Industry'])){ 
            $Industries              =  $_REQUEST['Industry'];
        }else {
            // error_reporting(0);
        }
        
        if(isset($_REQUEST['Sector'])){ 
            $Sectors                 =  $_REQUEST['Sector'];
        }else {
            // error_reporting(0);
        }
        /*  
            ==============================================================================
                UPDATE LINKINGS BETWEEN DEALS AND INSETORS, FUNDS, INDUSTRY AND SECTOR
            ==============================================================================
        */
        if(!empty($Investors)){
            foreach($Investors AS $Investor){
                $prevQuery = "  SELECT 
                                    InvestorID 
                                FROM 
                                    DealsInvestor
                                WHERE 
                                    DealsID = '$DealsID' AND InvestorID = (select Investor.InvestorID FROM Investor  where Investor.InvestorName = '$Investor')
                        ";
                $prevResult = mysqli_query($conn,$prevQuery);
                if($prevResult->num_rows>0){
                    // $msg[] =$sector;
                    // IF THIS CONDITION RETURNS TRUE, THAT MEANS A LINK BETWEEN THE INVESTOR AND THE DEAL ALREADY EXISTS IN THE DATABASE. IN THAT CASE, WE WILL DELETE THE RECORD AND THEN CREATE UPDATED LINKS IN THE NEXT QUERY.
                    $deleteQuery = "DELETE FROM DealsInvestor WHERE DealsID = '$DealsID' AND InvestorID = (select Investor.InvestorID FROM Investor  where Investor.InvestorName = '$Investor')   ";
                    $resultDelete = mysqli_query($conn, $deleteQuery);
                    if($resultDelete){
                        // do nothing
                    }else{
                        echo'There is an error here '.mysqli_error($conn);
                    }
                }else{
                    // IF NO LINKS ARE FOUND BETWEEN A DEAL AND THE INVESTOR, WE WILL THEN CREATE A NEW LINK BETWEEN THAT INVESTOR AND THE DEAL.
                    $sql4 = "   INSERT IGNORE INTO 
                                    DealsInvestor(DealsInvestorID, CreatedDate, ModifiedDate, Deleted, DeletedDate, DealsID, InvestorID)
                                VALUES 
                                    (uuid(), now(), now(), 0, NULL, '$DealsID', (select Investor.InvestorID FROM Investor  where Investor.InvestorName = '$Investor'))
                    ";
                    $query4 = mysqli_query($conn, $sql4);
                }
            };
        }
        // ===================================================================================
        // A CONDITIONAL STATEMENT TO CHECK IF LINKS BETWEEN COMPANY AND FUND ALREADY EXISTS
        // ===================================================================================
        // $msg = array();
        if(!empty($Funds)){
            foreach($Funds AS $Fund){
                $prevQuery1 = "  SELECT 
                                    FundID 
                                FROM 
                                    DealsFund
                                WHERE 
                                    DealsID = '$DealsID' AND FundID = (select Fund.FundID FROM Fund where Fund.FundName = '$Fund')
                ";
                $prevResult1 = mysqli_query($conn,$prevQuery1);
                if($prevResult1 !== false && $prevResult1-> num_rows>0){
                    // $msg[] =$Fund;
                    // IF THIS CONDITION RETURNS TRUE, THAT MEANS A LINK BETWEEN THE SECTOR AND THE COMPANY ALREADY EXISTS IN THE DATABASE. IN THAT CASE, WE WILL DELETE THE RECORD AND THEN CREATE UPDATED LINKS IN THE NEXT QUERY.
                    $deleteQuery1 = "DELETE FROM DealsFund WHERE DealsID = '$DealsID' AND FundID = (select Fund.FundID FROM Fund where Fund.FundName = '$Fund')";
                    mysqli_query($conn, $deleteQuery1);
                }else{
                    // IF NO LINKS ARE FOUND BETWEEN A COMPANY AND THE FUND, WE WILL THEN CREATE A NEW LINK BETWEEN THAT FUND AND THE COMPANY.
                    $sql105 = "     INSERT IGNORE INTO 
                                        DealsFund(DealsFundID, CreatedDate, ModifiedDate, Deleted, DeletedDate, DealsID, FundID)
                                    VALUES 
                                        (uuid(), now(), now(), 0, NULL, '$DealsID', (select Fund.FundID FROM Fund where Fund.FundName = '$Fund'))";
                    $query105 = mysqli_query($conn, $sql105);
                }
            };
        };
        // ===================================================================================
        // A CONDITIONAL STATEMENT TO CHECK IF LINKS BETWEEN DEAL AND SECTOR ALREADY EXISTS
        // ===================================================================================
        // $msg = array();
        // $SectorList = explode(",", $Sectors);
        if(!empty($Sectors)){
            foreach($Sectors AS $sector){
                $prevQuery = "  SELECT 
                                    SectorID 
                                FROM 
                                    DealsSector
                                WHERE 
                                    DealsID = '$DealsID' AND SectorID = (select S.SectorID FROM sector S where S.Sector = '$sector')
                        ";
                $prevResult = mysqli_query($conn,$prevQuery);
                if($prevResult->num_rows>0){
                    // $msg[] =$sector;
                    // IF THIS CONDITION RETURNS TRUE, THAT MEANS A LINK BETWEEN THE SECTOR AND THE DEAL ALREADY EXISTS IN THE DATABASE. IN THAT CASE, WE WILL DELETE THE RECORD AND THEN CREATE UPDATED LINKS IN THE NEXT QUERY.
                    $deleteQuery = "DELETE FROM DealsSector WHERE DealsID = '$DealsID' AND SectorID = (select S.SectorID FROM sector S where S.Sector = '$sector')";
                    mysqli_query($conn, $deleteQuery);
                }else{
                    // IF NO LINKS ARE FOUND BETWEEN A DEAL AND THE SECTOR, WE WILL THEN CREATE A NEW LINK BETWEEN THAT SECTOR AND THE DEAL.
                    $sql99 = "  INSERT IGNORE INTO DealsSector(DealsSectorID, CreatedDate, ModifiedDate, Deleted, DeletedDate, DealsID, SectorID)
                                VALUES (uuid(), now(), now(), 0, NULL,'$DealsID', (select S.SectorID FROM sector S where S.Sector = '$sector'))";
                    $query99 = mysqli_query($conn, $sql99);
                }
            };
        }else{
            // Do nothing
        }
        // =======================================================================================
        // A CONDITIONAL STATEMENT TO CHECK IF LINKS BETWEEN DEAL AND INDUSTRIES ALREADY EXISTS
        // =======================================================================================
        // CREATE AN EMPTY ARRAY TO STORE INDUSTRIES 
        // $msg = array();
        if(!empty($Industries)){
            $IndustryList = explode(",", $Industries);
            foreach($IndustryList AS $Industry){ 
                // CHECK IF SELECTED INDUSTRY ALREADY EXISTS IN THE DB OR NOT 
                $prevQuery = "  SELECT 
                                    IndustryID 
                                FROM 
                                    DealsIndustry
                                WHERE 
                                    DealsID = '$DealsID' AND IndustryID = (select Industry.IndustryID FROM Industry where Industry.Industry = '$Industry')
                        ";
                $prevResult = mysqli_query($conn,$prevQuery);
                if($prevResult->num_rows>0){
                    // $msg[] = $Industry;
                    // IF THIS CONDITION RETURNS TRUE, THAT MEANS A LINK BETWEEN THE INDUSTRY AND THE DEAL ALREADY EXISTS IN THE DATABASE. IN THAT CASE, WE WILL DELETE THE RECORD AND THEN CREATE UPDATED LINKS IN THE NEXT QUERY.
                    $deleteQuery = "DELETE FROM DealsIndustry WHERE DealsID = '$DealsID'  AND IndustryID = (select Industry.IndustryID FROM Industry where Industry.Industry = '$Industry')";
                    mysqli_query($conn, $deleteQuery);
                }else{
                    // IF NO LINKS ARE FOUND BETWEEN A DEAL AND THE INDUSTRY, WE WILL THEN CREATE A NEW LINK BETWEEN THAT INDUSTRY AND THE DEAL.
                    $sql98 = "   INSERT IGNORE INTO 
                                    DealsIndustry(DealsIndustryID, CreatedDate, ModifiedDate, Deleted, DeletedDate, DealsID, IndustryID)
                                VALUES 
                                    (uuid(), now(), now(), 0, NULL,'$DealsID', (select Industry.IndustryID FROM Industry where Industry.Industry = '$Industry'))";
                    $query98 = mysqli_query($conn, $sql98);
                    if($query98){
                        // Do nothing
                    } else {
                        echo 'Oops! There was an error inserting the Industry IDs from the array'.mysqli_error($conn).'<br/>';
                    }
                }
            };
        };
        // ===================================================================================
        // A CODE BLOCK TO UPDATE THE News/Deal NOTE
        // ===================================================================================
        // $msg = array();
        // $updates2 = array();
        // if(!empty($NewsNote)){
        //     $updates2[] ="Note='".$NewsNote."'";
        // };
        // // print_r($updates2);
        // $updateString2 = implode(', ', $updates2);
        // // echo $updateString2;
        // $updateNote = "UPDATE Note SET ModifiedDate= NOW(), $updateString2 WHERE Note.NoteID = (SELECT DealsNote.NoteID FROM DealsNote WHERE DealsNote.DealsID = '$DealsID')";
        // echo $updateNote;
        // $resultUpdate2 = mysqli_query($conn, $updateNote) ;
        // if($resultUpdate2){
        //     // do nothing
        // }else{
        //     echo 'error: '.mysqli_error($conn); 
        // }
        
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
            <form class="container" action="" method="POST" enctype="multipart/form-data">
               
                <input type="hidden" name="new" value="1" />
                <input name="DealsID" type="hidden" value="<?php echo $row['DealsID'];?>" />
                <div class="row"> 
                    <h5>
                        News 
                    </h5>
                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 "> 
                        <label for="NewsDate" class="form-label">News Date</label>
                        <input type="date" value="<?php echo $row['NewsDate'];?>" class="form-control" id="NewsDate" name="NewsDate" >
                    </div>
                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                        <label for="NewsURL" class="form-label">News Link</label>
                        <input type="text" class="form-control" id="NewsURL" name="NewsURL" value="<?php echo $row['NewsURL'];?>">
                    </div>
                    <!-- <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                        <label for="NewsNote" class="form-label">News Note</label>
                        <textarea class="form-control" aria-label="With textarea" id="NewsNote" name="NewsNote" value=""></textarea>
                    </div>   -->
                </div>
                <!--    
                        =========================================================================
                        ===================== STARTUP COMPANY SECTION ===========================
                        =========================================================================
                -->
                <div class="row">  
                    <h5>
                        Company Details
                    </h5>
                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                        <label for="PortfolioCompanyName" class="form-label"> Portfolio Company Name</label>
                        <select class="form-select" id="PortfolioCompanyName" name="PortfolioCompanyName" >
                            <option > <?php echo $row['PortfolioCompanyName'];?></option>
                            <?php
                                while ($row1 = mysqli_fetch_assoc($result1)) {
                                    # code...
                                    echo "<option>".$row1['PortfolioCompanyName']."</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                        <label for="Stake" class="form-label">Stake</label>
                        <input type="number" class="form-control" id="Stake" name="Stake"  value="<?php echo $row['stake'];?>">
                        <small style="color:red;">Place a zero if stake not disclosed </small>
                    </div>
                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                        <label for="InvestmentValue" class="form-label">Total Investment Value</label>
                        <input type="number" class="form-control" id="InvestmentValue" name="InvestmentValue" value="<?php echo $row['InvestmentValue']?>">
                    </div>
                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 "> 
                        <label for="UserFullName" class="form-label">Contact Person</label>
                        <select class="form-select" id="UserFullName" name="UserFullName" >
                            <option value="<?php echo $row['UserFullName'];?>"> <?php echo $row['UserFullName'];?> </option>
                            <?php
                                while ($row5 = mysqli_fetch_assoc($result5)) {
                                    # code...
                                    echo "<option>".$row5['UserFullName']."</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <!-- <div class="mb-3 "> 
                        <label for="Note" class="form-label">Note</label>
                        <textarea class="form-control col" type="text" name="Note" placeholder="Enter Note"> <?php echo $row['Note'];?></textarea>
                    </div> -->
                    <!-- 
                        /////////////////////
                         INDUSTRY DROPDOWN
                        /////////////////////
                    -->
                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                        <label for="Industry" class="form-label">Industry</label>
                        <select id="Industry" name="Industry" class="form-select">
                            <option>choose...</option>
                            <option value="Artificial Intelligence">Artificial Intelligence</option>
                            <option value="Clothing Apparel">Clothing Apparel</option>
                            <option value="Administrative Services">Administrative Services</option>
                            <option value="Advertising">Advertising</option>
                            <option value="Agriculture and Farming">Agriculture and Farming</option>
                            <option value="Apps">Apps</option>
                            <option value="Biotechnology">Biotechnology</option>
                            <option value="Commerce and Shopping">Commerce and Shopping</option>
                            <option value="Community and Lifestyle">Community and Lifestyle</option>
                            <option value="Consumer Electronics">Consumer Electronics</option>
                            <option value="Consumer Goods">Consumer Goods</option>
                            <option value="Content and Publishing">Content and Publishing</option>
                            <option value="Data and Analytics">Data and Analytics</option>
                            <option value="Design">Design</option> 
                            <option value="Education">Education</option>
                            <option value="Energy">Energy</option>
                            <option value="Events">Events</option>
                            <option value="Financial Services">Financial Services</option>
                            <option value="Food and Beverage">Food and Beverage</option>
                            <option value="Gaming">Gaming</option>
                            <option value="Government and Military">Government and Military</option>
                            <option value="Hardware">Hardware</option>
                            <option value="Health Care">Health Care</option>
                            <option value="Information Technology">Information Technology</option>
                            <option value="Internet Services">Internet Services</option>
                            <option value="Lending and Investments">Lending and Investments</option>
                            <option value="Manufacturing">Manufacturing</option>
                            <option value="Media and Entertainment">Media and Entertainment</option>
                            <option value="Messaging and Telecommunications">Messaging and Telecommunications</option>
                            <option value="Mobile">Mobile</option>
                            <option value="Music and Audio">Music and Audio</option>
                            <option value="Natural Resources">Natural Resources</option>
                            <option value="Navigation and Mapping">Navigation and Mapping</option>
                            <option value="Payments">Payments</option>
                            <option value="Platforms">Platforms</option>
                            <option value="Privacy and Security">Privacy and Security</option>
                            <option value="Professional Services">Professional Services</option>
                            <option value="Real Estate">Real Estate</option>
                            <option value="Sales and Marketing">Sales and Marketing</option>
                            <option value="Science and Engineering">Science and Engineering</option>
                            <option value="Software">Software</option>
                            <option value="Sports">Sports</option>
                            <option value="Sustainability">Sustainability</option>
                            <option value="Transportation">Transportation</option>
                            <option value="Travel and Tourism">Travel and Tourism</option>
                            <option value="Video">Video</option>
                            <option value="Other">Other</option>
                            <option value="Unknown">Unknown</option>
                        </select>
                    </div>
                    <!-- 
                        /////////////////////
                            SECTOR DROPDOWN
                        /////////////////////
                    -->
                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 " id="ArtificialIntelligenceDrop">
                        <label for="Sector" class="form-label" >Sector </label> <br/>
                        <select id="Sector" name="Sector[]"  class="form-select sectorDropdowns" multiple="true">
                            <option>choose...</option>
                        </select> <br/>
                        <small style="color:red;">First select an industry </small>
                    </div>
                </div>
                <!--    
                        =========================================================================
                        ======================== INVESTOR SECTION ===============================
                        =========================================================================
                -->
                <div class="row">
                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12"> 
                        <h5>
                            Investment Manager(s)
                        </h5>
                        <label for="InvestorName" class="form-label"> Name</label>        <br/>
                        <select class="form-select InvestorName" id="InvestorName" name="InvestorName[]" multiple="true" >
                            <option> Select...</option>
                            <?php
                                while ($rowA1 = mysqli_fetch_assoc($resultA1)) {
                                    # code...
                                    echo "<option>".$rowA1['InvestorName']."</option>";
                                }
                            ?>
                        </select>
                    </div> 
                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <h5>
                            Fund
                        </h5>
                        <label for="FundName" class="form-label">Fund Name</label><br/>
                        <select  class="form-select FundName" id="FundName" name="FundName[]" multiple="true" >
                            <option value="">Select...</option>
                            <?php
                                while($rowB = mysqli_fetch_assoc($resultB)){
                                    echo "<option>".$rowB['FundName']."</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <!--    
                        =========================================================================
                        ======================== INVESTOR CONTACT SECTION ====================
                        =========================================================================
                -->
                <!-- <div class="row">
                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 "> 
                        <h5>
                            Contact Person
                        </h5> 
                        <label for="UserFullName1" class="form-label">Contact</label>
                        <select class="form-select" id="UserFullName1" name="UserFullName1" >
                            <option> Select...</option>
                            <?php
                                mysqli_data_seek($result5, 0);
                                while ($row5 = mysqli_fetch_assoc($result5)) {
                                    # code...
                                    echo "<option>".$row5['UserFullName']."</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div> -->
                <p>
                    <Button name="Update" type="submit" value="Update" class="btn btn-primary" formmethod="POST">Update</Button>
                    <a href="../tabs/NewDeals.php" class="btn btn-danger" >Close</a>
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
        <!-- <script src="../../js/DateDropDown.js"></script> -->
    </body>
</html>