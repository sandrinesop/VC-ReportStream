<!DOCTYPE html>
<html lang="en">
    
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="./resources/DCA_Icon.png" type="image/x-icon">
        <title> VC Reportstream | Home </title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
       
        <!-- <link rel="stylesheet" href="./css/main.css"> -->
        <link rel="stylesheet" href="./css/index.css">
        <link rel="stylesheet" href="./css/navbar.css"> 
        </head>
    <!-- Body -->
    <body class="index-body">
        <!-- Header Content --> 
        <?php include('./php/UnAuthViews/navBar/indexNav.php') ?>
        <div style="height: 10px;"></div>
        <!-- Main Welcome Screen -->
        <main class="container-fluid text-center index-main">
            <div class="wrapper ">
                <div>
                    <a href="./php/UnAuthViews/DealsView.php">
                        <div class=" Deals centered-nav">
                            <h1>Deals</h1>
                            <div>
                                <img src="./resources/Deals.png" alt="">
                            </div>
                        </div>
                    </a>
                    <a href="./php/UnAuthViews/InvestorsView.php">
                        <div class=" Investors centered-nav">
                            <h1>Investment Managers</h1>
                            <div>
                                <img src="./resources/Investor.png" alt="">
                            </div>
                        </div>
                    </a>
                </div>
                <div>
                    
                    <a href="./php/UnAuthViews/FundsView.php">
                        <div class=" Funds centered-nav">
                            <h1>Funds</h1>
                            <div>
                                <img src="./resources/Funds.png" alt="">
                            </div>
                        </div>
                    </a>
                </div>
                <div>
                    <a href="./php/UnAuthViews/CompaniesView.php">
                        <div class=" Portfolio centered-nav">
                            <h1>Portfolio Companies</h1>
                            <div>
                                <img src="./resources/portfolio-company.png" alt="">
                            </div>
                        </div>
                    </a>
                    <a href="./php/UnAuthViews/ContactsView.php">
                        <div class=" Funds centered-nav">
                            <h1>Contacts</h1>
                            <div>
                                <img style="width: 80px;" src="./resources/contact.svg" alt="">
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </main>
       <!-- JavaScript Scripts -->
       <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    </body>
</html>