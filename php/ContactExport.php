<?php 
    // EXPORTING A CSV FILE OF THE DATA
    If(isset($_POST["export"])){
        include_once('./App/connect.php');
        // QUERY DATABASE FROM DATA
        header('Content-Type:text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=data.csv');

        $output = fopen("php://output","w");
        fputcsv($output, array('','UserFullName', 'FirstName', 'LastName', 'Organization(s)', 'ContactNumber1', 'ContactNumber2', 'Email', 'RoleTypeID', 'GenderID', 'RaceID'));
        $query = "  SELECT 
                        userdetail.Deleted, userdetail.UserFullName, userdetail.FirstName, userdetail.LastName, GROUP_CONCAT(DISTINCT PortfolioCompanyName) AS PortfolioCompanyName, userdetail.ContactNumber1, userdetail.ContactNumber2, userdetail.Email, RoleType.RoleType, gender.Gender, race.Race 
                    FROM 
                        userdetail 
                    LEFT JOIN 
                        PortfolioCompanyUserDetail 
                    ON 
                        PortfolioCompanyUserDetail.UserDetailID=userdetail.UserDetailID 
                    LEFT JOIN 
                        PortfolioCompany
                    ON 
                        PortfolioCompanyUserDetail.PortfolioCompanyID=PortfolioCompany.PortfolioCompanyID 
                    LEFT JOIN 
                        roletype 
                    ON 
                        roletype.RoleTypeID=userdetail.RoleTypeID

                    LEFT JOIN 
                        gender
                    ON
                        gender.GenderID = userdetail.GenderID

                    LEFT JOIN 
                        race 
                    ON 
                        race.RaceID =userdetail.RaceID
                    WHERE  
                        userdetail.Deleted = 0 
                    GROUP BY 
                    userdetail.Deleted, userdetail.UserDetailID, userdetail.UserFullName, userdetail.FirstName, userdetail.LastName, userdetail.ContactNumber1, userdetail.ContactNumber2, userdetail.Email, RoleType.RoleType, gender.Gender, race.Race
        ";
        $result = mysqli_query($conn, $query);

        while( $row = mysqli_fetch_assoc($result))
        {
            fputcsv($output, $row);
        }
        fclose($output);
    }
?>








