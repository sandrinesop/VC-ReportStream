<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>African VC Database | Form</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
        <link rel="stylesheet" href="./css/main.css">
    </head>
    <body class="pb-5">
        <!-- HEADER CONTENT -->
        <nav class="container navbar navbar-expand-lg align-middle" style="z-index: 1;">
            <div class="container-fluid">
                <a style="color:#ffffff;" class="navbar-brand" href="./index.php"><img style=" width: 80px;" class="home-ico" src="./resources/DCA_Icon.png" alt="Digital collective africa logo"> DCA Deal Database </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="./index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./WebInterface.php">New Deal</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Contact</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav> 
        <!-- BODY CONTENT -->
        <form class="container" action="./php/insert.php" method="POST" enctype="multipart/form-data">
            <!-- 
                /////////////////////
                FUND SECTION
                /////////////////////
             -->
             <div class="row">
                <h2 class="fund-h2">
                    Fund Section
                </h2>
                <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                    <label for="FundName" class="form-label">Fund Name</label>
                        <input type="FundName" class="form-control" id="FundName" aria-describedby="FundName" name="FundName" required>
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
        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.6.0.slim.js" integrity="sha256-HwWONEZrpuoh951cQD1ov2HUK5zA5DwJ1DNUXaM6FsY=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
        <script src="./js/scripts.js"></script>
    </body>
</html>