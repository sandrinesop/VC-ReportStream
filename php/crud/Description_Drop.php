<?php

    //Connect to our MySQL database using the PDO extension.
    include_once('../connect.php');

?>


<?php
 $Descript = "select * from Description";
 $res = mysqli_query($conn, $Descript);   
?>


<form>
  <select class="form-select">
     <?php
       while ($row = $res->fetch_assoc()) 
       {
         echo '<option value=" '.$row['Descriptionid'].' "> '.$row['Description'].' </option>';
       }
    ?>
  </select>
</form>