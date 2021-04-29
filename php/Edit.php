<?php 
    include_once('./connect.php');
    // QUERY DATABASE FROM DATA
    $sql=" SELECT * FROM investor "; 
    
    // $sql=" SELECT * FROM investor where id='".$InvestorID."'"; 
    $result = mysqli_query($conn, $sql) or die ( mysqli_error());
    $row = mysqli_fetch_assoc($result);
    
    $status = "";
    if(isset($_POST['new']) && $_POST['new']==1)
    {
        $InvestorID     =$_REQUEST['InvestorID'];
        $ModifiedDate   =$_REQUEST['ModifiedDate'];
        $InvestorName   =$_REQUEST['InvestorName'];
        $Website        =$_REQUEST['Website'];
        $Description    =$_REQUEST['Description'];
        $ImpactTag      =$_REQUEST['ImpactTag'];
        $YearFounded    =$_REQUEST['YearFounded'];
        $Headquarters   =$_REQUEST['Headquarters'];
        $Logo           =$_REQUEST['Logo'];

        $update="update investor set ModifiedDate='".$ModifiedDate."',InvestorName='".$InvestorName."', Website='".$Website."',Description='".$Description."', ImpactTag='".$ImpactTag."', YearFounded='".$YearFounded."', Headquarters='".$Headquarters."', Logo='".$Logo."' where InvestorID='".$InvestorID."'";
    mysqli_query($conn, $update) or die(mysqli_error());
    $status = "Record Updated Successfully. </br></br>
    <a href='investor.php'>View Updated Record</a>";
    echo '<p style="color:#FF0000;">'.$status.'</p>';
    }else {
?>
<div>
    <form name="form" method="post" action=""> 
        <input type="hidden" name="new" value="1" />
        <input name="InvestorID" type="hidden" value="<?php echo $row['InvestorID'];?>" />
        <p><input type="text" name="ModifiedDate" required value="<?php echo $row['ModifiedDate'];?>" /></p>
        <p><input type="text" name="InvestorName" placeholder="Enter InvestorName" required value="<?php echo $row['InvestorName'];?>" /></p>
        <p><input type="text" name="Website" placeholder="Enter Website" required value="<?php echo $row['Website'];?>" /></p>
        <p><input type="text" name="Description" placeholder="Enter Description" required value="<?php echo $row['Description'];?>" /></p>
        <p><input type="text" name="ImpactTag" placeholder="Enter ImpactTag" required value="<?php echo $row['ImpactTag'];?>" /></p>
        <p><input type="text" name="YearFounded" placeholder="Enter YearFounded" required value="<?php echo $row['YearFounded'];?>" /></p>
        <p><input type="text" name="Headquarters" placeholder="Enter Headquarters" required value="<?php echo $row['Headquarters'];?>" /></p>
        <p><input type="text" name="Logo" placeholder="Enter Logo" required value="<?php echo $row['Logo'];?>" /></p>

        <p><input name="submit" type="submit" value="Update" /></p>
    </form>
    <?php } ?>
</div>