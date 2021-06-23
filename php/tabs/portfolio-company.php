<?php 
    include_once('../connect.php');
    // QUERY DATABASE FROM DATA
    $sql=" SELECT 
	portfoliocompany.PortfolioCompanyID,portfoliocompany.Deleted, portfoliocompany.DeletedDate, portfoliocompany.PortfolioCompanyName, currency.Currency, portfoliocompany.Website, portfoliocompany.TotalInvestmentValue, portfoliocompany.Stake, portfoliocompany.Details, portfoliocompany.YearFounded, country.Country, portfoliocompany.Logo 
FROM 
	portfoliocompany 
LEFT JOIN 
	currency 
ON 
	currency.CurrencyID = portfoliocompany.CurrencyID 
LEFT JOIN 
	country 
ON 
	country.CountryID = portfoliocompany.Headquarters 
WHERE 
	portfoliocompany.Deleted = 0; "; 

    $result = $conn->query($sql) or die($conn->error);
    
    if ( isset($_POST['submit']))
    {
        // DEFINED VAR FOR THE SECOND TABLE
        // PORTFOLIO COMPANY TABLE
        $PortfolioCompanyName    = $_POST['PortfolioCompanyName'];
        $PortfolioCompanyWebsite = $_POST['Website'];
        $Details                 = $_POST['Details'];
        $YearFounded             = $_POST['YearFounded'];
        $Headquarters            = $_POST['Headquarters'];
        $Stake                   = $_POST['Stake'];
        $TotalInvestmentValue    = $_POST['TotalInvestmentValue'];
        $Industry                = $_POST['Industry'];
        $Sector                  = $_POST['Sector'];
        $Currency                = $_POST['Currency'];
        $Logo                    = $_FILES['img']['name'];

        // Company Logo Insert code
        $Logo = addslashes(file_get_contents($_FILES["img"]["tmp_name"]));

        // PORTFOLIO COMPANY NOTE INSERT
        $sql = "INSERT INTO PortfolioCompany( PortfolioCompanyID, CreatedDate, ModifiedDate, Deleted, DeletedDate, PortfolioCompanyName, CurrencyID, Website,TotalInvestmentValue, Stake, Details, YearFounded, Headquarters, Logo)
            VALUES (uuid(), now(), now(), 0, NULL,'$PortfolioCompanyName', (select C.CurrencyID FROM currency C where C.Currency = '$Currency' ), '$PortfolioCompanyWebsite','$TotalInvestmentValue', '$Stake', '$Details', '$YearFounded', (select country.CountryID FROM country where country.Country = '$Headquarters'), '$Logo')";
            $query = mysqli_query($conn, $sql);
        // if($query){
        //     header( "refresh: 3; url= portfolio-company.php" );
        // } else {
        //     echo 'Oops! There was an error creating the company. Please try again later.'.'<br/>'.mysqli_error($conn);
        // }
        // LINKING COMPANY WITH SECTOR AND INDUSTRY
        if($query){
        // LINKING COMPANY WITH SECTOR AND INDUSTRY
        $sql2 = "   INSERT INTO PortfolioCompanySector(PortfolioCompanySectorID, CreatedDate, ModifiedDate, Deleted, DeletedDate, PortfolioCompanyID, SectorID)
                    VALUES (uuid(), now(), now(), 0, NULL,(select PortfolioCompany.PortfolioCompanyID FROM PortfolioCompany where PortfolioCompany.PortfolioCompanyName = '$PortfolioCompanyName'), (select S.SectorID FROM sector S where S.Sector = '$Sector'))";
        $query2 = mysqli_query($conn, $sql2);

        header( "refresh: 3; url= portfolio-company.php" );

        } else {
            echo 'Oops! There was an error Linking PortfolioCompany and Sector'.mysqli_error($conn).'<br/>';
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
        <title>VC Reportstream | Portfolio Company</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
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
            <!-- ==== LIST OF PORTFOLIO COMPANIES ==== -->
            <div class=" my-5">
                <div class="my-2">
                    <div class="row">
                        <!-- CREATE NEW PORTFOLIO COMPANY MODAL -->
                        <span class="col-3">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn new-button " data-bs-toggle="modal" data-bs-target="#exampleModal">
                                Portfolio Company <img src="../../resources/icons/New.svg" alt="">
                            </button>
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Create A New Portfolio Company</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form class="container" method="POST" enctype="multipart/form-data">
                                                <div class="row">
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="PortfolioCompanyName" class="form-label"> Portfolio Company Name</label>
                                                        <input type="text" class="form-control" id="PortfolioCompanyName" name="PortfolioCompanyName" required>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="Website" class="form-label">Company Website</label>
                                                        <input type="text" class="form-control" id="Website" name="Website" required>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="Details" class="form-label">Details</label>
                                                        <input type="text" class="form-control" id="Details" name="Details">
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="YearFounded" class="form-label">Year Founded</label>
                                                        <input type="text" class="form-control" id="YearFounded" name="YearFounded" required>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="Headquarters" class="form-label">Headquarters</label>
                                                        <select id="Headquarters" name="Headquarters" class="form-select">
                                                            <option>choose...</option>
                                                            <option value="Unknown">Unknown</option>
                                                            <option value="Finland">Finland</option>
                                                            <option value="Canada">Canada</option>
                                                            <option value="Angola">Angola</option>
                                                            <option value="Benin">Benin</option>
                                                            <option value="Botswana">Botswana</option>
                                                            <option value="Burkina Faso">Burkina Faso</option>
                                                            <option value="Cameroon">Cameroon</option>
                                                            <option value="Chad">Chad</option>
                                                            <option value="Democratic Republic of Congo">Democratic Republic of Congo</option>
                                                            <option value="Denmark">Denmark</option>
                                                            <option value="Egypt">Egypt</option>
                                                            <option value="Eswatini">Eswatini</option>
                                                            <option value="Ethiopia">Ethiopia</option>
                                                            <option value="EU">EU</option>
                                                            <option value="Gabon">Gabon</option>
                                                            <option value="Germany">Germany</option>
                                                            <option value="Ghana">Ghana</option>
                                                            <option value="India">India</option>
                                                            <option value="Israel">Israel</option>
                                                            <option value="Ivory Coast">Ivory Coast</option>
                                                            <option value="Kenya">Kenya</option>
                                                            <option value="Lesotho">Lesotho</option>
                                                            <option value="Liberia">Liberia</option>
                                                            <option value="Madagascar">Madagascar</option>
                                                            <option value="Malawi">Malawi</option>
                                                            <option value="Malaysia">Malaysia</option>
                                                            <option value="Mali">Mali</option>
                                                            <option value="Mauritius">Mauritius</option>
                                                            <option value="Morocco">Morocco</option>
                                                            <option value="Mozambique">Mozambique</option>
                                                            <option value="Namibia">Namibia</option>
                                                            <option value="Nepal">Nepal</option>
                                                            <option value="Netherlands">Netherlands</option>
                                                            <option value="Niger">Niger</option>
                                                            <option value="Nigeria">Nigeria</option>
                                                            <option value="Pakistan">Pakistan</option>
                                                            <option value="Pan-African">Pan-African</option>
                                                            <option value="Republic of Congo">Republic of Congo</option>
                                                            <option value="Rwanda">Rwanda</option>
                                                            <option value="Senegal">Senegal</option>
                                                            <option value="Sierra Leone">Sierra Leone</option>
                                                            <option value="Singapore">Singapore</option>
                                                            <option value="Somalia">Somalia</option>
                                                            <option value="South Africa">South Africa</option>
                                                            <option value="South Sudan">South Sudan</option>
                                                            <option value="Sub-Saharan Africa">Sub-Saharan Africa</option>
                                                            <option value="Switzerland">Switzerland</option>
                                                            <option value="Tanzania">Tanzania</option>
                                                            <option value="Togo">Togo</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="Stake" class="form-label">Stake</label>
                                                        <input type="text" class="form-control" id="Stake" name="Stake">
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="TotalInvestmentValue" class="form-label">Total Investment Value</label>
                                                        <input type="number" class="form-control" id="TotalInvestmentValue" name="TotalInvestmentValue" required>
                                                    </div>
                                                    <!-- 
                                                        /////////////////////
                                                            INDUSTRY SECTION
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
                                                        </select>
                                                    </div>
                                                    <!-- Sector Dropdowns | Data being fed through JQuery -->
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 sector" id="ArtificialIntelligenceDrop">
                                                        <label for="Sector" class="form-label">Sector</label>
                                                        <select id="Sector" name="Sector" class="form-select">
                                                            <option>choose...</option>
                                                        </select>
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
                        <span class="col-2"> 
                            <form action="../PCExport.php" method="POST">
                                <button class="btn new-button" type="submit" name="export" formmethod="POST"> Export CSV</button>
                            </form>
                        </span>
                    </div>
                </div>
                <!-- TABLE OF ALL PORTFOLIO COMPANIES --> 
                <div class="card">
                    <div class="card-body bg-secondary">
                        <div class="table-responsive" style="overflow-x:auto;">
                            <table class=" tbl table table-hover table-striped table-success table-bordered" style="Width: 2400px; line-height: 18px;">
                                <t> 
                                    <th scope="col" >Portfolio Company Name</th>
                                    <th scope="col" >Currency </th>
                                    <th scope="col" >Portfolio Company Website</th>
                                    <th scope="col" >Total Investment Value</th>
                                    <th scope="col" >Stake</th>
                                    <th scope="col" >Details</th>
                                    <th scope="col" >Year Founded</th>
                                    <th scope="col" >Headquarters</th>
                                    <th scope="col" >Logo</th>
                                    <th scope="col">Edit </th>
                                    <th scope="col">Delete </th>
                                </t>
                                <?php
                                    while($rows = mysqli_fetch_assoc($result))
                                    {
                                ?>
                                    <tr>     
                                        <td class="text-truncate"> <small> <?php echo $rows['PortfolioCompanyName'] ?> </small></td>
                                        <td class="text-truncate"> <small> <?php echo $rows['Currency'] ?> </small></td>
                                        <td class="text-truncate"> <small> <a href="<?php echo $rows['Website'] ?>" target="_Blank">Website</a> </small></td>
                                        <td class="text-truncate"> <small> <?php echo $rows['TotalInvestmentValue'] ?> </small></td>
                                        <td class="text-truncate"> <small> <?php echo $rows['Stake'] ?> </small></td>
                                        <td class="text-truncate"> <small> <?php echo $rows['Details'] ?> </small></td>
                                        <td class="text-truncate"> <small> <?php echo $rows['YearFounded'] ?> </small></td>
                                        <td class="text-truncate"> <small> <?php echo $rows['Country'] ?> </small></td>
                                        <td class="text-truncate"> <small> <?php echo '<img src="data:image;base64,'.base64_encode($rows['Logo']).'" style="width:100px; height:60px;">'?> </small></td>
                                        <td class="text-truncate"> <small> <a href="../crud/edit_PC.php?PortfolioCompanyID=<?php echo $rows['PortfolioCompanyID']; ?> ">Edit</a></small></td>
                                        <td class="text-truncate"> <small> <a href="../crud/delete_PC.php?PortfolioCompanyID=<?php echo $rows['PortfolioCompanyID']; ?> ">Delete</a></small></td>
                                        <!-- <td> <?php echo $rows['IndustryID'] ?></td>
                                        <td> <?php echo $rows['SectorID'] ?></td> -->
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
    </body>
</html>