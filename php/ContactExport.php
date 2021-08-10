<?php 
    // EXPORTING A CSV FILE OF THE DATA
    If(isset($_POST["export"])){
        include_once('./App/connect.php');
        // QUERY DATABASE FROM DATA
        header('Content-Type:text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=data.csv');

        $output = fopen("php://output","w");
        fputcsv($output, array('UserFullName', 'FirstName', 'LastName', 'Organization(s)', 'ContactNumber1', 'ContactNumber2', 'Email', 'RoleType', 'Gender', 'Race'));
        $query = "  SELECT 
                        UserDetail.UserFullName, UserDetail.FirstName, UserDetail.LastName, GROUP_CONCAT(DISTINCT PortfolioCompanyName) AS PortfolioCompanyName, UserDetail.ContactNumber1, UserDetail.ContactNumber2, UserDetail.Email, RoleType.RoleType, Gender.Gender, Race.Race 
                    FROM 
                        UserDetail 
                    LEFT JOIN 
                        PortfolioCompanyUserDetail 
                    ON 
                        PortfolioCompanyUserDetail.UserDetailID=UserDetail.UserDetailID 
                    LEFT JOIN 
                        PortfolioCompany
                    ON 
                        PortfolioCompanyUserDetail.PortfolioCompanyID=PortfolioCompany.PortfolioCompanyID 
                    LEFT JOIN 
                        RoleType 
                    ON 
                        RoleType.RoleTypeID=UserDetail.RoleTypeID

                    LEFT JOIN 
                        Gender
                    ON
                        Gender.GenderID = UserDetail.GenderID

                    LEFT JOIN 
                        Race 
                    ON 
                        Race.RaceID =UserDetail.RaceID
                    WHERE  
                        UserDetail.Deleted = 0 
                    GROUP BY 
                        UserFullName, FirstName, LastName, ContactNumber1, ContactNumber2, Email, RoleType, Gender, Race
        ";
        $result = mysqli_query($conn, $query);

        while( $row = mysqli_fetch_assoc($result))
        {
            fputcsv($output, $row);
        }
        fclose($output);
    }
?>








