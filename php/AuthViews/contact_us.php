<!DOCTYPE html>
<html lang="en">
 <head>
    <title> Contact Us</title>
    <meta charset="utf8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width = device-width initial-scale=1">

    <link rel="stylesheet" href="../../css/bootstrap.min.css"> 
    <link rel="stylesheet" href="../../css/bootstrap.css"> 
    <link rel="stylesheet" href="../../css/admin.css"> 
    <link rel="stylesheet" href="../../css/contact.css"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
</head>

 <body>


    <!-- HEADER CONTENT -->
 <header class="mb-5">
            <nav class=" navbar navbar-expand-lg align-middle navbar-dark fixed-top" style="z-index: 1;">
                    <div class="container px-0">
                        <a class="navbar-brand" href="../../Admin.php"><img style=" width: 48px;" class="home-ico" src="../../resources/DCA_Admin.png" alt="Digital collective africa logo"> <small>VC ReportStream </small> </a>
                        <button class="navbar-toggler text-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                            <ul class="navbar-nav w-75 justify-content-end">
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="https://www.digitalcollective.africa/ " target="_blank" ><small>Digital Collective Africa</small> </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="../../php/AuthViews/contact_us.php"><small>Contact</small> </a>
                                </li>
                                <li class="nav-item">
                                    <form action="../../php/Auth/logout.php" method="POST"  class="profile">
                                        <input class="logout_btn" type="submit" name="logout"  value="logout" formmethod="POST">
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
            </nav>
        </header> 
         <div style="height: 10px;"></div>

        <!-- Main Welcome Screen -->
    <main class="Contact">
        <div class="content"> 
            <h2>Contact Us</h2>
        </div>

        <div class="container">
            <div class="contactInfo">
                <div class="box">
                    <div class="icon"><i class="fa fa-map-marker" aria-hidden="true"></i></div>
                    <div class="text">
                        <h3>Address</h3>
                        <p>Century Way, <br> Century City,<br>  Cape Town, 7441</p>
                    </div>
                </div>
                <div class="box">
                    <div class="icon"><i class="fa fa-phone" aria-hidden="true"></i></div>
                    <div class="text">
                        <h3>Phone</h3>
                        <p>021 551 8183  </p>
                    </div>
                </div>
                <div class="box">
                
                    <div class="icon"><i class="fa fa-envelope" aria-hidden="true"></i></div>
                    <div class="text">
                        <h3>Email</h3>
                        <p>Contact@africarena.com </p>
                    </div>
                </div>
            </div>
            <!-- <div class="contactForm" >
                <form >
                    <h2>Send Message</h2>
                    <div class="inputBox">
                        <input type="text" required="required">
                        <span>Full Name</span>
                    </div>
                    <div class="inputBox">
                        <input type="text" required="required">
                        <span>Email</span>
                    </div>
                    <div class="inputBox">
                        <textarea required="required"></textarea>
                        <span>Type your message...</span>
                    </div>
                    <div class="inputBox">
                        <input type="submit"  value="Send">
                    </div>
                </form>

            </div> -->
        </div>

</main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
 </body>
</html>