<?php 
    session_start();
    include_once('../App/connect.php');
    // QUERY DATABASE FROM DATA
    if(isset($_POST['logout'])){
        session_destroy();
        echo 
            'Successfully logged out.'
            .'<br/>' 
            .'<a href="../../index.php">Home</a>';
        header('refresh: 5; url = ../../index.php');
        exit;
    }
?>