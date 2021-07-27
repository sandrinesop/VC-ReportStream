<?php 
    include_once('../App/connect.php');
    // QUERY DATABASE FROM DATA
    $FundID =$_REQUEST['FundID'];
    $sql="  SELECT 
                Fund.FundID, Fund.Deleted, Fund.DeletedDate, Fund.FundName, GROUP_CONCAT(DISTINCT InvestorName) AS InvestorName, GROUP_CONCAT(DISTINCT PortfolioCompanyName) AS PortfolioCompanyName, currency.Currency, Fund.CommittedCapital, Fund.MinimumInvestment, Fund.MaximumInvestment, GROUP_CONCAT(DISTINCT InvestmentStage) AS InvestmentStage, GROUP_CONCAT(DISTINCT Industry) AS Industry , Note.Note
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
                currency 
            ON 
                currency.CurrencyID = Fund.CurrencyID 
            WHERE  
                Fund.Deleted = 0 AND Fund.FundID = '$FundID'

            GROUP BY FundID, Deleted, DeletedDate, FundName, Currency, CommittedCapital, MinimumInvestment, MaximumInvestment,  Note 
    "; 

    $result = mysqli_query($conn, $sql) or die($conn->error);
    $row = mysqli_fetch_assoc($result);

    // $tempCurrency = $row['CurrencyID'];
    // $sql3 = " Select Currency from Currency where CurrencyID = '$tempCurrency' ";
    // $result3 = mysqli_query($conn, $sql3) or die($conn->error);
    // $row3 = mysqli_fetch_assoc($result3);

    $status = "";
    if(isset($_POST['new']) && $_POST['new']==1)
    {
        $FundName                   = $_REQUEST['FundName'];
        $InvestorName               = $_REQUEST['InvestorName'];
        $PortfolioCompanyName       = $_REQUEST['PortfolioCompanyName'];
        $Currency                   = $_REQUEST['Currency'];
        $CommittedCapital           = $_REQUEST['CommittedCapital'];
        $MinimumInvestment          = $_REQUEST['MinimumInvestment'];
        $MaximumInvestment          = $_REQUEST['MaximumInvestment'];

        $update=" UPDATE Fund SET ModifiedDate='uuid()',FundName='".$FundName."', CurrencyID=(select C.CurrencyID FROM currency C where C.Currency = '$Currency' ), CommittedCapitalOfFund='".$CommittedCapitalOfFund."', CommittedCapital='".$CommittedCapital."', MinimumInvestment='".$MinimumInvestment."', MaximumInvestment='".$MaximumInvestment."' WHERE FundID='".$FundID."'";

        mysqli_query($conn, $update) or die($conn->error);
        $status = "Record Updated Successfully. </br></br>
        <a href='../tabs/fund.php'>View Updated Record</a>";
        echo '<p style="color:#FF0000;">'.$status.'</p>';
        header( "refresh: 3;url= ../tabs/fund.php" );
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
                <input name="PortfolioCompanyID" type="hidden" value="<?php echo $row['FundID'];?>"/>
                <p>
                    <label for="Website" class="form-label"> Fund Name </label>
                    <input class="form-control col" type="text" name="FundName" placeholder="Enter FundName"  value="<?php echo $row['FundName'];?>" />
                </p>
                <p>
                    <label for="InvestorName" class="form-label">Investment Manager(s)</label>
                    <input class="form-control col" type="text" name="InvestorName" placeholder="Enter InvestorName"  value="<?php echo $row['InvestorName'];?>" />
                </p>
                <p>
                    <label for="PortfolioCompanyName" class="form-label">Portfolio Company List</label>
                    <input class="form-control col" type="text" name="PortfolioCompanyName" placeholder="Enter PortfolioCompanyName"  value="<?php echo $row['PortfolioCompanyName'];?>"/>
                </p>
                <p>
                    <label for="Currency" class="form-label">currency</label>
                    <input class="form-control col" type="text" name="Currency" placeholder="Enter Currency" value="<?php echo $row['Currency'];?>"/>
                </p>
                <p>
                    <label for="CommittedCapital" class="form-label">Committed Capital</label>
                    <input class="form-control col" type="text" name="CommittedCapital" placeholder="Enter CommittedCapital" value="<?php echo $row['CommittedCapital'];?>"/>
                </p>
                <p>
                    <label for="MinimumInvestment" class="form-label">Minimum Investment</label>
                    <input class="form-control col" type="text" name="MinimumInvestment" placeholder="Enter MinimumInvestment"  value="<?php echo $row['MinimumInvestment'];?>"/>
                </p>
                <p>
                    <label for="MaximumInvestment" class="form-label">Maximum Investment</label>
                    <input class="form-control col" type="text" name="MaximumInvestment" placeholder="Enter MaximumInvestment"  value="<?php echo $row['MaximumInvestment'];?>"/>
                </p>
                <p>
                    <label for="InvestmentStage" class="form-label">Investment Stage</label>
                    <input class="form-control col" type="text" name="InvestmentStage" placeholder="Enter InvestmentStage"  value="<?php echo $row['InvestmentStage'];?>" />
                </p>
                <p>
                    <label for="Industry" class="form-label">Industry</label>
                    <input class="form-control col" type="text" name="Industry" placeholder="Enter Industry"  value="<?php echo $row['Industry'];?>" />
                </p>
                <p>
                    <label for="Note" class="form-label">Note</label>
                    <input class="form-control col" type="text" name="Note" placeholder="Enter Note"  value="<?php echo $row['Note'];?>" />
                </p>
                <p>
                    <Button name="Update" type="submit" value="Update" class="btn btn-primary" formmethod="POST">Update</Button>
                    <a href="../tabs/fund.php" class="btn btn-danger" >Close</a>
                </p>
            </form>
            <?php } ?>
        </main>
    </body>
</html>