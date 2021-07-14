<?php 
    include_once('../App/connect.php');
    // QUERY DATABASE FROM DATA
    $sql="  SELECT  
                Investor.InvestorID, Investor.Deleted, Investor.DeletedDate, Investor.InvestorName, GROUP_CONCAT(DISTINCT Investor.Website) AS Website, GROUP_CONCAT(DISTINCT FundName) AS FundName, GROUP_CONCAT(DISTINCT PortfolioCompanyName) AS PortfolioCompanyName, Note.Note, description.Description, currency.Currency, Investor.YearFounded, GROUP_CONCAT(DISTINCT Country) AS Country, Investor.Logo 
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
                currency 
            ON 
                currency.CurrencyID=Investor.CurrencyID
                
            LEFT JOIN 
                description 
            ON 
                description.DescriptionID=Investor.DescriptionID 
            LEFT JOIN 
                country 
            ON 
                country.CountryID = Investor.Headquarters 
            WHERE 
                Investor.Deleted= 0 
            
            GROUP BY InvestorID, Deleted, DeletedDate, InvestorName, Description, Currency,  YearFounded, Logo
            ORDER BY InvestorName;
        "; 
            
    $result = $conn->query($sql) or die($conn->error);
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

    // INVESTOR INSERTS
    if ( isset($_POST['submit'])){
            $InvestorName           = $_POST['InvestorName'];
            $InvestorWebsite        = $_POST['InvestorWebsite'];
            $FundName               = $_POST['FundName'];
            $PortfolioCompanyName   = $_POST['PortfolioCompanyName'];
            $InvestorNote           = $_POST['InvestorNote'];
            $Description            = $_POST['Description'];
            $Currency                = $_POST['Currency'];
            $YearFounded            = $_POST['YearFounded'];
            $Headquarters           = $_POST['Headquarters'];
            $Logo                   = $_FILES['img']['name'];

            // INVESTOR NOTE INSERT

            $sql2 = "INSERT INTO Note(NoteID, CreatedDate, ModifiedDate, Note, NoteTypeID)
            VALUES (uuid(), now(), now(), '$InvestorNote','fb450b19-7056-11eb-a66b-96000010b114')";
            $query2 = mysqli_query($conn, $sql2);

            if($query2){
                // echo 'Form 3 Submitted! => '.$query3.'<br/>';
            } else {
                echo 'Oops! There was an error. Please report bug to support.'.'<br/>'.mysqli_error($conn);
            };

            /* $m = "../img/".$_FILES['img']['name'];
                Use move_uploaded_file function to move files
                move_uploaded_file($_FILES['img']['tmp_name'], $m);
            */

            $Logo = addslashes(file_get_contents($_FILES["img"]["tmp_name"]));
            // tmp_name a temporary dir to store our files & we'll transfer them to the m variable path

            // ========================================================
            // MAIN INSERT QUERY INTO INVESTOR TABLE
            // ========================================================
            $sql3 ="    INSERT INTO 
                            Investor(InvestorID, CreatedDate, ModifiedDate, Deleted, DeletedDate, CurrencyID, InvestorName, Website, DescriptionID, ImpactTag, YearFounded, Headquarters, Logo) 
                        VALUES 
                            (uuid(), now(), now(),0,NULL,(select C.CurrencyID FROM currency C where C.Currency = '$Currency' ),'$InvestorName', '$InvestorWebsite',(select de.DescriptionID FROM Description de where de.Description = '$Description'), '$ImpactTag', '$YearFounded', (select country.CountryID FROM country where country.Country = '$Headquarters'),'$Logo')
            ";
            $query3 = mysqli_query($conn, $sql3);
            if($query3){
                // Do Nothing
            }else{
                echo 'Oops! There was an error creating new Investor. Please report bug to support.'.'<br/>'.mysqli_error($conn);
            }
            // ============================================
            // LOOP TO INSERT FUNDS ON INVESTMENT MANAGER
            // ============================================
            foreach ($FundName as $Fund) {
                $sql4 = "   INSERT INTO 
                                FundInvestor(FundInvestorID, CreatedDate, ModifiedDate, Deleted, DeletedDate, FundID, InvestorID)
                            VALUES 
                                (uuid(), now(), now(), 0, NULL, (select Fund.FundID FROM Fund where Fund.FundName = '$Fund'),(select Investor.InvestorID FROM Investor where Investor.InvestorName = '$InvestorName'))
                ";
                $query4 = mysqli_query($conn, $sql4);
                if($query4){
                    // Do nothing
                } else {
                    echo 'Oops! There was an error linking Investor to Fund  . Please report bug to support.'.'<br/>'.mysqli_error($conn);
                }
            }
            // ============================================
            // LOOP TO INSERT COMPANY ON INVESTMENT MANAGER
            // ============================================
            foreach ($PortfolioCompanyName as $Company) {
                
                $sql5 = "   INSERT INTO 
                                InvestorPortfolioCompany(InvestorPortfolioCompanyID, CreatedDate, ModifiedDate, Deleted, DeletedDate,InvestorID, PortfolioCompanyID)
                            VALUES 
                                (uuid(), now(), now(), 0, NULL, (select Investor.InvestorID FROM Investor where Investor.InvestorName = '$InvestorName'), (select PortfolioCompany.PortfolioCompanyID FROM PortfolioCompany where PortfolioCompany.PortfolioCompanyName = '$Company'))
                ";
                $query5 = mysqli_query($conn, $sql5);
                if($query5){
                    // Do nothing
                } else {
                    echo 'Oops! There was an error linking Investor to Company  . Please report bug to support.'.'<br/>'.mysqli_error($conn);
                }
            }
            // ============================================
            // LINK INVESTOR TO NOTE
            // ============================================
            $sql6 = "   INSERT INTO 
                            InvestorNote(InvestorNoteID, CreatedDate, ModifiedDate, Deleted, DeletedDate,InvestorID, NoteID)
                        VALUES 
                            (uuid(), now(), now(), 0, NULL, (select Investor.InvestorID FROM Investor where Investor.InvestorName = '$InvestorName'), (select Note.NoteID FROM Note where Note.Note = '$InvestorNote'))
            ";
            $query6 = mysqli_query($conn, $sql6);
            if($query6){
                // Do nothing
            } else {
                echo 'Oops! There was an error linking Investor to Note. Please report bug to support.'.'<br/>'.mysqli_error($conn);
            }
            
            $conn->close();
            echo '<H3>Thanks for your contibution!</H3>'
            .'<br/>'
            .'<small>You will be redirected shortly...</small>';
            header( "refresh: 3;url= Investor.php" );
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="../../resources/DCA_Icon.png" type="image/x-icon">
        <title>VC Reportstream | Investor</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
        <link rel="stylesheet" href="../../css/select2.min.css">
        <link rel="stylesheet" href="../../css/bootstrap.min.css">
        <link rel="stylesheet" href="../../css/bootstrap.css">
        <link rel="stylesheet" href="../../css/main.css">
    </head>
    <body class="pb-5">
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
                            <a class="nav-link" href="#">Contact</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- BODY CONTENT -->
        <main class="container ">
            <!-- ==== LIST OF INVESTORS ==== -->
            <div class=" my-5">
                <div class="my-2">
                    <div class="row">
                        <!-- CREATE NEW INVESTOR MODAL -->
                        <span class="col-6 col-md-4 col-lg-2">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn new-button " data-bs-toggle="modal" data-bs-target="#exampleModal">
                                Add New Manager  <img src="../../resources/icons/New.svg" alt="">
                            </button>
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">New Investment Manager</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form class="container" method="POST" enctype="multipart/form-data">
                                                <div class="row">
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                                        <label for="InvestorName" class="form-label"> Name</label>
                                                        <input list="Investors" type="text" class="form-control" id="InvestorName" name="InvestorName" required>
                                                    </div>  
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                                        <label for="InvestorWebsite" class="form-label">Website</label>
                                                        <input type="text" class="form-control" id="InvestorWebsite" name="InvestorWebsite" required>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="FundName" class="form-label" >Fund </label>
                                                        <select class="form-select FundName" id="FundName" name="FundName[]" multiple="true" required>
                                                            <option> Select Fund...</option>
                                                            <?php
                                                                while ($row100 = mysqli_fetch_assoc($result100)) {
                                                                    # code...
                                                                    echo "<option>".$row100['FundName']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                        <button onclick="openWin2()" target="_blank" class="btn btn-outline-success btn-sm">
                                                            Add new Fund
                                                        </button>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="PortfolioCompanyName" class="form-label">Portfolio Company</label>
                                                        <select class="form-select PortfolioCompanyName" id="PortfolioCompanyName" name="PortfolioCompanyName[]" multiple="true" required>
                                                            <option> Select Portfolio Company...</option>
                                                            <?php
                                                                while ($row101 = mysqli_fetch_assoc($result101)) {
                                                                    # code...
                                                                    echo "<option>".$row101['PortfolioCompanyName']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                        <button onclick="openWin()" target="_blank" class="btn btn-outline-success btn-sm">
                                                            Add new Company
                                                        </button>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="InvestorNote" class="form-label"> Note</label>
                                                        <textarea class="form-control InvestorNote" aria-label="With textarea" id=" InvestorNote" name=" InvestorNote"></textarea>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="Description" class="form-label">Description</label>
                                                        <select class="form-select" id="Description" name="Description">
                                                            <option> Select Description...</option>
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
                                                            <option> Select...</option>
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
                                                        <select class="form-control" name="YearFounded" id="YearFounded"required>
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
                                                        <input type="file" class="form-control" id="img" name="img" required>
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
                        <!-- EXPORT CSV FILE -->
                        <span class="col-6 col-md-4 col-lg-2"> 
                            <form action="../InvestorExport.php" method="POST">
                                <button class="btn new-button" type="submit" name="export" formmethod="POST"> Export CSV</button>
                            </form>
                        </span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body bg-secondary">
                        <div class="table-responsive" style="overflow-x:auto;">
                            <table class=" table table-hover table-striped table-success table-bordered table-responsive" style="Width: 3600px; line-height: 18px; ">
                                <t>
                                    <th scope="col">Investment Manager</th>
                                    <th scope="col">Website</th>
                                    <th scope="col">Fund((s)</th>
                                    <th scope="col">Portfolio Company List</th>
                                    <th scope="col">Note</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Currency</th>
                                    <th scope="col">Year Founded</th>
                                    <th scope="col">Country</th>
                                    <th scope="col">Logo</th>
                                    <th scope="col">Edit </th>
                                    <th scope="col">Delete </th>
                                </t>
                                <?php
                                    while($rows = mysqli_fetch_assoc($result) )
                                    {
                                ?>
                                    <tr>
                                        <td class="text-truncate"> <small> <?php echo $rows['InvestorName'] ?> </small></td>
                                        <td class="text-truncate"> <small> <a href="<?php echo $rows['Website'] ?>" target="_Blank"><?php echo $rows['Website'] ?></a> </small></td>
                                        <td class="text-truncate"> <small> <?php echo $rows['FundName'] ?> </small></td>
                                        <td class="text-truncate"> <small> <?php echo $rows['PortfolioCompanyName'] ?> </small></td>
                                        <td class="text-truncate"> <small> <?php echo $rows['Note'] ?> </small></td>
                                        <td class="text-truncate"> <small> <?php echo $rows['Description'] ?> </small></td>
                                        <td class="text-truncate"> <small> <?php echo $rows['Currency'] ?> </small></td>
                                        <td class="text-truncate"> <small> <?php echo $rows['YearFounded'] ?> </small></td>
                                        <td class="text-truncate"> <small> <?php echo $rows['Country']?> </small></td>
                                        <td class="text-truncate"> <small> <?php echo '<img src="data:image;base64,'.base64_encode($rows['Logo']).'" style="width:100px; height:60px;">'?> </small></td>
                                        <td class="text-truncate"> <small> <a href="../crud/edit_Investor.php?InvestorID=<?php echo $rows['InvestorID']; ?> ">Edit</a></small></td>
                                        <td class="text-truncate"> <small> <a href="../crud/Delete.php?InvestorID=<?php echo $rows['InvestorID']; ?>">Delete</a> </small></td>
                                    </tr>
                                <?php 
                                    }
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.6.0.slim.js" integrity="sha256-HwWONEZrpuoh951cQD1ov2HUK5zA5DwJ1DNUXaM6FsY=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
        <script src="../../js/scripts.js"></script>
        <script src="../../js/select2.min.js"></script>
        <script src="../../js/DateDropDown.js"></script>
        <script src="../../js/MultiSelect.js"></script>
        <script>
            var createWindow;
            // open window
            function openWin() {
                createWindow = window.open("./SubFunctions/create_portfoliocompany.php", "_blank", "width=920, height=500");
            }

            var createWindow2;
            // open window
            function openWin2() {
                createWindow2 = window.open("./SubFunctions/create_fund.php", "_blank", "width=920, height=500");
            }
        </script>
    </body>
</html>
