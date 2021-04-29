
<?php
    $status = "";
    if(isset($_POST['new']) && $_POST['new']==1)
    {
        $id= $_REQUEST['id'];
        $trn_date = date("Y-m-d H:i:s");
        $name = $_REQUEST['name'];
        $age = $_REQUEST['age'];
        $update="update new_record set trn_date='".$trn_date."',
        name='".$name."', age='".$age."', where id='".$id."'";

        mysqli_query($conn, $update) or die(mysqli_error());
        $status = "Record Updated Successfully.
        </br>
        </br>
        <a href='./investor.php'>View Updated Record</a>";
        echo '<p style="color:#FF0000;">'.$status.'</p>';
        }else {
        ?>
        <div>
            <form name="form" method="post" action=""> 
                <input type="hidden" name="new" value="1" />
                <input name="InvestorId" type="hidden" value="<?php echo $row['id'];?>" />
                <p><input type="text" name="name" placeholder="Enter Name" required value="<?php echo $row['name'];?>" /></p>
                <p><input type="text" name="age" placeholder="Enter Age" required value="<?php echo $row['age'];?>" /></p>
                <p><input name="submit" type="submit" value="Update" /></p>
            </form>
            <?php } ?>
        </div>


















            <!-- 
                /////////////////////
                USER DETAIL
                /////////////////////
            -->
            <!-- <div class="row">
                <h2 class="investor-h2">
                    Portfolio Company Contact Person
                </h2>
                <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                    <label for="FirstName" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="FirstName" name="FirstName" required>
                </div>
                <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                    <label for="LastName" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="LastName" name="LastName" required>
                </div>
                <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                    <label for="Email" class="form-label">Email</label>
                    <input type="text" class="form-control" id="Email" name="Email" required>
                </div> 
                <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                    <label for="ContactNumber1" class="form-label">ContactNumber1</label>
                    <input type="text" class="form-control" id="ContactNumber1" name="ContactNumber1" required>
                </div>
                <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                    <label for="ContactNumber2" class="form-label">ContactNumber2</label>
                    <input type="text" class="form-control" id="ContactNumber2" name="ContactNumber2">
                </div>
                <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                    <label for="RoleType" class="form-label">RoleType</label>
                    <select name="RoleType" class="form-select" id="RoleType" required>
                        <option value="" selected >Choose...</option>
                        <option value="President">President</option>
                        <option value="CEO">CEO</option>
                        <option value="CFO">CFO</option>
                        <option value="COO">COO</option>
                    </select>
                </div>
                <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                    <label for="Gender" class="form-label">Gender</label>
                    <select name="Gender" class="form-select" id="Gender" required>
                        <option value="" selected >Choose...</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Unknown">Unknown</option>
                    </select>
                </div>
                <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                    <label for="Race" class="form-label">Race</label>
                    <select name="Race" class="form-select" id="Race" required>
                        <option value="" selected >Choose...</option>
                        <option value="Black">Black</option>
                        <option value="White">White</option>
                        <option value="Asian">Asian</option>
                        <option value="Indian">Indian</option>
                        <option value="Unknown">Unknown</option>
                    </select>
                </div>
            </div> -->