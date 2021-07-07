
    // ==============================================================
    // =============== MULTI-SELECT USING Select2.JS ================
    // ==============================================================

    // This initializes the Plugin to allow multiple selects on various fields
    
    $(".Dropdowns").select2({
        width: '250px', 
        maximumSelectionLength: 8,
        placeholder: "Select Sector"
    })