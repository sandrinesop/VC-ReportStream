<?php 
    // EXPORTING A CSV FILE OF THE DATA
    If(isset($_POST["export"])){
        include_once('./App/connect.php');
        // QUERY DATABASE FROM DATA
        header('Content-Type:text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=data.csv');

        $output = fopen("php://output","w");
        fputcsv($output, array('Deleted','PortfolioCompanyName','Investor(s)','Fund(s)','Currency','Website','Industry','Sector','TotalInvestmentValue','Stake','Details','Year Founded','Headquarters','CEO','CEO Gender','CEO Race'));
        $query = "  SELECT DISTINCT
                         portfoliocompany.Deleted, PortfolioCompany.PortfolioCompanyName, GROUP_CONCAT(DISTINCT InvestorName) AS InvestorName, GROUP_CONCAT(DISTINCT FundName) AS FundName, currency.Currency, portfoliocompany.Website, GROUP_CONCAT(DISTINCT Industry) AS Industry, GROUP_CONCAT(DISTINCT Sector) AS Sector, portfoliocompany.TotalInvestmentValue, portfoliocompany.Stake, portfoliocompany.Details, portfoliocompany.YearFounded, country.Country, UserDetail.UserFullName, gender.Gender, race.Race
                    FROM 
                        portfoliocompany 
                    LEFT JOIN 
                        InvestorPortfolioCompany 
                    ON 
                        InvestorPortfolioCompany.PortfolioCompanyID = PortfolioCompany.PortfolioCompanyID
                    LEFT JOIN 
                        Investor 
                    ON 
                        Investor.InvestorID = InvestorPortfolioCompany.InvestorID 
                    LEFT JOIN 
                        FundPortfolioCompany 
                    ON 
                        FundPortfolioCompany.PortfolioCompanyID = PortfolioCompany.PortfolioCompanyID
                    LEFT JOIN 
                        Fund 
                    ON 
                        Fund.FundID = FundPortfolioCompany.FundID 
                    LEFT JOIN 
                        currency 
                    ON 
                        currency.CurrencyID = portfoliocompany.CurrencyID 
                    LEFT JOIN 
                        country 
                    ON 
                        country.CountryID = portfoliocompany.Headquarters 
                    LEFT JOIN 
                        PortfolioCompanyIndustry 
                    ON 
                        PortfolioCompanyIndustry.PortfolioCompanyID = PortfolioCompany.PortfolioCompanyID
                    LEFT JOIN 
                        Industry 
                    ON 
                        Industry.IndustryID = PortfolioCompanyIndustry.IndustryID
                    LEFT JOIN 
                        PortfolioCompanySector
                    ON 
                        PortfolioCompanySector.PortfolioCompanyID = portfoliocompany.PortfolioCompanyID
                    LEFT JOIN 
                        Sector 
                    ON 
                        Sector.SectorID = PortfolioCompanySector.SectorID
                    LEFT JOIN 
                        PortfolioCompanyUserDetail
                    ON 
                        PortfolioCompanyUserDetail.portfoliocompanyID = PortfolioCompany.PortfolioCompanyID
                    LEFT JOIN 
                        UserDetail
                    ON 
                        UserDetail.UserDetailID = PortfolioCompanyUserDetail.UserDetailID
                    LEFT JOIN 
                        RoleType
                    ON 
                        RoleType.RoleTypeID = UserDetail.RoleTypeID
                    LEFT JOIN 
                        gender
                    ON
                        gender.GenderID = userdetail.GenderID
                    LEFT JOIN 
                        race 
                    ON 
                        race.RaceID =userdetail.RaceID
                    WHERE 
                        portfoliocompany.Deleted = 0
                        
                    GROUP BY portfoliocompany.Deleted, PortfolioCompany.PortfolioCompanyName, currency.Currency, portfoliocompany.Website, portfoliocompany.TotalInvestmentValue, portfoliocompany.Stake, portfoliocompany.Details, portfoliocompany.YearFounded, country.Country, UserDetail.UserFullName, gender.Gender, race.Race
        ";
        $result = mysqli_query($conn, $query);

        while( $row = mysqli_fetch_assoc($result))
        {
            fputcsv($output, $row);
        }
            fclose($output);
        }
?>