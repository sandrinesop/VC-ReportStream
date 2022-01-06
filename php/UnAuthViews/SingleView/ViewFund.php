<?php
    // INCLUDE DATABASE CONNECTION  
    include_once('../../App/connect.php');
    $FundID =$_REQUEST['FundID'];
    // echo 'You have viewed company with ID: '. $PortfolioCompanyID;
    // QUERY DATABASE FROM DATA
    $sql="  SELECT 
                Fund.FundID, Fund.Deleted, Fund.DeletedDate, Fund.FundName, GROUP_CONCAT(DISTINCT InvestorName) AS InvestorName, GROUP_CONCAT(DISTINCT PortfolioCompanyName) AS PortfolioCompanyName, Currency.Currency,Currency.CurrencyCode, Fund.CommittedCapital, Fund.MinimumInvestment, Fund.MaximumInvestment, GROUP_CONCAT(DISTINCT InvestmentStage) AS InvestmentStage, GROUP_CONCAT(DISTINCT Industry) AS Industry , Note.Note
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

            GROUP BY FundID, Deleted, DeletedDate, FundName, Currency,CurrencyCode, CommittedCapital, MinimumInvestment, MaximumInvestment,  Note 
    "; 
    $result = mysqli_query($conn, $sql) or die($conn->error);
    
    if($result){
        // echo 'Query success';
        $row = mysqli_fetch_assoc($result);
    }else{
        echo 'Query Failed: '.mysqli_error($conn);
    };
    
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>VC ReportStream |View Company</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
        <link rel="stylesheet" href="../../../css/main.css">
        <link rel="stylesheet" href="../../../css/index.css">
    </head>
    <body>
        <!-- HEADER CONTENT -->
        <?php 
            include_once('../navBar/singleViewNav.php');
        ?>
        <!-- MAIN SECTION -->
        <main class="single_View ">
            <div class="p-2">
                <div class="container card-view-body"  enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                            <h3 for="FundName" class="form-label"> Fund</h3>
                            <p name="FundName">
                                <?php echo $row['FundName'] ?>
                            </p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                            <h3 for="InvestorName" class="form-label">Investor(s)</h3>
                            <p name="InvestorName">
                                <?php echo $row['InvestorName'] ?>
                            </p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                            <h3 for="PortfolioCompanyName" class="form-label">Company List</h3>
                            <p name="PortfolioCompanyName">
                                <?php echo $row['PortfolioCompanyName'] ?>
                            </p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                            <h3 for="Currency" class="form-label"> Currency</h3>
                            <p name="Currency">
                                <?php echo $row['Currency'] ?>
                            </p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                            <h3 for="CommittedCapital" class="form-label"> Committed Capital</h3>
                            <p name="CommittedCapital">
                                <?php echo $row['CurrencyCode'] ?><?php echo $row['CommittedCapital'] ?>
                            </p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                            <h3 for="MinimumInvestment" class="form-label"> Minimum Investment</h3>
                            <p name="MinimumInvestment">
                                <?php echo $row['CurrencyCode'] ?><?php echo $row['MinimumInvestment'] ?>
                            </p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                            <h3 for="MaximumInvestment" class="form-label"> MaximumInvestment</h3>
                            <p name="MaximumInvestment">
                                <?php echo $row['CurrencyCode'] ?><?php echo $row['MaximumInvestment'] ?>
                            </p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                            <h3 for="InvestmentStage" class="form-label"> Investment Stage</h3>
                            <p name="InvestmentStage">
                                <?php echo $row['InvestmentStage']?>
                            </p>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                            <h3 for="Note" class="form-label"> Note</h3>
                            <p name="Note">
                                <?php echo $row['Note'] ?>
                            </p>
                        </div>
                    </div>
                    <button><a href="../FundsView.php"> Close</a></button>
                </div>
            </div>
        </main>  
        <!-- SCRIPTS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>      
    </body>
</html>