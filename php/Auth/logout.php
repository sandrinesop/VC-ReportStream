<?php 
    session_start();
    include_once('../App/connect.php');
    // QUERY DATABASE FROM DATA
    if(isset($_POST['logout'])){
        session_destroy();
        header('refresh: 5; url = ../../index.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VC ReportStream | logout</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <style>
        /* 
        /// Header - Navbar Styles///
        */
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap');
        body {
            font-family: 'Open Sans', sans-serif;
            font-weight: 500;
        }
        body {
            background-color: #447002a9;
            background-image:url(../../resources/Logout.jpg);
            background-size: cover;
            background-position-x:center;
            background-repeat: no-repeat;
        }
        .wrapper {
            background-color:#447002a9;
        }
        h3 {
            color: #fff;
            margin-top: 25%;
            padding-top: 30px;
            padding-bottom: 30px;
        }
        p {
            color: #fff;
            padding-bottom: 30px;
        }
    </style>
</head>
<body>
    <main class="Container text-center justify-content-center wrapper">
        <h3>
            Successfully logged out
        </h3>
        <p>
            You will be redirected back to the home page shortly...
        </p>
    </main>
</body>
</html>