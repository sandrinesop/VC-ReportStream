<?php 
    include_once('../connect.php');
    // QUERY DATABASE FROM DATA
    $NewsID =$_REQUEST['NewsID'];
    // QUERY DATABASE FROM DATA
    $sqlA1="    SELECT 
                    News.NewsID, News.NewsURL, News.NewsDate, PortfolioCompany.PortfolioCompanyName, PortfolioCompany.TotalInvestmentValue, GROUP_CONCAT(InvestorName) AS InvestorName,GROUP_CONCAT(Sector) AS Sector, GROUP_CONCAT(FundName) AS FundName, GROUP_CONCAT(InvestmentStage) AS InvestmentStage, Country.Country 
                FROM 
                    PortfolioCompanyNews 
                LEFT JOIN 
                    News 
                ON
                    News.NewsID = PortfolioCompanyNews.NewsID 
                LEFT JOIN 
                    PortfolioCompany
                ON
                    PortfolioCompany.PortfolioCompanyID = PortfolioCompanyNews.PortfolioCompanyID 
                LEFT JOIN 
                    InvestorNews
                ON
                    InvestorNews.NewsID = PortfolioCompanyNews.NewsID 
                LEFT JOIN 
                    Investor
                ON
                    Investor.InvestorID = InvestorNews.InvestorID 
                LEFT JOIN 
                    PortfolioCompanySector
                ON
                    PortfolioCompanySector.PortfolioCompanyID = PortfolioCompanyNews.PortfolioCompanyID         
                LEFT JOIN 
                    Sector          
                ON
                    Sector.SectorID = PortfolioCompanySector.SectorID      
                LEFT JOIN 
                    FundPortfolioCompany
                ON
                    FundPortfolioCompany.PortfolioCompanyID = PortfolioCompanyNews.PortfolioCompanyID
                LEFT JOIN 
                    Fund
                ON
                    Fund.FundID = FundPortfolioCompany.FundID 
                LEFT JOIN 
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
                    PortfolioCompanyCountry.PortfolioCompanyID = PortfolioCompanyNews.PortfolioCompanyID
                LEFT JOIN 
                    Country
                ON 
                    Country.CountryID = PortfolioCompanyCountry.CountryID

                GROUP BY News.NewsURL, News.NewsDate, PortfolioCompany.PortfolioCompanyName, PortfolioCompany.TotalInvestmentValue,Country.Country ";
    $resultA1 = $conn->query($sqlA1) or die($conn->error);
    $rowA1 = mysqli_fetch_assoc($resultA1);

    
    $status = "";
    if(isset($_POST['new']) && $_POST['new']==1)
    {
        $InvestorID     =$_REQUEST['InvestorID'];
        $ModifiedDate   =$_REQUEST['ModifiedDate'];
        $InvestorName   =$_REQUEST['InvestorName'];
        $Website        =$_REQUEST['Website'];
        $Description    =$_REQUEST['Description'];
        $ImpactTag      =$_REQUEST['ImpactTag'];
        $YearFounded    =$_REQUEST['YearFounded'];
        $Headquarters   =$_REQUEST['Headquarters']; 
        $Logo           =$_REQUEST['Logo'];
    }
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
                <input type="hidden" name="new" value="1" />
                <input name="NewsID" type="hidden" value="<?php echo $rowA1['NewsID'];?>" />
                <p><input class="form-control col" type="text" name="NewsDate" required value="<?php echo $rowA1['NewsDate'];?>" DISABLED/></p>
                <p><input class="form-control col" type="text" name="PortfolioCompanyName" placeholder="Portfolio Company"  value="<?php echo $rowA1['PortfolioCompanyName'];?>" DISABLED/></p>
                
                <p><input class="form-control col" type="text" name="InvestorName" placeholder="Unknown Investor(s)"  value="<?php echo $rowA1['InvestorName'];?>" DISABLED/></p>
                
                <p><input class="form-control col" type="text" name="FundName" placeholder="Unknown Fund(s)"  value="<?php echo $rowA1['FundName'];?>" DISABLED/></p>
                
                <p><input class="form-control col" type="text" name="TotalInvestmentValue" placeholder="Undisclosed Amount"  value="<?php echo $rowA1['TotalInvestmentValue'];?>" DISABLED/></p>
                
                <p><input class="form-control col" type="text" name="InvestmentStage" placeholder="Unknown Investment Stage"  value="<?php echo $rowA1['InvestmentStage'];?>" DISABLED/></p>
                
                <p><input class="form-control col" type="text" name="Sector" placeholder="Enter Sector"  value="<?php echo $rowA1['Sector'];?>" DISABLED/></p>
                
                <p><input class="form-control col" type="text" name="Country" placeholder="Unknown Headquarters"  value="<?php echo $rowA1['Country'];?>" DISABLED/></p>

                <button><a href="../../index.php"> Close</a></button>
            </form>
        </main>
    </body>
</html>
