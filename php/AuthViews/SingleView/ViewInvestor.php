<?php
    // INCLUDE DATABASE CONNECTION  
    include_once('../../App/connect.php');
    $InvestorID =$_REQUEST['InvestorID'];
    // echo 'You have viewed company with ID: '. $PortfolioCompanyID;
    // QUERY DATABASE FROM DATA
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
                InvestorLocation
            ON
                InvestorLocation.InvestorID = Investor.InvestorID
            LEFT JOIN 
                Country 
            ON 
                Country.CountryID = InvestorLocation.CountryID
            WHERE 
                Investor.Deleted= 0 AND Investor.InvestorID = '$InvestorID'

            GROUP BY InvestorID, Deleted, DeletedDate, InvestorName, Description, Currency, ImpactTag, YearFounded, Logo
            ORDER BY InvestorName;
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
        <link rel="stylesheet" href="../../../css/admin.css">
    </head>
    <body>
        <!-- NAVBAR -->
        <nav class="  navbar navbar-expand-lg align-middle text-light navbar-dark" style="z-index: 1; font-size: 1rem !important;">
            <div class="container px-0">
                <a style="color:#ffffff;" class="navbar-brand" href="../../../Admin.php"><img style=" width: 48px;" class="home-ico" src="../../../resources/DCA_Admin.png" alt="Digital collective africa logo"> <small>VC ReportStream</small>  </a>
                <button class="navbar-toggler text-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
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
                        <li class="nav-item">
                            <form action="../../Auth/logout.php" method="POST"  class="profile">
                                <input class="logout_btn" type="submit" name="logout"  value="logout" formmethod="POST">
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- MAIN SECTION -->
        <main class="single_View py-5">
            <div class="p-2">
                <div class="container card-view-body"  enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                            <h3 for="InvestorName" class="form-label">Investor Name</h3>
                            <p name="InvestorName">
                                <?php echo $row['InvestorName'] ?>
                            </p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                            <h3 for="Website" class="form-label">Website</h3>
                            <p name="Website">
                                <a href="<?php echo $row['Website'] ?>" target="_blank"><?php echo $row['Website'] ?></a>
                            </p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                            <h3 for="FundName" class="form-label"> Fund(s)</h3>
                            <p name="FundName">
                                <?php echo $row['FundName'] ?>
                            </p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                            <h3 for="PortfolioCompanyName" class="form-label"> Company List</h3>
                            <p name="PortfolioCompanyName">
                                <?php echo $row['PortfolioCompanyName'] ?>
                            </p>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                            <h3 for="Note" class="form-label"> Note</h3>
                            <p name="Note">
                                <?php echo $row['Note'] ?>
                            </p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                            <h3 for="Description" class="form-label"> Description</h3>
                            <p name="Description">
                                <?php echo $row['Description'] ?>
                            </p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                            <h3 for="Currency" class="form-label"> Currency</h3>
                            <p name="Currency">
                                <?php echo $row['Currency'] ?>
                            </p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                            <h3 for="YearFounded" class="form-label"> YearFounded</h3>
                            <p name="YearFounded">
                                <?php echo $row['YearFounded'] ?>
                            </p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                            <h3 for="Country" class="form-label"> Country</h3>
                            <p name="Country">
                                <?php echo $row['Country'] ?>
                            </p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                            <h3 for="Logo" class="form-label"> Logo</h3>
                            <p name="Logo">
                                <?php echo '<img src="data:image;base64,'.base64_encode($row['Logo']).'" style="width:100px;"'?>
                            </p>
                        </div>
                    </div>
                    <button type="button" class="btn btn_close">
                        <a href="../investor.php"> Close</a>
                    </button>
                    <button type="button" class="btn btn_edit">
                        <a href="../../crud/edit_investor.php?InvestorID=<?php echo $row['InvestorID']; ?>">Edit</a>
                    </button>
                </div>
            </div>
        </main>   
        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.6.0.slim.js" integrity="sha256-HwWONEZrpuoh951cQD1ov2HUK5zA5DwJ1DNUXaM6FsY=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>     
    </body>
</html>