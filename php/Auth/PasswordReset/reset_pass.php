<?php 
  // Include Data base connection
  include_once('../../App/connect.php');

  if($_GET['key'] && $_GET['reset'])
  {
    $Email=$_GET['key'];
    $Password=$_GET['reset'];
    
    $sql = "SELECT Email, Password FROM PlatformContributors WHERE md5(Email)='$Email' AND md5(Password)='$Password'";
    $result = mysqli_query($conn, $sql);
    echo 
      'The variable avalues are: '.$Email."<br/>"
      .$Password;
    if($result -> num_rows === 1)
    {
      ?>
      <form method="POST" action="submit_new.php">
        <input type="hidden" name="Email" value="<?php echo $Email;?>">
        <label for="Password">Enter New Password</label>
        <input type="Password" name='Password' id="Password">
        <input type="submit" name="submit_Password">
      </form>
      <?php
    } 
  }
?>