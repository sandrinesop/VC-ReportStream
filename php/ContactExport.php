<?php 
    // EXPORTING A CSV FILE OF THE DATA

    
    If(isset($_POST["export"])){
        include_once('./connect.php');
        // QUERY DATABASE FROM DATA
        header('Content-Type:text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=data.csv');

        $output = fopen("php://output","w");
        fputcsv($output, array('UserFullName', 'FirstName', 'LastName', 'ContactNumber1', 'ContactNumber2', 'Email', 'RoleTypeID', 'GenderID', 'RaceID'));
        $query = "SELECT UserFullName, FirstName, LastName, ContactNumber1, ContactNumber2, Email, RoleType.RoleType, Gender.Gender, Race.Race 
        FROM UserDetail
        LEFT JOIN RoleType
        ON RoleType.RoleTypeID = UserDetail.RoleTypeID
        LEFT JOIN Gender
        ON Gender.GenderID = UserDetail.GenderID
        LEFT JOIN Race
        ON Race.RaceID = UserDetail.RaceID";
        $result = mysqli_query($conn, $query);

        while( $row = mysqli_fetch_assoc($result))
        {
            fputcsv($output, $row);
        }
        fclose($output);
    }
?>








