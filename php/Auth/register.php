
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="../../resources/DCA_Icon.png" type="image/x-icon">
        <title>VC Reportstream | Fund </title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
        <link rel="stylesheet" href="../../css/select2.min.css">
        <link rel="stylesheet" href="../../css/bootstrap.min.css">
        <link rel="stylesheet" href="../../css/bootstrap.css">
        <link rel="stylesheet" href="../../css/main.css">
        <link rel="stylesheet" href="../../DataTables/datatables.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
        <!-- OVERWRITING THE STYLING OF THE PLUGIN -->
        <style>
            .dataTables_wrapper,
            .dataTables_length,
            .dataTables_wrapper,
            .dataTables_filter,
            .dataTables_wrapper,
            .dataTables_info,
            .dataTables_wrapper,
            .dataTables_processing,
            .dataTables_wrapper,
            .dataTables_paginate,
            .dataTables_paginate #table_investmentManager_previous,
            .dataTables_paginate #table_investmentManager_next {
                color: #ffffff !important;
            }
        </style>
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
                            <a class="nav-link" href="#">Contact</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav> 
        <!-- BODY CONTENT -->
        <main class="container my-5 px-5 login-main" >
            <div class="card container p-0">
                <div class="card-header text-center">Sign Up</div>
                <div class="card-body">
                    <form class="container" id="login" action="./process_registration.php" method="POST">
                        <div class="mb-3 FirstName-container">
                            <label for="Name" class=" col-form-label">First Name</label>
                            <div class="FirstName">
                                <input required type="text" class="form-control" id="FirstName" placeholder="Enter first name" minlength="3" name="FirstName">
                            </div>
                            <hr >
                        </div>
                        <div class="mb-3 LastName-container">
                            <label for="Name" class=" col-form-label">Last Name</label>
                            <div class="LastName">
                                <input required type="text" class="form-control" id="LastName" placeholder="Enter last name"  name="LastName">
                            </div>
                            <hr >
                        </div>
                        <div class="mb-3 email-container">
                            <label for="Email" class=" col-form-label">Email</label>
                            <div class="username">
                                <input required type="email" class="form-control" id="Email" placeholder="Enter Email"  name="Email">
                            </div>
                            <hr >
                        </div>
                        <div class="mb-3">
                            <label for="password" class=" col-form-label">Create Password</label>
                            <div class="userpassword">
                               <input required type="password" class="form-control" id="password" placeholder="Enter Password"  name="Password1">
                            </div>
                            <hr >
                        </div>
                        <div class="mb-3">
                            <label for="password2" class=" col-form-label">Confirm Password</label>
                            <div class="userpassword">
                               <input required type="password" class="form-control" id="password2" placeholder="Confirm password" name="Password2">
                            </div>
                            <hr >
                        </div>
                        <button type="submit"  formmethod="POST" class="register-btn" name="register">Register</button>
                        <button class=" login-btn"><a href="./login.php">Sign in</a></button>
                    </form>
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