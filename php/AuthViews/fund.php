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
    $sql=" SELECT 
	            Fund.FundID, Fund.Deleted, Fund.DeletedDate, Fund.FundName, GROUP_CONCAT(DISTINCT InvestorName) AS InvestorName, GROUP_CONCAT(DISTINCT PortfolioCompanyName) AS PortfolioCompanyName , Currency.Currency,Currency.CurrencyCode, FORMAT(Fund.CommittedCapital, 'c', 'en-US') AS 'CommittedCapital' , FORMAT(Fund.MinimumInvestment, 'c', 'en-US') AS 'MinimumInvestment', FORMAT(Fund.MaximumInvestment, 'c', 'en-US') AS 'MaximumInvestment', GROUP_CONCAT(DISTINCT InvestmentStage) AS InvestmentStage, Note.Note
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
                Fund.Deleted = 0

            GROUP BY FundID, Deleted, DeletedDate, FundName, Currency, CurrencyCode, CommittedCapital, MinimumInvestment, MaximumInvestment,  Note
        ";
        
    $result = $conn->query($sql) or die($conn->error);
    
    // CURRENCIES
    $sql100 = "   SELECT DISTINCT 
                    Currency
                FROM 
                    Currency 
                WHERE 
                    Currency IS NOT NULL ORDER BY Currency ASC";
    $result100 = mysqli_query($conn, $sql100);

    // COUNTRIES
    $sql101 = "   SELECT DISTINCT 
                    Country
                FROM 
                    Country 
                WHERE 
                    Country IS NOT NULL ORDER BY Country ASC";
    $result101 = mysqli_query($conn, $sql101);
    
    // INVESTORS
    $sql102 = "   SELECT DISTINCT 
                    InvestorName
                FROM 
                    Investor 
                WHERE 
                    InvestorName IS NOT NULL ORDER BY InvestorName ASC";
    $result102 = mysqli_query($conn, $sql102);
    
    // PORTFOLIO COMPANIES
    $sql103 = "   SELECT DISTINCT 
                    PortfolioCompanyName
                FROM 
                    PortfolioCompany 
                WHERE 
                    PortfolioCompanyName IS NOT NULL ORDER BY PortfolioCompanyName ASC";
    $result103 = mysqli_query($conn, $sql103);    

    // INVESTMENT STAGE
    $sql104 = " SELECT DISTINCT 
                    InvestmentStage
                FROM 
                    InvestmentStage 
                WHERE 
                    InvestmentStage IS NOT NULL ORDER BY InvestmentStage ASC";
    $result104 = mysqli_query($conn, $sql104);

    // INDUSTRY
    $sql105 = "   SELECT DISTINCT 
                    Industry
                FROM 
                    Industry 
                WHERE 
                    Industry IS NOT NULL ORDER BY Industry ASC";
    $result105 = mysqli_query($conn, $sql105);

    if ( isset($_POST['submit']))
    {
        // FUND TABLE
        $FundName               = mysqli_real_escape_string($conn,$_POST['FundName']);
        $InvestorName           = $_POST['InvestorName'];
        $PortfolioCompanyName   = $_POST['PortfolioCompanyName'];
        $Currency               = $_POST['Currency'];
        $CommittedCapital       =  mysqli_real_escape_string($conn,$_POST['CommittedCapital']);
        $MinimumInvestment      =  mysqli_real_escape_string($conn,$_POST['MinimumInvestment']);
        $MaximumInvestment      =  mysqli_real_escape_string($conn,$_POST['MaximumInvestment']);
        $InvestmentStage        = $_POST['InvestmentStage'];
        $FundNote               = mysqli_real_escape_string($conn,$_POST['FundNote']);

        
        // ===========================================================================================================
        // THE CODE BLOCK BELOW CHECKS IF RECORD ALREADY EXISTS IN THE DB OR NOT. WE'LL USE THIS TO PREVENT DUPLICATES
        // ===========================================================================================================
        $DuplicateCheck = " SELECT FundName FROM Fund WHERE Fund.FundName ='$FundName'";
        $checkResult = mysqli_query($conn, $DuplicateCheck);

        if($checkResult -> num_rows >0){
            $conn->close();
            header( "refresh: 3;url= fund.php" );
            echo 
                '<div style="background-color:#f8d7da; color: #842029; margin:0;">
                    <H3>Heads Up!</H3>
                    <p style="margin:0;"> <small>New record not created, Fund already exists.</small> </p>
                </div>'
            ;
        }else{
            // FUND INSERTION QUERY
            $sql =" INSERT INTO 
                        Fund(FundID, CreatedDate, ModifiedDate, Deleted, DeletedDate, FundName, CurrencyID, CommittedCapital, MinimumInvestment, MaximumInvestment) 
                    VALUES 
                        (uuid(), now(), now(),0,NULL, '$FundName',(SELECT Currency.CurrencyID FROM Currency WHERE Currency.Currency = '$Currency' ), '$CommittedCapital', '$MinimumInvestment', '$MaximumInvestment')
            ";
            $query = mysqli_query($conn, $sql);
            if($query){
                // Do Nothing
            } else {
                echo 'Oops! There was an error creating Fund. Please report bug to support.'.'<br/>'.mysqli_error($conn);
            };
            // NOTE INSERTION QUERY
            $sql2 ="INSERT INTO 
                        Note(NoteID, CreatedDate, ModifiedDate, Note, NoteTypeID) 
                    VALUES 
                        (uuid(), now(), now(), '$FundNote','fb450e57-7056-11eb-a66b-96000010b114' )
            ";
            $query2 = mysqli_query($conn, $sql2);

            if($query2 ){
                // Do Nothing
            } else {
                echo 'Oops! There was an error creating Note. Please report bug to support.'.'<br/>'.mysqli_error($conn);
            };
            // ===========================================================
            // LOOP TO INSERT INVESTORS ON FUND
            // ===========================================================
            foreach($InvestorName as $InvestmentManager) {
            $sql4 = "   INSERT INTO 
                        FundInvestor(FundInvestorID, CreatedDate, ModifiedDate, Deleted, DeletedDate, FundID, InvestorID)
                    VALUES 
                        (uuid(), now(), now(), 0, NULL, (SELECT Fund.FundID FROM Fund WHERE Fund.FundName = '$FundName'),(SELECT Investor.InvestorID FROM Investor WHERE Investor.InvestorName = '$InvestmentManager'))
            ";
            $query4 = mysqli_query($conn, $sql4);
            if($query4){
            // Do nothing if success
            } else {
                echo 'Oops! There was an error linking Fund to Investment Manager. Please report bug to support.'.'<br/>'.mysqli_error($conn);
            }
            }
            // ===========================================================
            // LOOP TO INSERT PORTFOLIO COMPANY ON FUND
            // ===========================================================
            foreach($PortfolioCompanyName as $Company) {
            $sql5 = "   INSERT INTO 
                        FundPortfolioCompany(FundPortfolioCompanyID, CreatedDate, ModifiedDate, Deleted, DeletedDate, FundID, PortfolioCompanyID)
                    VALUES 
                        (uuid(), now(), now(), 0, NULL, (SELECT Fund.FundID FROM Fund WHERE Fund.FundName = '$FundName'),(SELECT PortfolioCompany.PortfolioCompanyID FROM PortfolioCompany WHERE PortfolioCompany.PortfolioCompanyName = '$Company'))
            ";
            $query5 = mysqli_query($conn, $sql5);
            if($query5){
                // Do nothing if success
            } else {
            echo 'Oops! There was an error on linking Fund and Companies. Please report bug to support.'.'<br/>'.mysqli_error($conn);
            }
            }
            // ===========================================================
            // LOOP TO INSERT INVESTMENT STAGE ON FUND
            // ===========================================================
            foreach($InvestmentStage as $Stage) {
            $sql6 = "   INSERT INTO 
                        FundInvestmentStage(FundInvestmentStageID, CreatedDate, ModifiedDate, Deleted, DeletedDate, FundID, InvestmentStageID)
                    VALUES 
                        (uuid(), now(), now(), 0, NULL, (SELECT Fund.FundID FROM Fund WHERE Fund.FundName = '$FundName'),(SELECT InvestmentStage.InvestmentStageID FROM InvestmentStage WHERE InvestmentStage.InvestmentStage = '$Stage'))
            ";
            $query6 = mysqli_query($conn, $sql6);
            if($query6){
                // Do nothing if success
            } else {
                echo 'Oops! There was an error on linking Fund and Investment Stage. Please report bug to support.'.'<br/>'.mysqli_error($conn);
            }
            }

            // LINK FUND TO NOTE
            $sql7 = "   INSERT INTO 
                    FundNote(FundNoteID, CreatedDate, ModifiedDate, Deleted, DeletedDate, FundID, NoteID)
                VALUES 
                    (uuid(), now(), now(), 0, NULL, (SELECT Fund.FundID FROM Fund WHERE Fund.FundName = '$FundName'),(SELECT Note.NoteID FROM Note WHERE Note.Note = '$FundNote'))
            ";
            $query7 = mysqli_query($conn, $sql7);
            if($query7){
                // Do nothing if success
            } else {
            echo 'Oops! There was an error on linking Fund and Note. Please report bug to support.'.'<br/>'.mysqli_error($conn);
            }

            $conn->close();
            // ===========================================================
            // REFRESH PAGE TO SHOW NEW ENTRIES IF INSERTION WAS A SUCCESS
            // ===========================================================
            header( "refresh: 3; url= fund.php" );
            echo 
                '<div style="background-color:#d1e7dd; color: #0f5132; margin:0;">
                    <H4>Thank you for your contibution</H4>
                    <p style="margin:0;"> <small> New Fund created successfully! </small> </p>
                </div>'
            ;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="../../resources/DCA_Icon.png" type="image/x-icon">
        <title>VC Reportstream | Fund </title>
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
            <!-- ==== LIST OF PORTFOLIO COMPANIES ==== -->
            <div class=" my-5">
                <div class="my-2">
                    <div class="row">
                        <!-- CREATE NEW FUND MODAL -->
                        <span class="col-4">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn_new " data-bs-toggle="modal" data-bs-target="#exampleModal">
                                Add New <img src="../../resources/icons/New.svg" alt="">
                            </button>
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Create New Fund</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form class="container" method="POST" enctype="multipart/form-data">
                                                <div class="row">
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                                        <label for="FundName" class="form-label">Fund Name</label>
                                                        <input type="FundName" class="form-control" id="FundName" aria-describedby="FundName" name="FundName" required>
                                                    </div>
                                                    <!-- investors dropdown - populated from the database -->
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                                        <label for="InvestorName" class="form-label">Investment Manager(s)</label>
                                                        <select class="form-select InvestorName" id="InvestorName" name="InvestorName[]" multiple="true" required>
                                                            <option> Select...</option>
                                                            <?php
                                                                while ($row102 = mysqli_fetch_assoc($result102)) {
                                                                    # code...
                                                                    echo "<option>".$row102['InvestorName']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                        <button onclick="openWin1()" target="_blank" class="btn btn-outline-success btn-sm">
                                                            Add new Investment Manager
                                                        </button>
                                                    </div>
                                                    <!-- Portfolio Company dropdown - populated from the database -->
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                                        <label for="PortfolioCompanyName" class="form-label">Portfolio Company </label>
                                                        <select class="form-select PortfolioCompanyName" id="PortfolioCompanyName" name="PortfolioCompanyName[]" multiple="true" required>
                                                            <option> Select...</option>
                                                            <?php
                                                                while ($row103 = mysqli_fetch_assoc($result103)) {
                                                                    # code...
                                                                    echo "<option>".$row103['PortfolioCompanyName']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                        <button onclick="openWin2()" target="_blank" class="btn btn-outline-success btn-sm">
                                                            Add new Portfolio Company
                                                        </button>
                                                    </div>
                                                    <!-- Actual Currencies as in the DB --> 
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="Currency" class="form-label">Currency</label>
                                                        <select class="form-select" id="Currency" name="Currency" required>
                                                            <option> Select...</option>
                                                            <?php
                                                                while ($row100 = mysqli_fetch_assoc($result100)) {
                                                                    # code...
                                                                    echo "<option>".$row100['Currency']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div> 
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                                        <label for="CommittedCapital" class="form-label"> Committed Capital</label>
                                                        <input type="number" class="form-control" id="CommittedCapital" name="CommittedCapital" required>
                                                    </div>  
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                                        <label for="MinimumInvestment" class="form-label"> Minimum Investment</label>
                                                        <input type="number" class="form-control" id="MinimumInvestment" name="MinimumInvestment" required>
                                                    </div> 
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                                        <label for="MaximumInvestment" class="form-label"> Maximum Investment</label>
                                                        <input type="number" class="form-control" id="MaximumInvestment" name="MaximumInvestment" required>
                                                    </div>
                                                    <!-- Investment Stage -->
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="InvestmentStage" class="form-label">Investment Stage </label>
                                                        <select class="form-select InvestmentStage" id="InvestmentStage" name="InvestmentStage[]" multiple="true" required>
                                                            <option> Select Investment Stage...</option>
                                                            <?php
                                                                while ($row104 = mysqli_fetch_assoc($result104)) {
                                                                    # code...
                                                                    echo "<option>".$row104['InvestmentStage']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="FundNote" class="form-label">Fund Note</label>
                                                        <textarea class="form-control FundNote" aria-label="With textarea" id=" FundNote" name="FundNote"></textarea>
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
                            <div id="ImportFrm" class="mt-1" style="display:none;">
                                <form action="../Import/ImportFund.php" method="POST" enctype="multipart/form-data">
                                    <input type="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" name="file"> <br>
                                    <input type="submit" class="btn btn-outline-primary" name="ImportSubmit" value="IMPORT" >
                                </form>
                            </div>
                        </span>
                        <!-- EXPORT CSV FILE -->
                        <span class="col-3"> 
                            <form action="../ExportCSV/FundExport.php" method="POST">
                                <button class="btn btn_export" type="submit" name="export" formmethod="POST"> Export</button>
                            </form>
                        </span>
                    </div>
                </div>
                <!-- TABLE OF ALL PORTFOLIO COMPANIES --> 
                <div class="card">
                    <div class="card-body" >
                        <div class="table-responsive" style="overflow-x:auto;">
                            <table class=" table table-hover table-striped table-success table-bordered" style="line-height: 18px;" id="table_Fund">
                                <thead>
                                    <th scope="col">Fund Name</th>
                                    <!-- <th scope="col">Investment Manager(s)</th> -->
                                    <!-- <th scope="col">Portfolio Companies List</th> -->
                                    <th scope="col">Currency</th>
                                    <th scope="col">Committed Capital </th>
                                    <!-- <th scope="col">Minimum Investment</th> -->
                                    <!-- <th scope="col">Maximum Investment</th> -->
                                    <th scope="col">Investment Stage</th>
                                    <!-- <th scope="col">Fund Note</th> -->
                                    <th scope="col">View More </th>
                                    <th scope="col">Edit </th>
                                    <th scope="col">Delete </th>
                                </thead>
                                <tbody>
                                    <?php
                                        while($rows = mysqli_fetch_assoc($result))
                                        {
                                    ?>
                                    <tr data-href="../crud/edit_fund.php?FundID=<?php echo $rows['FundID']; ?>">
                                        <td class="text-truncate"> <small><?php echo $rows['FundName'] ?> </small></td>
                                        <!-- <td class="text-truncate"> <small><?php echo $rows['InvestorName'] ?> </small></td> -->
                                        <!-- <td class="text-truncate"> <small><?php echo $rows['PortfolioCompanyName'] ?> </small></td> -->
                                        <td class="text-truncate"> <small><?php echo $rows['Currency'] ?> </small></td>
                                        <td class="text-truncate"> <small><?php echo $rows['CurrencyCode'] ?> <?php echo $rows['CommittedCapital'] ?> </small></td>
                                        <!-- <td class="text-truncate"> <small><?php echo $rows['CurrencyCode'] ?> <?php echo $rows['MinimumInvestment'] ?> </small></td> -->
                                        <!-- <td class="text-truncate"> <small><?php echo $rows['CurrencyCode'] ?> <?php echo $rows['MaximumInvestment'] ?> </small></td> -->
                                        <td class="text-truncate"> <small><?php echo $rows['InvestmentStage'] ?> </small></td>
                                        <!-- <td class="text-truncate"> <small><?php echo $rows['Note'] ?> </small></td> -->
                                        <td> <a href="./SingleView/ViewFund.php?FundID=<?php echo $rows['FundID'];?>">View More</a></td>
                                        <td class="text-truncate"> <small><a href="../crud/edit_fund.php?FundID=<?php echo $rows['FundID']; ?> ">Edit</a> </small></td>
                                        <td class="text-truncate"> <small><a href="../crud/delete_fund.php?FundID=<?php echo $rows['FundID']; ?> ">Delete</a> </small></td>
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

        <footer class="footer">   
                <p class="copyright"> © 2022 VC ReportStream. All rights reserved.</p>
        </footer>
        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.6.0.slim.js" integrity="sha256-HwWONEZrpuoh951cQD1ov2HUK5zA5DwJ1DNUXaM6FsY=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
        <script src="../../js/scripts.js"></script>
        <script src="../../js/select2.min.js"></script>
        <script src="../../js/MultiSelect.js"></script>
        <script src="../../DataTables/datatables.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
        <script>
            var createWindow1;
            // open window
            function openWin1() {
                createWindow1 = window.open("./SubFunctions/create_investor.php", "_blank", "width=920, height=500");
            }
            var createWindow2;
            // open window
            function openWin2() {
                createWindow2 = window.open("./SubFunctions/create_portfoliocompany.php", "_blank", "width=920, height=500");
            }

        </script>
        <script>
            $(document).ready( function () {
                // Initializing the datatable plugin
                $('#table_Fund').DataTable();
                
                // Trigger the double tap to edit function
                $(document.body).on("dblclick", "tr[data-href]", function (){
                    window.location.href = this.dataset.href;
                });
            });

        // Display and Hide the import form with a button click 
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