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
    include('../App/connect.php');
    // QUERY DATABASE FOR DATA WE'LL DISPLAY ON THE SCREEN
    $sql="  SELECT Deals.DealsID
                ,News.NewsID
                ,News.NewsURL
                ,News.NewsDate
                ,PortfolioCompany.PortfolioCompanyName
                ,GROUP_CONCAT(DISTINCT InvestorName) AS InvestorName
                ,GROUP_CONCAT(DISTINCT FundName) AS FundName
                ,FORMAT(Deals.InvestmentValue, 'c', 'en-US') AS 'InvestmentValue'
                ,Deals.stake
                ,GROUP_CONCAT(DISTINCT Industry) AS Industry
                ,GROUP_CONCAT(DISTINCT Sector.Sector) AS Sector
                ,GROUP_CONCAT(DISTINCT InvestmentStage) AS InvestmentStage
                ,Country.Country
                ,Note.Note
            FROM Deals
            -- Joining linking tables
            LEFT JOIN DealsInvestor ON DealsInvestor.DealsID = Deals.DealsID
            LEFT JOIN Investor ON Investor.InvestorID = DealsInvestor.InvestorID
            LEFT JOIN DealsFund ON DealsFund.DealsID = Deals.DealsID
            LEFT JOIN Fund ON Fund.FundID = DealsFund.FundID
            -- Include News table data 
            LEFT JOIN News ON News.NewsID = Deals.NewsID
            LEFT JOIN
                -- Include PortfoliCompany table data
                PortfolioCompany ON PortfolioCompany.PortfolioCompanyID = Deals.PortfolioCompanyID
            LEFT JOIN
                -- Link investment stage to fund
                FundInvestmentStage ON FundInvestmentStage.FundID = Fund.FundID
            LEFT JOIN InvestmentStage ON InvestmentStage.InvestmentStageID = FundInvestmentStage.InvestmentStageID
            LEFT JOIN PortfolioCompanyLocation ON PortfolioCompanyLocation.PortfolioCompanyID = Deals.PortfolioCompanyID
            LEFT JOIN Country ON Country.CountryID = PortfolioCompanyLocation.CountryID
            LEFT JOIN DealsIndustry ON DealsIndustry.DealsID = Deals.DealsID
            LEFT JOIN Industry ON Industry.IndustryID = DealsIndustry.IndustryID
            LEFT JOIN DealsSector ON DealsSector.DealsID = Deals.DealsID
            LEFT JOIN Sector ON Sector.SectorID = DealsSector.SectorID
            LEFT JOIN DealsNote ON DealsNote.DealsID = Deals.DealsID
            LEFT JOIN Note ON Note.NoteID = DealsNote.NoteID
            WHERE Deals.Deleted = 0
            GROUP BY DealsID
                ,NewsID
                ,NewsURL
                ,NewsDate
                ,PortfolioCompanyName
                ,InvestmentValue
                ,stake
                ,Note
            -- ORDER BY News.NewsDate DESC
        "; 
            
    $result = $conn->query($sql); //or die($conn->error);
    $row = mysqli_fetch_assoc($result);  

    // POPULATING PORTFOLIO COMPANIES DROPDOWN
    $sql1 ="SELECT DISTINCT PortfolioCompanyName
            FROM PortfolioCompany 
            WHERE PortfolioCompanyName IS NOT NULL ORDER BY PortfolioCompanyName ASC
    ";
    $result1 = mysqli_query($conn, $sql1);
    
    // POPULATING UserDetail DROPDOWN 
    $sql2 ="SELECT DISTINCT UserFullName
            FROM UserDetail 
            WHERE UserFullName IS NOT NULL 
            ORDER BY UserFullName ASC";
    $result2 = mysqli_query($conn, $sql2);

    // POPULATING IMPACT-TAG DROPDOWN
    $sql3 ="SELECT DISTINCT InvestorName
            FROM Investor 
            WHERE InvestorName IS NOT NULL ORDER BY InvestorName ASC
    ";
    $result3 = mysqli_query($conn, $sql3);
    // POPULATING COUNTRIES DROPDOWN
    $sql4 ="SELECT DISTINCT FundName
            FROM Fund
            WHERE FundName IS NOT NULL
            ORDER BY FundName ASC
    ";
    $result4 = mysqli_query($conn, $sql4);

    // QUERY DATABASE TO DISPLAY DATA INSIDE THE PieChart
    $chartQuery ="  SELECT Sector.Sector,COUNT(*) AS Percentage
                    FROM DealsSector
                    LEFT JOIN Sector ON Sector.SectorID = DealsSector.SectorID
                    GROUP BY Sector.Sector
    ";
    $resultQuery = mysqli_query($conn, $chartQuery);


    // =====================================================================
    // =====================================================================
    // =======================CREATING A NEW DEAL===========================

    if ( isset($_POST['submit']))
    {
        // DECLARED AND SET VARIABLES
        // NEWS TABLE
        $NewsDate       = date('Y-m-d', strtotime($_POST['NewsDate']));
        $NewsURL        = mysqli_real_escape_string($conn, $_POST['NewsURL']);

        if(isset($_POST['NewsNote'])){
            $NewsNote       = mysqli_real_escape_string($conn, $_POST['NewsNote']);
        }
        // PORTFOLIO COMPANY TABLE
        $PortfolioCompanyName    = $_POST['PortfolioCompanyName'];
        $Stake                   = $_POST['Stake'];
        $InvestmentValue         = $_POST['InvestmentValue'];
        $Industries              = $_POST['Industry'];
        $Sector                  = $_POST['Sector'];
        // USER DETAIL TABLE
        $Contacts                 = $_POST['UserFullName'];
        // INVESTOR TABLE
        $InvestorName            = $_POST['InvestorName'];
        // FUND TABLE
        $FundName                = $_POST['FundName'];

        // ===========================================================================================================
        // THE CODE BLOCK BELOW CHECKS IF RECORD ALREADY EXISTS IN THE DB OR NOT. WE'LL USE THIS TO PREVENT DUPLICATES
        // ===========================================================================================================
        $DuplicateCheck = " SELECT NewsURL FROM News WHERE News.NewsURL ='$NewsURL'";
        $checkResult = mysqli_query($conn, $DuplicateCheck);

        if($checkResult -> num_rows >0){
            $conn->close();
            header( "refresh: 3;url= ../AuthViews/NewDeals.php" );
            echo 
                '<div style="background-color:#f8d7da; color: #842029; margin:0;">
                    <H3>Heads Up!</H3>
                    <p style="margin:0;"> <small>New record not created, Deal already exists.</small> </p>
                </div>'
            ;
        }else{
            
            
            // ===========================================================
            // REFRESH PAGE TO SHOW NEW ENTRIES IF INSERTION WAS A SUCCESS
            // ===========================================================
            // ===========================================================
            header("Refresh:8; url=../AuthViews/NewDeals.php");
            echo '<h4>Thank you for your contibution</h4>';

            // ===================================================================================
            // BELOW ARE THE INSERT STATEMENTS TO THE NEWS AND NOTE TABLE. 
            // THESE ARE THE ONLY  TWO TABLES THAT WILL COLLECT NEW DATA UPON ENTERING A NEW DEAL.
            // ===================================================================================
            $sql = "    INSERT INTO 
                            News(NewsID, CreatedDate, ModifiedDate,Deleted, DeletedDate, NewsDate, NewsURL) 
                        VALUES 
                            (uuid(), now(), now(),0,NULL,'$NewsDate', '$NewsURL')";
            $query = mysqli_query($conn, $sql);
            
            if ($query ){
                    // Success
            } else {
                echo 'Oops! There was an error saving News item. Please report bug to support.'.'<br/>'.mysqli_error($conn); 
            }
            // Query End

            // CONDITIONAL STATEMENT TO INSERT DATA INTO THE NOTE TABLE
            // IF THE NOTE VARIABLE IS SET THEN INSERT INTO NOTE OTHERWISE DO NOT INSERT.
            if(!empty($NewsNote)){
                $sql2 = "   INSERT INTO 
                                Note(NoteID, CreatedDate, ModifiedDate,Deleted, DeletedDate, Note, NoteTypeID )
                            VALUES 
                                (uuid(), now(), now(), 0,NULL, '$NewsNote','fb44ee75-7056-11eb-a66b-96000010b114')";
                $query2 = mysqli_query($conn, $sql2);
            }else {
                // echo 'Oops! There was an error saving Deal Note item. Please report bug to support.'.'<br/>'.mysqli_error($conn);
            }
    
            // =====================================================
            // =====================================================
            // ***** INSERT STATEMENTS FOR THE MAPPING TABLES ******
            // =====================================================
            // =====================================================
    
            // PORTFOLIOCOMPANY MAPPING TABLES
            $sqlA1 = "  INSERT INTO PortfolioCompanyNews(PortfolioCompanyNewsID, CreatedDate, ModifiedDate,Deleted, DeletedDate, PortfolioCompanyID, NewsID)
                        VALUES (uuid(), now(), now(),0,NULL, (select PortfolioCompany.PortfolioCompanyID FROM PortfolioCompany where PortfolioCompany.PortfolioCompanyName = '$PortfolioCompanyName'), (select distinct News.NewsID FROM News where News.NewsURL = '$NewsURL'))";
            $queryA1 = mysqli_query($conn, $sqlA1);
            if ($queryA1 ){
                // Success
            } else {
                echo 'Oops! There was an error on linking Company and News. Please report bug to support.'.'<br/>'.'<br/>'.mysqli_error($conn);
            }
    
            // FUND MAPPING TABLES
            foreach($FundName as $Fund){ 
                $sqlA3 = "  INSERT INTO 
                                FundNews(FundNewsID, CreatedDate, ModifiedDate,Deleted, DeletedDate, FundID, NewsID)
                            VALUES 
                                (uuid(), now(), now(),0,NULL, (select distinct Fund.FundID FROM Fund where Fund.FundName = '$Fund'), (select distinct News.NewsID FROM News where News.NewsURL = '$NewsURL'))";
                $queryA3 = mysqli_query($conn, $sqlA3);
                
                if($queryA3){
                    // Do nothing
                } else {
                    echo 'Oops! There was an error saving links between Funds and Newss from the array'.mysqli_error($conn).'<br/>';
                }
            }
            
            // =====================================================================
            // **** INSERT STATEMENTS FOR THE DEALS CENTRAL CAPTURING TABLES **** //
            // =====================================================================
    
            $sqlDLS = "  INSERT INTO 
                            Deals(DealsID, CreatedDate, ModifiedDate,Deleted, DeletedDate, NewsID, PortfolioCompanyID, InvestmentValue, stake)
                        VALUES 
                            (uuid(), now(), now(),0,NULL, (select distinct News.NewsID FROM News where News.NewsURL = '$NewsURL'), (select distinct PortfolioCompany.PortfolioCompanyID FROM PortfolioCompany where PortfolioCompany.PortfolioCompanyName = '$PortfolioCompanyName'), '$InvestmentValue', '$Stake')
            ";
            $queryDLS = mysqli_query($conn, $sqlDLS);
            // DLS
            if ($queryDLS){
                // IF THE QUERY ABOVE IS A SUCCESS THEN EXECUTE BELOW CODE
                // =========================================================
                // LOOP TO INSERT INDUSTRIES TO THE LINKING TABLE ON DEALS
                // =========================================================
                foreach($Industries as $Industry){  
                    $sqlDealIndustry = "  INSERT INTO 
                                            DealsIndustry(DealsIndustryID, CreatedDate, ModifiedDate, Deleted, DeletedDate, DealsID, IndustryID)
                                        VALUES 
                                            (uuid(), now(), now(), 0, NULL,(SELECT Deals.DealsID FROM Deals WHERE Deals.NewsID = (SELECT News.NewsID FROM News WHERE News.NewsURL = '$NewsURL')), (SELECT Industry.IndustryID FROM Industry WHERE Industry.Industry = '$Industry'))
                    ";
                    $queryIndustry = mysqli_query($conn, $sqlDealIndustry);
                    if($queryIndustry){
                        // Do nothing
                    } else {
                        echo 'Oops! There was an error inserting the Industry IDs from the array'.mysqli_error($conn).'<br/>';
                    }
                }
                // ======================================================
                // LOOP TO INSERT SectorS TO THE LINKING TABLE ON DEALS
                // ======================================================
                foreach($Sector as $sects){  
                    $sqlDealSector = "  INSERT INTO 
                                            DealsSector(DealsSectorID, CreatedDate, ModifiedDate, Deleted, DeletedDate, DealsID, SectorID)
                                        VALUES 
                                            (uuid(), now(), now(), 0, NULL,(SELECT Deals.DealsID FROM Deals WHERE Deals.NewsID = (SELECT News.NewsID FROM News WHERE News.NewsURL = '$NewsURL')), (SELECT S.SectorID FROM Sector S WHERE S.Sector = '$sects'))
                    ";
                    $query99 = mysqli_query($conn, $sqlDealSector);
                    if($query99){
                        // Do nothing
                    } else {
                        echo 'Oops! There was an error inserting the Sector IDs from the array'.mysqli_error($conn).'<br/>';
                    }
                }

                
                // ======================================================
                // LOOP TO INSERT CONTACT TO THE LINKING TABLE ON DEALS
                // ======================================================
                foreach($Contacts as $Contact){  
                    $sql= "  INSERT INTO 
                                            DealsUserDetail(DealsUserDetailID, CreatedDate, ModifiedDate, DealsID, UserDetailID)
                                        VALUES 
                                            (uuid(), now(), now(), (SELECT Deals.DealsID FROM Deals WHERE Deals.NewsID = (SELECT News.NewsID FROM News WHERE News.NewsURL = '$NewsURL')), (select UserDetail.UserDetailID FROM UserDetail where UserDetail.UserFullName = '$Contact'))
                    ";
                    $query = mysqli_query($conn, $sql);
                    if($query){
                        // Do nothing
                    } else {
                        echo 'Oops! There was an error inserting the contact IDs from the array'.mysqli_error($conn).'<br/>';
                    }
                }

                // =================================================================
                // LOOP TO INSERT INVESTMENT MANAGERS TO THE LINKING TABLE ON DEALS
                // =================================================================
                foreach($InvestorName as $InvestmentManager){  
                    $sqlDealInvestor = "  INSERT INTO 
                                            DealsInvestor(DealsInvestorID, CreatedDate, ModifiedDate, Deleted, DeletedDate, DealsID, InvestorID)
                                        VALUES 
                                            (uuid(), now(), now(), 0, NULL,(SELECT Deals.DealsID FROM Deals WHERE Deals.NewsID = (SELECT News.NewsID FROM News WHERE News.NewsURL = '$NewsURL')), (SELECT Investor.InvestorID FROM Investor WHERE Investor.InvestorName = '$InvestmentManager'))
                    ";
    
                    $query98 = mysqli_query($conn, $sqlDealInvestor);
                    
                    if($query98){
                        // Do nothing
                    } else {
                        echo 'Oops! There was an error saving links between Investment Manager and Deals from the array'.mysqli_error($conn).'<br/>';
                    }
                }
                // =================================================================
                // LOOP TO INSERT FUNDS TO THE LINKING TABLE ON DEALS
                // =================================================================
                foreach($FundName as $Fund){  
                    $sqlDealFund = "  INSERT INTO 
                                            DealsFund(DealsFundID, CreatedDate, ModifiedDate, Deleted, DeletedDate, DealsID, FundID)
                                        VALUES 
                                            (uuid(), now(), now(), 0, NULL,(SELECT Deals.DealsID FROM Deals WHERE Deals.NewsID = (SELECT News.NewsID FROM News WHERE News.NewsURL = '$NewsURL')), (SELECT Fund.FundID FROM Fund WHERE Fund.FundName = '$Fund'))
                    ";
    
                    $query97 = mysqli_query($conn, $sqlDealFund);
                    
                    if($query97){
                        // Do nothing
                    } else {
                        echo 'Oops! There was an error saving links between Funds and Deals from the array'.mysqli_error($conn).'<br/>';
                    }
                }
                        
                // ===============================
                // ===============================
                // Deal Note Mapping tables
                // ===============================
                // ===============================
                if(!empty($NewsNote)){
                    $sqlDealsNote = "   INSERT INTO 
                                            DealsNote(DealsNoteID, CreatedDate, ModifiedDate,Deleted, DeletedDate, DealsID, NoteID)
                                        VALUES 
                                            (uuid(), now(), now(),0,NULL, (SELECT Deals.DealsID FROM Deals WHERE Deals.NewsID = (SELECT News.NewsID FROM News WHERE News.NewsURL = '$NewsURL')), (select Note.NoteID FROM Note where Note.Note = '$NewsNote'))
                    ";
                    $queryDealsNote = mysqli_query($conn, $sqlDealsNote);
                    if ($queryDealsNote ){
                        // Success
                    } else {
                        echo 'Oops! There was an error on linking Deal and Note. Please report bug to support.'.'<br/>'.'<br/>'.mysqli_error($conn);
                    }
                }else {
                    // echo 'Oops! There was an error linking the Deal and Note because Notes field is empty. Please report bug to support.'.'<br/>'.mysqli_error($conn);
                }    
            } else {
                echo 'Oops! There was an error on Deals Capture Table. Please report bug to support.'.'<br/>'.mysqli_error($conn);
            }
         
        }
    };
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="../../resources/DCA_Icon.png" type="image/x-icon">
        <title>VC Reportstream | deals </title>
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
            };
        </style>
    </head>
    <body class="pb-5">
        <!-- HEADER CONTENT -->        <header class="mb-5">
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
                <div class="my-2">
                    <div class="row ">
                        
                        <!-- CREATE NEW Deal MODAL -->
                        <span class="col-4">
                            <!-- Button trigger modal -->
                            <small>
                                <button type="button" class="btn btn_new " data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    <small>Add New</small> <img src="../../resources/icons/New.svg" alt="">
                                </button>
                            </small>
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Create New Deal</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form class="container" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                                                <!--    
                                                        =========================================================================
                                                        ======================== NEWS SECTION ===================================
                                                        =========================================================================
                                                -->
                                                <div class="row"> 
                                                    <h5>
                                                        News 
                                                    </h5>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 "> 
                                                        <label for="NewsDate" class="form-label">News Date</label>
                                                        <input type="date" value="" class="form-control" id="NewsDate" name="NewsDate" required>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="NewsURL" class="form-label">News Link</label>
                                                        <input type="text" class="form-control" id="NewsURL" name="NewsURL" required>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="NewsNote" class="form-label">News Note</label>
                                                        <textarea class="form-control" aria-label="With textarea" id="NewsNote" name="NewsNote" ></textarea>
                                                    </div>  
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
                                                        <select class="form-select" id="PortfolioCompanyName" name="PortfolioCompanyName" required>
                                                            <option>Select Company...</option>
                                                            <?php
                                                                while ($row = mysqli_fetch_assoc($result1)) {
                                                                    # code...
                                                                    echo "<option>".$row['PortfolioCompanyName']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                        <button onclick="openWin3()" target="_blank" class="btn btn-outline-success btn-sm">
                                                            Add new Company
                                                        </button>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="Stake" class="form-label">Stake</label>
                                                        <input type="number" class="form-control" id="Stake" name="Stake"  min="0" max="1" step="any">
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="InvestmentValue" class="form-label">Total Investment Value</label>
                                                        <input type="number" class="form-control" id="InvestmentValue" name="InvestmentValue" min="0" max="1000000000000" step="any">
                                                    </div>
                                                    <!-- 
                                                        /////////////////////
                                                            INDUSTRY DROPDOWN
                                                        /////////////////////
                                                    -->
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="Industry" class="form-label">Industry</label>
                                                        <select id="Industry" name="Industry[]" class="form-select">
                                                            <option>choose...</option>
                                                            <option value="Artificial Intelligence">Artificial Intelligence</option>
                                                            <option value="Clothing and Apparel">Clothing Apparel</option>
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
                                                        <label for="Sector" class="form-label" >Sector </label>
                                                        <select id="Sector" name="Sector[]"  class="form-select sectorDropdowns" multiple="true" required>
                                                            <option>choose...</option>
                                                        </select>
                                                        <small style="color:red;">First select an industry </small>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 "> 
                                                        <label for="UserFullName" class="form-label">Contact Person</label>
                                                        <select class="form-select UserDetail" id="UserFullName" name="UserFullName[]" required multiple="true">
                                                            <option> Select Contact Person...</option>
                                                            <?php
                                                                while ($row2 = mysqli_fetch_assoc($result2)) {
                                                                    # code...
                                                                    echo "<option>".$row2['UserFullName']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                        <div class="my-1">
                                                            <button onclick="openWin()" target="_blank" class="btn btn-outline-success btn-sm">
                                                                Add new Contact
                                                            </button>
                                                        </div>
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
                                                        <label for="InvestorName" class="form-label"> Name</label>        
                                                        <select class="form-select InvestorName" id="InvestorName" name="InvestorName[]" multiple="true" required>
                                                            <option> Select...</option>
                                                            <?php
                                                                while ($row3 = mysqli_fetch_assoc($result3)) {
                                                                    # code...
                                                                    echo "<option>".$row3['InvestorName']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                        <div class="my-1">
                                                            <button onclick="openWin1()" target="_blank" class="btn btn-outline-success btn-sm">
                                                                Add new Manager
                                                            </button>
                                                        </div>
                                                    </div> 
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                                        <h5>
                                                            Fund
                                                        </h5>
                                                        <label for="FundName" class="form-label">Fund Name</label>
                                                        <select  class="form-select FundName" id="FundName" name="FundName[]" multiple="true" required>
                                                            <option value="">Select...</option>
                                                            <?php
                                                                while($row4 = mysqli_fetch_assoc($result4)){
                                                                    echo "<option>".$row4['FundName']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                        <div class="my-1">
                                                            <button onclick="openWin2()" target="_blank" class="btn btn-outline-success btn-sm">
                                                                Add new Fund
                                                            </button>
                                                        </div>
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
                        <!-- IMPORT CSV FILE -->
                        <span class="col-4"> 
                            <a href="javascript:void(0);" class="btn btn_import" onclick="formToggle('ImportFrm');">Import</a>
                            <div id="ImportFrm" class="my-1" style="display:none;">
                                <form action="../Import/DealsImport.php" method="POST" enctype="multipart/form-data">
                                    <input type="file" name="file"> <br>
                                    <input type="submit" class="btn btn-outline-success" name="ImportSubmit" value="IMPORT">
                                </form>
                            </div>
                        </span>
                        <!-- EXPORT CSV FILE -->
                        <span class="col-3"> 
                            <form action="../ExportCSV/DealExport.php" method="POST">
                                <button class="btn btn_export" type="submit" name="export" formmethod="POST"> <small>Export</small></button>
                            </form>
                        </span>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body " style="background-color:#5d8f18;">
                        <div class="table-responsive" style="overflow-x:auto;">
                            <table class=" table table-hover table-striped table-success table-bordered" style="line-height: 18px;" id="table_Deals">
                                <thead>
                                    <th scope="col">Date </th>
                                    <th scope="col">News Link</th>
                                    <th scope="col">Portfolio Company</th>
                                    <th scope="col">Investment Manager(s)</th>
                                    <!-- <th scope="col">Fund(s)</th> -->
                                    <th scope="col">Value Of Investment</th>
                                    <!-- <th scope="col">Stake</th>
                                    <th scope="col">Industry </th>
                                    <th scope="col">Sector(s)</th>
                                    <th scope="col">Portfolio Company Headquarters</th>
                                    <th scope="col">Company Contact(s)</th>
                                    <th scope="col">Role </th>
                                    <th scope="col">Deal Notes </th> -->
                                    <th scope="col">View More </th>
                                    <th scope="col">Edit  </th>
                                    <th scope="col">Delete </th>
                                </thead>
                                <tbody>
                                    <?php
                                        while(($row = mysqli_fetch_assoc($result)))
                                        {
                                    ?>
                                    <tr data-href="../crud/edit_deals.php?DealsID=<?php echo $row['DealsID'];?>">
                                        <td class="text-truncate"> <small ><?php echo $row["NewsDate"];?> </small> </td>
                                        <td class="text-truncate"> <a href="<?php echo $row["NewsURL"];?>" target="_blank"><small > <?php echo $row["NewsURL"];?></small></a></td>
                                        <td class="text-truncate"> <small ><?php echo $row["PortfolioCompanyName"];?> </small> </td>
                                        <td class="text-truncate"> <small ><?php echo $row["InvestorName"];?> </small> </td>
                                        <!-- <td class="text-truncate"> <small ><?php echo $row["FundName"];?> </small> </td> -->
                                        <td class="text-truncate"> <small ><?php echo '$'.$row["InvestmentValue"];?> </small> </td>
                                        <!-- <td class="text-truncate"> <small ><?php echo $row["stake"];?> </small> </td>
                                        <td class="text-truncate"> <small ><?php echo $row["Industry"];?> </small> </td>
                                        <td class="text-truncate"> <small ><?php echo $row["Sector"];?> </small> </td>
                                        <td class="text-truncate"> <small ><?php echo $row["Country"];?> </small></td>
                                        <td class="text-truncate"> <small ><?php echo $row["UserFullName"];?> </small></td>
                                        <td class="text-truncate"> <small ><?php echo $row["RoleType"];?> </small></td>
                                        <td class="text-truncate"> <small ><?php echo $row["Note"];?> </small></td> -->
                                        <td> <a href="./SingleView/ViewDeal.php?DealsID=<?php echo $row['DealsID'];?>">View Deal</a></td>
                                        <td class="text-truncate"> <a href="../crud/edit_deals.php?DealsID=<?php echo $row['DealsID']; ?>">Edit</a></td>
                                        <td class="text-truncate"> <a href="../crud/delete_deals.php?DealsID=<?php echo $row['DealsID']; ?>">Delete</a></td>
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

            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>  
            <script type="text/javascript">
                // Loading the Visualization API and the corechart package and controls
                google.charts.load('current', {'packages':['corechart', 'controls']});

                // Set a callback to run when the Google Visualization API is loaded.
                google.charts.setOnLoadCallback(drawChart);
                
                google.charts.setOnLoadCallback(drawChart2);

                // Callback that creates and populates a data table,
                // instantiates the pie chart, passes in the data and draws it.
                function drawChart() {

                    // Create the data table.
                    var data =  google.visualization.arrayToDataTable([
                        ['Sector', 'Percentage'],
                        <?php
                            while($chartRow = mysqli_fetch_assoc($resultQuery)){
                                echo"['".$chartRow['Sector']."', ".$chartRow['Percentage']."],";
                            }
                        ?>
                    ]);

                    var options= {
                        title: 'Percentage of Sectors'
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('pieChart'));
                    chart.draw(data, options);
                };

                // second chart drawing
                function drawChart2() {

                // Create the data table.
                var data =  google.visualization.arrayToDataTable([
                    ['Sector', 'Percentage'],
                    <?php
                        mysqli_data_seek($resultQuery, 0);
                        while($chartRow = mysqli_fetch_assoc($resultQuery)){
                            echo"['".$chartRow['Sector']."', ".$chartRow['Percentage']."],";
                        }
                    ?>
                ]);

                var options= {
                    title: 'Percentage of Sectors'
                };

                var chart = new google.visualization.BarChart(document.getElementById('BarChart'));
                chart.draw(data, options);
                };
            </script>
            <!-- 
                DASHBOARD SECTION USING GOOGLEW CHARTS API 
             -->
            <div class="card">
                <div class="card-header text-center">
                    <h4 class="card-title"> Visualization Dashboard | Sector breakdowns.</h4>
                </div>
                <div class="card-body text-center">
                    <div class="row">
                        <div id="pieChart"  style=" height:270px;" class="col-6 col">
                        </div>
                    </div>  
                </div>
            </div>
        </main>
        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.6.0.slim.js" integrity="sha256-HwWONEZrpuoh951cQD1ov2HUK5zA5DwJ1DNUXaM6FsY=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
        <script src="../../js/select2.min.js"></script>
        <script src="../../js/scripts.js"></script>
        <script src="../../js/MultiSelect.js"></script>
        <script src="../../DataTables/datatables.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
        <script>
            var createWindow;
            // open window
            function openWin() {
                createWindow = window.open("./SubFunctions/create_contact.php", "_blank", "width=920, height=500");
            }

            var createWindow1;
            // open window
            function openWin1() {
                createWindow1 = window.open("./SubFunctions/create_investor.php", "_blank", "width=920, height=500");
            }

            var createWindow2;
            // open window
            function openWin2() {
                createWindow2 = window.open("./SubFunctions/create_fund.php", "_blank", "width=920, height=500");
            }

            
            var createWindow3;
            // open window
            function openWin3() {
                createWindow3 = window.open("./SubFunctions/create_portfoliocompany.php", "_blank", "width=920, height=500");
            }
        </script>
        <script>
            $(document).ready( function () {    
                // Initializing the datatable plugin
                $('#table_Deals').DataTable( 
                    {
                        "order": [[ 0, "desc" ]]
                    } 
                );    

                // Trigger the double tap to edit function
                $(document.body).on("dblclick", "tr[data-href]", function (){
                    window.location.href = this.dataset.href;
                });
            });
        </script>                
        <script>
            function formToggle(ID){
                 var ImportFormReview = document.getElementById(ID);
                 if(ImportFormReview.style.display === "none"){
                    ImportFormReview.style.display ="block";
                 }else{
                    ImportFormReview.style.display ="none";
                 }
            };
        </script>      
    </body>
</html>


                 

