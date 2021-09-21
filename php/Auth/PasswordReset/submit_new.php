<?php
        include_once('../../App/connect.php');

        if(isset($_POST['submit_Password']) )
        {
          $Email=$_POST['Email'];
          $Password=$_POST['Password'];
          
          $sql="UPDATE PlatformContributors SET Password='$Password' WHERE Email='$Email'";
          $result =mysqli_query($conn, $sql);
        }
?>