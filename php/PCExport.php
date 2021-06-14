<?php 
    // EXPORTING A CSV FILE OF THE DATA
    If(isset($_POST["export"])){
        include_once('./connect.php');
        // QUERY DATABASE FROM DATA
        header('Content-Type:text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=data.csv');

        $output = fopen("php://output","w");
        fputcsv($output, array('PortfolioCompanyName','Website','TotalInvestmentValue','Stake','Details','Year Founded','Headquarters','Logo'));
        $query = "  SELECT PortfolioCompanyName,Website,TotalInvestmentValue,Stake,Details,YearFounded, country.country,Logo 
                    FROM 
                        PortfolioCompany
                    LEFT JOIN 
                        country 
                    ON 
                        country.CountryID = portfoliocompany.Headquarters 
                    ORDER BY 
                        PortfolioCompanyName DESC";
        $result = mysqli_query($conn, $query);

        while( $row = mysqli_fetch_assoc($result))
        {
            fputcsv($output, $row);
        }
            fclose($output);
        }
?>