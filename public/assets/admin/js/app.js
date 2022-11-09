$(document).ready(function() {
    const liquidWeightMultiplyFactor = 95;
    const bodySelector = $('body');
    $(":input").inputmask();
    bodySelector.on('submit', 'form', function () {
        $(":input").inputmask('remove');
        $(this).find('button[type="submit"]').text('Processing...');
        $(this).find('button[type="submit"]').prop('disabled', true);
        $(this).find('input[type="submit"]').val('Processing...');
        $(this).find('input[type="submit"]').prop('disabled', true);
    });

    bodySelector.on('click', '.select-deselect-roles', function(){
        if($(this).hasClass('deselect-all'))
        {
            $(this).parents('.permissions-container').find('.form-check-input').prop('checked', false);
            $(this).removeClass('deselect-all');
            $(this).text('Select all');
        }
        else
        {
            $(this).parents('.permissions-container').find('.form-check-input').prop('checked', true);
            $(this).addClass('deselect-all');
            $(this).text('Deselect all');
        }
    });

    bodySelector.on('change', '.load-states-on-change', function(){
        let wrapperClass = $(this).data('state-holder');
        let ajaxUrl = $(this).data('ajax-url');
        let viewFile = $(this).data('view-file');
        let country_id = $(this).val();
        showLoader();

        $.ajax({
            type: 'POST',
            data: { _token: TOKEN, country_id: country_id, view_file: viewFile},
            url: ajaxUrl,
            success: function (data) {
                $('.'+wrapperClass).replaceWith(data);
                $('.'+wrapperClass+' select').trigger('change');
                hideLoader()
            },
            error: function (xhr, status, error) {
                alert(xhr.responseText);
                hideLoader()
            }
        });
        console.log(wrapperClass);
    });

    bodySelector.on('change', '.load-cities-on-change', function(){
        let wrapperClass = $(this).data('city-holder');
        let ajaxUrl = $(this).data('ajax-url');
        let state_id = $(this).val();
        let viewFile = $(this).data('view-file');
        showLoader();

        $.ajax({
            type: 'POST',
            data: { _token: TOKEN, state_id: state_id, view_file: viewFile},
            url: ajaxUrl,
            success: function (data) {
                $('.'+wrapperClass).replaceWith(data);
                $('.'+wrapperClass+' select').trigger('change');
                hideLoader()
            },
            error: function (xhr, status, error) {
                alert(xhr.responseText);
                hideLoader()
            }
        });
        console.log(wrapperClass);
    });

    bodySelector.on('change', '.load-areas-on-change', function(){
        let wrapperClass = $(this).data('area-holder');
        let ajaxUrl = $(this).data('ajax-url');
        let city_id = $(this).val();
        let viewFile = $(this).data('view-file');
        showLoader();

        $.ajax({
            type: 'POST',
            data: { _token: TOKEN, city_id: city_id, view_file: viewFile},
            url: ajaxUrl,
            success: function (data) {
                $('.'+wrapperClass).replaceWith(data);
                hideLoader()
            },
            error: function (xhr, status, error) {
                alert(xhr.responseText);
                hideLoader()
            }
        });
        console.log(wrapperClass);
    });

    

    bodySelector.on('click', '.delete-item', function(){
        let ele = $(this);
        swal({
                title: "Are you sure?",
                text: "Your will not be able to recover this data!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            },
            function(){
                let formId = ele.data('form-id');
                $(formId).submit();
                swal("Processing...", "Please wait", "warning");
            });
    });
    bodySelector.on('change', '.select-corresponding-input', function(){
        let dataCorresponding = $(this).data('corresponding-input');
        $('.' + dataCorresponding).prop('checked', true);
    });

    //quick_action_selection_data_count
    bodySelector.on('change', '.quick_action_item_select', function(){
        let totalCount = $('.quick_action_item_select:checked').length;
        let countText = '';
        if(totalCount > 0)
        {
            countText = 'Total ' + totalCount + (totalCount > 1 ? ' items' : ' item') +' selected';
        }
        $('.quick_action_selection_data_count').text(countText);
    });

    bodySelector.on('click', '.select_all_quick_action_item', function(){
        if($(this).attr('data-selected') === 'all')
        {
            $('.quick_action_item_select').prop('checked', true);
            $('.select_all_quick_action_item').attr('data-selected', 'not-all');
            $('.select_all_quick_action_item small i').text('Deselect all');
        }
        else
        {
            $('.quick_action_item_select').prop('checked', false);
            $('.select_all_quick_action_item').attr('data-selected', 'all');
            $('.select_all_quick_action_item small i').text('Select all');
        }
        $('.quick_action_item_select:first').trigger('change');
    });

    bodySelector.on('click', '.submit-quick-action-event', function(){
        let thisEle = $(this);
        let actionEvent = $('.quick_action_event_select').val();
        let errorMessage = '';
        if($.trim(actionEvent)==='') {
            errorMessage = "Please select action";
        }
        if($('.quick_action_item_select:checked').length === 0)
        {
            errorMessage = "Please select one product for action";
        }
        if(errorMessage !== '')
        {
            swal({
                title: errorMessage,
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Ok",
                closeOnConfirm: true
            });
        }
        else
        {
            swal({
                    title: "Are you sure?",
                    text: "You want to delete selected items?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes",
                    closeOnConfirm: false
                },
                function(){
                    let ajaxUrl = thisEle.data('url');
                    let items = $('.quick_action_item_select:checked').map(function () {
                        return $(this).val()
                    }).get();
                    console.log(ajaxUrl);
                    console.log(items);
                    $.ajax({
                        type: 'POST',
                        data: { _token: TOKEN, items: items, actionEvent: actionEvent},
                        url: ajaxUrl,
                        success: function (data) {
                            window.location.reload(true);
                        },
                        error: function (xhr, status, error) {
                            alert(xhr.responseText);
                        }
                    });
                    swal("Processing...", "Please wait", "warning");
                });
        }
    });

   


    bodySelector.on('change', '.product_unit_name', function(){
        let unitValue = $(this).val();
        let finalWeight = $('.product_unit_value').val();
        switch (unitValue) {
            case "Weight|kg":
                    finalWeight = finalWeight * 1;
                break;

                case "Weight|gm":
                    finalWeight = finalWeight / 1000;
                break;

                case "Volume|liters":
                    finalWeight = finalWeight * (liquidWeightMultiplyFactor / 100);
                break;

                case "Volume|litre":
                    finalWeight = finalWeight * (liquidWeightMultiplyFactor / 100);
                break;

                case "Volume|ml":
                    finalWeight = (finalWeight * (liquidWeightMultiplyFactor / 100)) / 1000;
                break;
            default:
                break;

        }
        $('.product-single-weight-input').val(parseFloat(finalWeight).toFixed(2)).trigger('keyup');
    });
    bodySelector.on('keyup', '.product_unit_value', function(){
        $('.product_unit_name').trigger('change');
    });

    // $('.table').on('keyup', '.product-single-weight-input', function () {
    //     $('.product-shrink-qty-input').trigger('keyup');
    //     $('.product-case-qty-input').trigger('keyup');
    //     $('.product-pallet-qty-input').trigger('keyup');
    // });

    $('.table').on('keyup', '.product-shrink-qty-input', function () {
        let singleWeight = parseFloat($('.product-single-weight-input').val());
        console.log(singleWeight);
        let singleQtyInShrink = parseInt($(this).val());
        let shrinkWeight = singleWeight * singleQtyInShrink;
        if(isNaN(shrinkWeight))
        {
            shrinkWeight = "";
        }
        $('.product-shrink-weight-input').val(shrinkWeight);
    });

    $('.table').on('keyup', '.product-case-qty-input', function () {
        let singleWeight;
        if($('.case-product-bundle-of-input').val() === "single" )
        {
            singleWeight = parseFloat($('.product-single-weight-input').val());
        }
        else
        {
            singleWeight = parseFloat($('.product-shrink-weight-input').val());
        }

        console.log(singleWeight);
        let singleQtyInShrink = parseInt($(this).val());
        let shrinkWeight = singleWeight * singleQtyInShrink;
        if(isNaN(shrinkWeight))
        {
            shrinkWeight = "";
        }
        $('.product-case-weight-input').val(shrinkWeight);
        $('.product-pallet-qty-input').trigger("keyup");
    });

    $('.table').on('keyup', '.product-pallet-qty-input', function () {

        let singleWeight;
        if($('.pallet-product-bundle-of-input').val() === "single" )
        {
            singleWeight = parseFloat($('.product-single-weight-input').val());
            console.log("first: " + singleWeight);
        }
        else if($('.pallet-product-bundle-of-input').val() === "shrink" )
        {
            singleWeight = parseFloat($('.product-shrink-weight-input').val());
            console.log("second: " + singleWeight);
        }
        else
        {
            singleWeight = parseFloat($('.product-case-weight-input').val());
            console.log("third: " + singleWeight);
        }

        console.log(singleWeight);
        let singleQtyInShrink = parseInt($(this).val());
        let shrinkWeight = singleWeight * singleQtyInShrink;
        if(isNaN(shrinkWeight))
        {
            shrinkWeight = "";
        }
        $('.product-pallet-weight-input').val(shrinkWeight);
    });

    $('.table').on('change', '.case-product-bundle-of-input', function () {
       $('.product-case-qty-input').trigger("keyup");
    });
    $('.table').on('change', '.pallet-product-bundle-of-input', function () {
       $('.product-pallet-qty-input').trigger("keyup");
    });

    bodySelector.on("keyup", '.shrink_single_qty', function(){
        let weight = $('.product-single-weight-input').val();
        let qty = $(this).val();
        let totalWeight = weight * qty;
        $('.product-shrink-weight-input').val(totalWeight);
    });

    bodySelector.on("keyup", '.case_single_qty', function(){
        $('.case_shrink_qty').val('0');
        let weight = $('.product-single-weight-input').val();
        let qty = $(this).val();
        let totalWeight = weight * qty;
        $('.product-case-weight-input').val(totalWeight);
    });

    bodySelector.on("keyup", '.case_shrink_qty', function(){
        $('.case_single_qty').val('0');
        let weight = $('.product-shrink-weight-input').val();
        let qty = $(this).val();
        let totalWeight = weight * qty;
        $('.product-case-weight-input').val(totalWeight);
    });

    bodySelector.on("keyup", '.pallet_single_qty', function(){
        $('.pallet_shrink_qty').val('0');
        $('.pallet_case_qty').val('0');
        let weight = $('.product-single-weight-input').val();
        let qty = $(this).val();
        let totalWeight = weight * qty;
        $('.product-pallet-weight-input').val(totalWeight);
    });

    bodySelector.on("keyup", '.pallet_shrink_qty', function(){
        $('.pallet_single_qty').val('0');
        $('.pallet_case_qty').val('0');
        let weight = $('.product-shrink-weight-input').val();
        let qty = $(this).val();
        let totalWeight = weight * qty;
        $('.product-pallet-weight-input').val(totalWeight);
    });

    bodySelector.on("keyup", '.pallet_case_qty', function(){
        $('.pallet_single_qty').val('0');
        $('.pallet_shrink_qty').val('0');
        let weight = $('.product-case-weight-input').val();
        let qty = $(this).val();
        let totalWeight = weight * qty;
        $('.product-pallet-weight-input').val(totalWeight);
    });

    bodySelector.on("keyup", ".product-single-weight-input", function(){
        calculateProductWeight();
    });

     bodySelector.on('change', '.logistic-type-input', function(){
        let inputType = $(this).val();
        if(inputType === 'COMPANY')
        {
            $('.transporter_name_input').show();
        }
        else
        {
            $('.transporter_name_input').hide();
        }
    });



    // Numeric only control handler
    jQuery.fn.ForceNumericOnly = function()
    {
        return this.each(function()
        {
            $(this).keydown(function(e)
            {
                var key = e.charCode || e.keyCode || 0;
                // allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
                // home, end, period, and numpad decimal
                return (
                    key === 8 ||
                    key === 9 ||
                    key === 13 ||
                    key === 46 ||
                    key === 110 ||
                    key === 190 ||
                    (key >= 35 && key <= 40) ||
                    (key >= 48 && key <= 57) ||
                    (key >= 96 && key <= 105));
            });
        });
    };

    $(".numericInput").ForceNumericOnly();

});

/*$(document).live('change', '#logistic_company_id', function(){
    let inputType = $(this).val();
    alert(inputType);
    if(inputType == 'new_company')
    {

        $('.new_company_input').show();
    }
    else
    {
        $('.new_company_input').hide();
    }
});*/



function calculateProductWeight()
{
    let singleWeight = $(".product-single-weight-input").val();

    let shrinkSingleQty = $(".shrink_single_qty").val();
    let shrinkWeight = singleWeight * shrinkSingleQty;

    $(".product-shrink-weight-input").val(shrinkWeight);

    let caseSingleQty = $(".case_single_qty").val();
    let caseShrinkQty = $(".case_shrink_qty").val();
    let caseWeight = 0;
    if(caseSingleQty > 0)
    {
        caseWeight = singleWeight * caseSingleQty;
    }
    if(caseShrinkQty > 0)
    {
        caseWeight = shrinkWeight * caseShrinkQty;
    }
    $(".product-case-weight-input").val(caseWeight);

    let palletSingleQty = $(".pallet_single_qty").val();
    let palletShrinkQty = $(".pallet_shrink_qty").val();
    let palletCaseQty = $(".pallet_case_qty").val();
    let palletWeight = 0;
    if(palletSingleQty > 0)
    {
        palletWeight = singleWeight * palletSingleQty;
    }
    if(palletShrinkQty > 0)
    {
        palletWeight = singleWeight * palletShrinkQty;
    }
    if(palletCaseQty > 0)
    {
        palletWeight = singleWeight * palletShrinkQty;
    }
    $(".product-pallet-weight-input").val(palletWeight);


}
$(document).ready(function() {

    let inputType = $("input[name='logistic_type']:checked"). val();
    
        if(inputType === 'COMPANY')
        {
            $('.transporter_name_input').show();
        }
        else
        {
            $('.transporter_name_input').hide();
        }
});