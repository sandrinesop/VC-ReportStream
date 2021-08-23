<?php 
    include_once('../../App/connect.php');
    
    if ( isset($_POST['submit']))
    {
        // Fund TABLE
        $FundName               = $_POST['FundName'];
        // $InvestorName           = $_POST['InvestorName'];
        // $PortfolioCompanyName   = $_POST['PortfolioCompanyName'];
        $Currency               = $_POST['Currency'];
        $CommittedCapital       = $_POST['CommittedCapital'];
        $MinimumInvestment      = $_POST['MinimumInvestment'];
        $MaximumInvestment      = $_POST['MaximumInvestment'];
        $InvestmentStage        = $_POST['InvestmentStage'];
        $FundNote               = $_POST['FundNote'];
        // FUND INSERTION QUERY
        $sql = "    INSERT INTO Fund(FundID, CreatedDate, ModifiedDate, Deleted, DeletedDate, FundName, CurrencyID, CommittedCapital, MinimumInvestment, MaximumInvestment) 
                    VALUES (UUID(), now(), now(),0,NULL, '$FundName',(select C.CurrencyID FROM Currency C where C.Currency = '$Currency' ), '$CommittedCapital', '$MinimumInvestment', '$MaximumInvestment')";
        $query = mysqli_query($conn, $sql);
        if($query ){
            // echo '<script> Alert(Fund created successfully!)</script>';
            // header( "refresh: 3; url= fund.php" );
        } else {
            echo 'Oops! There was an error creating fund. Please report bug to support.'.'<br/>'.mysqli_error($conn);
        }
        // FUND NOTE INSERTION QUERY
        $sql2 = "INSERT INTO Note(NoteID, CreatedDate, ModifiedDate, Note, NoteTypeID) 
                VALUES (uuid(), now(), now(), '$FundNote','fb450e57-7056-11eb-a66b-96000010b114' )";
        $query2 = mysqli_query($conn, $sql2);

        if($query2 ){
            // echo '<script> Alert(Fund created successfully!)</script>';
            // header( "refresh: 3; url= fund.php" );
        } else {
            echo 'Oops! There was an error. Please report bug to support.'.'<br/>'.mysqli_error($conn);
        }
         
        // LINK FUND TO INVESTOR
        // $sql4 = "   INSERT INTO FundInvestor(FundInvestorID, CreatedDate, ModifiedDate, Deleted, DeletedDate, FundID, InvestorID)
        // VALUES (uuid(), now(), now(), 0, NULL, (select Fund.FundID FROM Fund where Fund.FundName = '$FundName'),(select Investor.InvestorID FROM Investor where Investor.InvestorName = '$InvestorName'))";
        // $query4 = mysqli_query($conn, $sql4);
        // if($query4){
        //     // echo '<script> Alert("Fund created successfully!")</script>';
        //     // header( "refresh: 3; url= fund.php" );
        // } else {
        //     echo 'Oops! There was an error linking Fund to Investor. Please report bug to support.'.'<br/>'.mysqli_error($conn);
        // }

        // LINK FUND TO COMPANY
        // $sql5 = "   INSERT INTO FundPortfolioCompany(FundPortfolioCompanyID, CreatedDate, ModifiedDate, Deleted, DeletedDate, FundID, PortfolioCompanyID)
        // VALUES (uuid(), now(), now(), 0, NULL, (select Fund.FundID FROM Fund where Fund.FundName = '$FundName'),(select PortfolioCompany.PortfolioCompanyID FROM PortfolioCompany where PortfolioCompany.PortfolioCompanyName = '$PortfolioCompanyName'))";
        // $query5 = mysqli_query($conn, $sql5);
        // if($query5){
        //     // Do nothing if success
        // } else {
        //     echo 'Oops! There was an error on linking Fund and Companies. Please report bug to support.'.'<br/>'.mysqli_error($conn);
        // }


        // LINK FUND TO INVESTMENTSTAGE
        $sql6 = "   INSERT INTO FundInvestmentStage(FundInvestmentStageID, CreatedDate, ModifiedDate, Deleted, DeletedDate, FundID, InvestmentStageID)
        VALUES (uuid(), now(), now(), 0, NULL, (select Fund.FundID FROM Fund where Fund.FundName = '$FundName'),(select InvestmentStage.InvestmentStageID FROM InvestmentStage where InvestmentStage.InvestmentStage = '$InvestmentStage'))";
        $query6 = mysqli_query($conn, $sql6);
        if($query6){
            // Do nothing if success
        } else {
            echo 'Oops! There was an error on linking Fund and InvestmentStage. Please report bug to support.'.'<br/>'.mysqli_error($conn);
        }

        // LINK FUND TO INVESTMENTSTAGE
        $sql7 = "   INSERT INTO FundNote(FundNoteID, CreatedDate, ModifiedDate, Deleted, DeletedDate, FundID, NoteID)
        VALUES (uuid(), now(), now(), 0, NULL, (select Fund.FundID FROM Fund where Fund.FundName = '$FundName'),(select Note.NoteID FROM Note where Note.Note = '$FundNote'))";
        $query7 = mysqli_query($conn, $sql7);
        if($query7){
            // Do nothing if success
        } else {
            echo 'Oops! There was an error on linking Fund and Note. Please report bug to support.'.'<br/>'.mysqli_error($conn);
        }

        echo '<p> New fund created successfully! </p>'
        .'<br/>'
        .'<a class="btn btn-danger" href="javascript:window.open(\'\',\'_self\').close();">Close</a>'
        .'<br/>';
        // header( "refresh: 10; url= fund.php" );
        exit();
    }
    // QUERY DATABASE FROM DATA
    $sql=" SELECT 
	            Fund.FundID, Fund.Deleted, Fund.DeletedDate, Fund.FundName, GROUP_CONCAT(DISTINCT InvestorName) AS InvestorName, GROUP_CONCAT(DISTINCT PortfolioCompanyName) AS PortfolioCompanyName , Currency.Currency, Fund.CommittedCapital, Fund.MinimumInvestment, Fund.MaximumInvestment, GROUP_CONCAT(DISTINCT InvestmentStage) AS InvestmentStage, Note.Note
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

            GROUP BY FundID, Deleted, DeletedDate, FundName, Currency, CommittedCapital, MinimumInvestment, MaximumInvestment,  Note
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

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="../../../resources/DCA_Icon.png" type="image/x-icon">
        <title>VC Reportstream | Investor</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
        <link rel="stylesheet" href="../../../css/select2.min.css">
        <link rel="stylesheet" href="../../../css/bootstrap.min.css">
        <link rel="stylesheet" href="../../../css/bootstrap.css">
        <link rel="stylesheet" href="../../../css/main.css">
    </head>
    <body class="pb-5">
        <!-- HEADER CONTENT -->
        <?php include('../../Views/navBar/sub_navbar.php');?>
        <!-- BODY CONTENT -->
        <main class="container ">
            <!-- ==== LIST OF INVESTORS ==== -->
            <div class=" my-5">
                <form class="container" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                            <label for="FundName" class="form-label">Fund Name</label>
                            <input type="FundName" class="form-control" id="FundName" aria-describedby="FundName" name="FundName" required>
                        </div>
                        <!-- investors dropdown - populated from the database -->
                        <!-- <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                            <label for="InvestorName" class="form-label">Investment Manager(s)</label>
                            <select class="form-select" id="InvestorName" name="InvestorName" required>
                                <option> Select...</option>
                                <?php
                                    while ($row102 = mysqli_fetch_assoc($result102)) {
                                        # code...
                                        echo "<option>".$row102['InvestorName']."</option>";
                                    }
                                ?>
                            </select>
                        </div> -->
                        <!-- Portfolio Company dropdown - populated from the database -->
                        <!-- <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                            <label for="PortfolioCompanyName" class="form-label">Portfolio Company </label>
                            <select class="form-select" id="PortfolioCompanyName" name="PortfolioCompanyName" required>
                                <option> Select...</option>
                                <?php
                                    while ($row103 = mysqli_fetch_assoc($result103)) {
                                        # code...
                                        echo "<option>".$row103['PortfolioCompanyName']."</option>";
                                    }
                                ?>
                            </select>
                        </div> -->
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
                            <select class="form-select" id="InvestmentStage" name="InvestmentStage" required>
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
                    <a class="btn btn-danger" href="javascript:window.open('','_self').close();">Close</a>
                </form>
            </div>
        </main>
        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.6.0.slim.js" integrity="sha256-HwWONEZrpuoh951cQD1ov2HUK5zA5DwJ1DNUXaM6FsY=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
        <script src="../../../js/scripts.js"></script>
        <script src="../../../js/select2.min.js"></script>
        <script src="../../../js/DateDropDown.js"></script>
    </body>
</html>
