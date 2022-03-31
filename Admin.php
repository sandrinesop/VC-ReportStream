<?php
    // ======================================
    // STARTING A NEW SESSION FOR EACH USER
    // ======================================
    session_start();
    // NOW WE SET A CONDITION TO PREVENT UNAUTHORISED USERS TO ACCESS THIS PAGE.
    if( $_SESSION == []){
        header('refresh:5; url = ./index.php');
        echo'
            <p> 
                Access denied. Only Admins can access this page. <br/>
                <small>You are being redirected back to the home page.</small>
            </p>
        ';
        exit; 
    }
?>

<!DOCTYPE html>
<html lang="en">
    <!-- Head -->
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="./resources/DCA_Icon.png" type="image/x-icon">
        <title> VC Reportstream | Admin </title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
        <link rel="stylesheet" href="./css/bootstrap.min.css">
        <link rel="stylesheet" href="./css/bootstrap.css">
        <link rel="stylesheet" href="./css/admin.css">
        </head>
    <!-- Body -->
    <body class="index-body">
        <!-- Header Content -->
        <header class="mb-5">
            <nav class=" navbar navbar-expand-lg align-middle navbar-dark fixed-top" style="z-index: 1;">
                    <div class="container px-0">
                        <a class="navbar-brand" href="./Admin.php"><img style=" width: 48px;" class="home-ico" src="./resources/DCA_Admin.png" alt="Digital collective africa logo"> <small>VC ReportStream </small> </a>
                        <button class="navbar-toggler text-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                            <ul class="navbar-nav w-75 justify-content-end">
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="https://www.digitalcollective.africa/ " target="_blank" ><small>Digital Collective Africa</small> </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="./php/AuthViews/contact_us.php"><small>Contact</small> </a>
                                </li>
                                <li class="nav-item">
                                    <form action="./php/Auth/logout.php" method="POST"  class="profile">
                                        <input class="logout_btn" type="submit" name="logout"  value="logout" formmethod="POST">
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
            </nav>
        </header> 
        <div style="height: 20px;"></div>
        <!-- Main Welcome Screen -->
        <main class="container text-center admin-main">
            <div class="wrapper ">
                <div>
                    <a href="./php/AuthViews/NewDeals.php">
                        <div class=" Deals centered-nav">
                            <h1>Deals</h1>
                            <div>
                                <img src="./resources/Deals.png" alt="">
                            </div>
                        </div>
                    </a>
                    <a href="./php/AuthViews/investor.php">
                        <div class=" Investors centered-nav">
                            <h1>Investment Managers</h1>
                            <div>
                                <img src="./resources/Investor.png" alt="">
                            </div>
                        </div>
                    </a>
                </div>
                <div>
                    
                    <a href="./php/AuthViews/fund.php">
                        <div class=" Funds centered-nav">
                            <h1>Funds</h1>
                            <div>
                                <img src="./resources/Funds.png" alt="">
                            </div>
                        </div>
                    </a>
                </div>
                <div>
                    <a href="./php/AuthViews/portfolio-company.php">
                        <div class=" Portfolio centered-nav">
                            <h1>Portfolio Companies</h1>
                            <div>
                                <img src="./resources/portfolio-company.png" alt="">
                            </div>
                        </div>
                    </a>
                    <a href="./php/AuthViews/contacts.php">
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

        <footer class="footer">   
                <p class="copyright"> © 2022 VC ReportStream. All rights reserved.</p>
        </footer>
       <!-- JavaScript Scripts -->
       <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    </body>
</html>