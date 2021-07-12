
    // ==============================================================
    // =============== MULTI-SELECT USING Select2.JS ================
    // ==============================================================

    // This initializes the Plugin to allow multiple selects on various fields
    
    $(".UserFullName").select2({
        width: '230px', 
        maximumSelectionLength: 4,
        placeholder: "Select Contact(s)"
    })

    $(".InvestorName").select2({
        width: '230px', 
        maximumSelectionLength: 4,
        placeholder: "Select Investment Manager(s)"
    })

    $(".FundName").select2({
        width: '230px', 
        maximumSelectionLength: 4,
        placeholder: "Select Fund(s)"
    })

    $(".PortfolioCompanyName").select2({
        width: '230px', 
        maximumSelectionLength: 4,
        placeholder: "Select Portfolio Company"
    })

    $(".InvestmentStage").select2({
        width: '230px', 
        maximumSelectionLength: 4,
        placeholder: "Select InvestmentStage(s)"
    })