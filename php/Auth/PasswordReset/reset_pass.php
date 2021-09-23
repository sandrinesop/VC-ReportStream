<?php 
  // Include Data base connection
  include_once('../../App/connect.php');

  if($_GET['key'] && $_GET['reset'])
  {
    // These are the encoded variables sent through
    $EncodedEmail =$_GET['key'];
    $EncodedPassword =$_GET['reset'];

    // Decode the variables for use
    $DecodedEmail = base64_decode($EncodedEmail);
    $DecodedPassword = base64_decode($EncodedPassword);

    $sql = "SELECT Email, Password FROM PlatformContributors WHERE Email='$DecodedEmail' AND  Password = '$DecodedPassword' "; 
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    // catch the error if any using the mysqli_error function
    if($result){
      $Email = $row['Email'];
      // echo 
      //     'Query success! ' .'<br/>'
      //     .$row['Email'] .'<br/>'
      //     .$row['Password']
      //     ;
    }else{
      echo 'there was an error: '.mysqli_error($conn);
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../../../resources/DCA_Icon.png" type="image/x-icon">
    <title> New Password </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/bootstrap.css">
    <link rel="stylesheet" href="../../../css/main.css">
  </head>
  <body class="p-2">
    <?php 
      if($result -> num_rows === 1)
      {
        ?>
        <form method="POST" action="submit_new.php" >
          <input type="hidden" name="Email" value="<?php echo $Email;?>">
          <label for="Password">Enter New Password</label>
          <br>
          <input type="Password" name='Password' id="Password">
          <input type="submit" name="submit_Password">
        </form>
        <?php
      } 
    ?>
  </body>
</html>