<?php 
    include_once('../connect.php');
    // QUERY DATABASE FROM DATA
    $sql=" SELECT 
	            fund.FundID, fund.Deleted, fund.DeletedDate, fund.FundName, currency.Currency, fund.CommittedCapitalOfFund, fund.CommittedCapital, fund.MinimumInvestment, fund.MaximumInvestment 
            FROM 
                fund 
            LEFT JOIN 
                currency 
            ON 
                currency.CurrencyID = fund.CurrencyID 
            WHERE  
                fund.Deleted = 0
        ";

    $result = $conn->query($sql) or die($conn->error);

    if ( isset($_POST['submit']))
    {
        // FUND TABLE

        $FundName               = $_POST['FundName'];
        $CommittedCapitalOfFund = $_POST['CommittedCapitalOfFund'];
        $CommittedCapital       = $_POST['CommittedCapital'];
        $MinimumInvestment      = $_POST['MinimumInvestment'];
        $MaximumInvestment      = $_POST['MaximumInvestment'];
        $FundNote               = $_POST['FundNote'];
        $Currency               = $_POST['Currency'];
        
        $sql = "INSERT INTO Fund(FundID, CreatedDate, ModifiedDate, FundName, CurrencyID, CommittedCapitalOfFund, CommittedCapital, MinimumInvestment, MaximumInvestment) 
        VALUES (uuid(), now(), now(), '$FundName',(select C.CurrencyID FROM currency C where C.Currency = '$Currency' ), '$CommittedCapitalOfFund', '$CommittedCapital', '$MinimumInvestment', '$MaximumInvestment')";
        
        $query = mysqli_query($conn, $sql);

        if($query){
            $sql2 = "INSERT INTO Note(NoteID, CreatedDate, ModifiedDate, Note) 
        VALUES (uuid(), now(), now(), '$FundNote')";
            header( "refresh: 3; url= fund.php" );
        } else {
            echo 'Oops! There was an error submitting form. Please try again later.';
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
        <link rel="stylesheet" href="../../css/bootstrap.min.css">
        <link rel="stylesheet" href="../../css/bootstrap.css">
        <link rel="stylesheet" href="../../css/main.css">
    </head>
    <body class="pb-5">
        <!-- HEADER CONTENT -->
        <nav class="container navbar navbar-expand-lg align-middle" style="z-index: 1;">
            <div class="container-fluid">
                <a style="color:#ffffff;" class="navbar-brand" href="../../index.php"><img style=" width: 80px;" class="home-ico" src="../../resources/DCA_Icon.png" alt="Digital collective africa logo"> VC Reportstream </a>
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
        <!-- BODY CONTENT -->
        <main class="container ">
            <!-- ==== LIST OF PORTFOLIO COMPANIES ==== -->
            <div class=" my-5">
                <div class="my-2">
                    <div class="row">
                        <!-- CREATE NEW PORTFOLIO COMPANY MODAL -->
                        <span class="col-1">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn new-button " data-bs-toggle="modal" data-bs-target="#exampleModal">
                                Fund <img src="../../resources/icons/New.svg" alt="">
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
                                                    <!-- Actual Currencies as in the DB --> 
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="Currency" class="form-label">Currency</label>
                                                        <select name="Currency" class="form-select" id="Currency" required>  
                                                            <option value="" selected >Choose...</option>
                                                            <option value="Ethiopia Birr" >Ethiopia Birr</option>
                                                            <option value="Angola Kwanza" >Angola Kwanza</option>
                                                            <option value="Botswana Pula" >Botswana Pula</option>
                                                            <option value="British Pound" >British Pound</option>
                                                            <option value="Canada Dollar" >Canada Dollar</option>
                                                            <option value="Central African CFA franc" >Central African CFA franc</option>
                                                            <option value="Congo Franc" >Congo Franc</option>
                                                            <option value="Denmark Krone" >Denmark Krone</option>
                                                            <option value="Egypt Pound" >Egypt Pound</option>
                                                            <option value="Euro" >Euro</option>
                                                            <option value="Ghana Cedi" >Ghana Cedi</option>
                                                            <option value="Indian Rupee" >Indian Rupee</option>
                                                            <option value="Israel Shekel" >Israel Shekel</option>
                                                            <option value="Kenya Shilling" >Kenya Shilling</option>
                                                            <option value="Liberia Dollar" >Liberia Dollar</option>
                                                            <option value="Madagascar Ariary" >Madagascar Ariary</option>
                                                            <option value="Malawi Kwacha" >Malawi Kwacha</option>
                                                            <option value="Malaysia Ringgit" >Malaysia Ringgit</option>
                                                            <option value="Mauritius Rupee" >Mauritius Rupee</option>
                                                            <option value="Morocco Dirham" >Morocco Dirham</option>
                                                            <option value="Mozambique Metical" >Mozambique Metical</option>
                                                            <option value="Namibian Dollar" >Namibian Dollar</option>
                                                            <option value="Nepal Rupee" >Nepal Rupee</option>
                                                            <option value="Nigeria Naira" >Nigeria Naira</option>
                                                            <option value="Norway Krone" >Norway Krone</option>
                                                            <option value="Pakistan Rupee" >Pakistan Rupee</option>
                                                            <option value="Rwanda Franc" >Rwanda Franc</option>
                                                            <option value="Sierra Leone Leone" >Sierra Leone Leone</option>
                                                            <option value="Singapore Dollar" >Singapore Dollar</option>
                                                            <option value="Somalia Shilling" >Somalia Shilling</option>
                                                            <option value="South African Rand" >South African Rand</option>
                                                            <option value="Sudan Pound" >Sudan Pound</option>
                                                            <option value="Switzerland Franc" >Switzerland Franc</option>
                                                            <option value="Tanzania Shilling" >Tanzania Shilling</option>
                                                            <option value="Tunisia Dinar" >Tunisia Dinar</option>
                                                            <option value="Uganda Shilling" >Uganda Shilling</option>
                                                            <option value="United Arab Emirates Dirham" >United Arab Emirates Dirham</option>
                                                            <option value="Unknown Currency" >Unknown Currency</option>
                                                            <option value="US Dollar" >US Dollar</option>
                                                            <option value="West African CFA franc" >West African CFA franc</option>
                                                            <option value="Zambia Kwacha" >Zambia Kwacha</option>
                                                            <option value="Zimbabwe Dollar" >Zimbabwe Dollar</option>                          
                                                        </select>
                                                    </div>  
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                                        <label for="CommittedCapitalOfFund" class="form-label"> Committed Capital Of Fund</label>
                                                        <input type="text" class="form-control" id="CommittedCapitalOfFund" name="CommittedCapitalOfFund" >
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
                                                        <select name="InvestmentStage" class="form-select" id="InvestmentStage" required>
                                                            <option value=""selected >Choose...</option>
                                                            <option value="Pre-Seed">Pre-Seed</option>
                                                            <option value="Seed">Seed</option>
                                                            <option value="Pre-Series A">Pre-Series A</option>
                                                            <option value="Series A">Series A</option>
                                                            <option value="Pre-Series B">Pre-Series B</option>
                                                            <option value="Series B">Series B</option>
                                                            <option value="Pre-Series C">Pre-Series C</option>
                                                            <option value="Series C">Series C</option>
                                                            <option value="Pre-Series D">Pre-Series D</option>
                                                            <option value="Series D">Series D</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="IndustryPreference" class="form-label">Industry Preference</label>
                                                        <textarea class="form-control IndustryPreference" aria-label="With textarea" id=" IndustryPreference" name=" IndustryPreference"></textarea>
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
                        <!-- EXPORT CSV FILE -->
                        <span class="col-2"> 
                            <form action="../FundExport.php" method="POST">
                                <button class="btn new-button" type="submit" name="export" formmethod="POST"> Export CSV</button>
                            </form>
                        </span>
                    </div>
                </div>
                <!-- TABLE OF ALL PORTFOLIO COMPANIES -->
                <div class="table-responsive" style="overflow-x:auto;">
                    <table class=" table table-hover table-striped table-success table-bordered" style="Width: 2400px;line-height: 30px;">
                        <t>
                            <th scope="col">Fund Name</th>
                            <th scope="col">Currency</th>
                            <th scope="col">Committed Capital Of Fund</th>
                            <th scope="col">Committed Capital </th>
                            <th scope="col">Minimum Investment</th>
                            <th scope="col">Maximum Investment</th>
                            <th scope="col">Edit </th>
                            <th scope="col">Delete </th>
                        </t>
                        <?php
                            while($rows = mysqli_fetch_assoc($result))
                            {
                        ?>
                        <tr>
                            <td> <?php echo $rows['FundName'] ?></td>
                            <td> <?php echo $rows['Currency'] ?></td>
                            <td> <?php echo $rows['CommittedCapitalOfFund'] ?></td>
                            <td> <?php echo $rows['CommittedCapital'] ?></td>
                            <td> <?php echo $rows['MinimumInvestment'] ?></td>
                            <td> <?php echo $rows['MaximumInvestment'] ?></td>
                            <td> <a href="../crud/edit_fund.php?FundID=<?php echo $rows['FundID']; ?>">Edit</a></td>
                            <td> <a href="../crud/delete_fund.php?FundID=<?php echo $rows['FundID']; ?>">Delete</a></td>
                        </tr>
                        <?php 
                            }
                        ?>
                    </table>
                </div> 
            </div>
        </main>
        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.6.0.slim.js" integrity="sha256-HwWONEZrpuoh951cQD1ov2HUK5zA5DwJ1DNUXaM6FsY=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
        <script src="../../js/scripts.js"></script>
    </body>
</html>