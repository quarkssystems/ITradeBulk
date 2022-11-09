$(function () {
    

    
    
    let cartItemCount = 0;
    const bodySelector = $('body');
    const inputNumber = $('.input-number');
    $('[data-toggle="tooltip"]').tooltip()
    // $('.add-to-cart').on('click', function () {
    //     cartItemCount++;
    //     swal({
    //         title: "Product added to cart",
    //         text: "Total " + cartItemCount + " product" + (cartItemCount > 1 ? 's' : '') + " in cart. \nClick on cart icon for go to cart page.",
    //         type: "success",
    //         showCancelButton: false,
    //         confirmButtonClass: "btn-success",
    //         confirmButtonText: "Ok",
    //         closeOnConfirm: true
    //     });
    //
    //     $('.topCartItemCount').text(cartItemCount);
    // });

    $(".sidebarMenu").load("/frontend/ajax/get-menu-categories");
    $('.product_sidebar_filter').click(function(){
        $('.br_category_sidebar_inner>div').slideToggle()
    });

    $(document).on("click", '.callMenuAjax', function(e) {
        e.preventDefault();
        $('.sidebarMenu').html("<li class='loading'></li>");
        cat_id = $(this).attr('cat_id'); 
        $(".sidebarMenu").load("/frontend/ajax/get-menu-categories/"+cat_id);
    });
    
    $('.mm-main-cat').click(function() {
        id = $(this).attr('id');
        $('.mm-main-cat').removeClass('selected');
        $('.mega-menu-slides').removeClass('first-mm-slide');
        $('#'+id).addClass('selected');
        $('.'+id).addClass('first-mm-slide');
    });

    
    
    $( "#topHeaderNav ul" ).each(function( index ) {
        $('.extra_menu_items').append($(this).html());
    });

    $('.mmenu_icon, .close-menu ').click(function(e){
        e.preventDefault();
        $('.m_cat_menu').toggleClass('dissh');
        $('body').toggleClass('noscroll');
        $('.close-menu').toggle();
        $('.setHtmlOverlay').fadeToggle();
    });

    $('.mmenu_icon').click(function(e){
        e.preventDefault();
        $('.sidebarMenu').html("<li class='loading'></li>");
        $(".sidebarMenu").load("/frontend/ajax/get-menu-categories");
    });

    $('.mega-menu-btn').click(function(){
        $('.mega-menu').slideToggle();
        $('.mega-menu-btn').toggleClass('selected');
    });

    $('.payment-order-button').click(function(){
        $(this).html('Processing ...');
        $(this).addClass('loading');
        $(this).attr('disabled', 'disabled');
    });

    $(document).on("click", '.buildURL', function(e) { 
        e.preventDefault(); 
        bid = $(this).attr('bid');
        val = getParameterByName(bid);
        currentval = $(this).attr('aval');
        if(val !== null) {
            newval = val+'|'+currentval; 
            newURL = UpdateQueryString(bid, newval);
        } else {
            newval = currentval;
            url = window.location.href;            
            url = url.replace("/detail/manufacturer", "");
            url = url.replace("/detail/supplier", "");
            url = url.replace("/detail/category", "");
            if(!url.includes("?")) {
                newURL = url+'?'+bid+'='+newval;
            } else {
                newURL = url+'&'+bid+'='+newval;
            }
        } 
        window.location.replace(newURL);
    });


    $('.image-zoom')
    .wrap('<span style="display:inline-block"></span>')
    .css('display', 'block')
    .parent()
    .zoom({
    url: $(this).find('img').attr('data-zoom')
    });

    // $('.product_details_btn').click(function(){
    //     id = $(this).attr('id');
    //     $('.'+id).show();
    // });
    // $('.product_details .close').click(function(){
    //     $('.product_details').hide();
    // });
 

    $('#searchproduct').on('click keyup', function () {

        let wrapperClass = $(this).data('product-holder');
        let ajaxUrl = $(this).data('ajax-url');
        //let viewFile = $(this).data('view-file');
        let productname = $(this).val();
        if (productname) {
            showLoader();
            $.ajax({
                type: 'POST',
                data: {_token: TOKEN, name: productname},
                url: ajaxUrl,
                success: function (data) {
                    $('.' + wrapperClass).show();
                    $('.' + wrapperClass).html(data);
                    //$('.' + wrapperClass + ' select').trigger('change');
                    hideLoader()
                },
                error: function (xhr, status, error) {
                    alert(xhr.responseText);
                    hideLoader()
                }
            });        
        }
     });
 
     $('#searchcategory').on('keyup', function () {

        let wrapperClass = $(this).data('cat-holder');
        let ajaxUrl = $(this).data('ajax-url');
        //let viewFile = $(this).data('view-file');
        let cat_name = $(this).val();
        if (cat_name) {
            showLoader();
            $.ajax({
                type: 'POST',
                data: {_token: TOKEN, name: cat_name},
                url: ajaxUrl,
                success: function (data) {
                
                    $('.' + wrapperClass).html(data);
                    //$('.' + wrapperClass + ' select').trigger('change');
                    hideLoader()
                },
                error: function (xhr, status, error) {
                    alert(xhr.responseText);
                    hideLoader()
                }
            });
        }
     });

 

      $('#searchbrand').on('keyup', function () {

        let wrapperClass = $(this).data('brand-holder');
        let ajaxUrl = $(this).data('ajax-url');
        //let viewFile = $(this).data('view-file');
        let name = $(this).val();
        if (name) {    
            showLoader();
            $.ajax({
                type: 'POST',
                data: {_token: TOKEN, name: name},
                url: ajaxUrl,
                success: function (data) {
                
                    $('.' + wrapperClass).html(data);
                    //$('.' + wrapperClass + ' select').trigger('change');
                    hideLoader()
                },
                error: function (xhr, status, error) {
                    alert(xhr.responseText);
                    hideLoader()
                }
            });
        }
     });
      $('#searchsupplier').on('keyup', function () {

        let wrapperClass = $(this).data('supplier-holder');
        let ajaxUrl = $(this).data('ajax-url');
        //let viewFile = $(this).data('view-file');
        let name = $(this).val();
        if (name) {    
            showLoader();
            $.ajax({
                type: 'POST',
                data: {_token: TOKEN, name: name},
                url: ajaxUrl,
                success: function (data) {
                
                    $('.' + wrapperClass).html(data);
                    //$('.' + wrapperClass + ' select').trigger('change');
                    hideLoader()
                },
                error: function (xhr, status, error) {
                    alert(xhr.responseText);
                    hideLoader()
                }
            });
        }
     });



    $(document).mouseup(function(e) 
    {
        var container = $(".suggestions");
        var container2 = $("#searchproduct");
        // if the target of the click isn't the container nor a descendant of the container
        if ((!container.is(e.target) && container.has(e.target).length === 0) || (!container2.is(e.target) && container2.has(e.target).length === 0))
        {
            container.hide();
        }
    });

    bodySelector.on('click', '.linkval', function () {
        $('#searchproduct').val($(this).data('id'));
        //alert($(this).data('id'));
    });

    bodySelector.on('click', '#seemore', function () {
        $('.allcategory').show();
        //alert($(this).data('id'));
    });


    

    $('.delete-cart-item').on('click', function () {

        $(this).parents('tr').remove();
        swal({
            title: "Product deleted from cart",
            // text: "Total " + cartItemCount + " product" + (cartItemCount > 1 ? 's' : '') + " in cart.",
            text: "",
            type: "success",
            showCancelButton: false,
            confirmButtonClass: "btn-success",
            confirmButtonText: "Ok",
            closeOnConfirm: true
        });
    });

    $('.btn-number').click(function (e) {
        e.preventDefault();

        fieldName = $(this).attr('data-field');
        type = $(this).attr('data-type');
        var input = $("." + fieldName);
        var currentVal = parseInt(input.val());
        if (!isNaN(currentVal)) {
            if (type === 'minus') {

                if (currentVal > input.attr('min')) {
                    input.val(currentVal - 1).change();
                }
                if (parseInt(input.val()) === input.attr('min')) {
                    $(this).attr('disabled', true);
                }

            } else if (type === 'plus') {

                if (currentVal < input.attr('max')) {
                    input.val(currentVal + 1).change();
                }
                if (parseInt(input.val()) === input.attr('max')) {
                    $(this).attr('disabled', true);
                }

            }
        } else {
            input.val(0);
        }

        // let newQty = input.val();
        // let price = input.parents('tr').find('td.cart-single-price').data('single-price');
        // price = parseInt(price);
        // let totalPrice = price * parseInt(newQty);
        // input.parents('tr').find('td.cart-single-price').data('single-price');
    });
    inputNumber.focusin(function () {
        $(this).data('oldValue', $(this).val());
    });
    inputNumber.change(function () {

        minValue = parseInt($(this).attr('min'));
        maxValue = parseInt($(this).attr('max'));
        valueCurrent = parseInt($(this).val());

        let name = $(this).attr('name');
        if (valueCurrent >= minValue) {
            $(".btn-number[data-type='minus'][data-field='" + name + "']").removeAttr('disabled')
        } else {
            $('.cartMessage').show();
            $('.cartMessage').html('Sorry, the minimum value was reached.');
            setTimeout(function(){ $('.cartMessage').hide(); }, 2000);
            $(this).val($(this).data('oldValue'));
        }
        if (valueCurrent <= maxValue) {
            $(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled')
        } else {
            $('.cartMessage').show();
            $('.cartMessage').html('Sorry, the maximum value was reached.');
            setTimeout(function(){ $('.cartMessage').hode(); }, 2000);
            $(this).val($(this).data('oldValue'));
        }


    });
    inputNumber.keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
            // Allow: Ctrl+A
            (e.keyCode === 65 && e.ctrlKey === true) ||
            // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

    bodySelector.on('change', '.load-states-on-change', function () {
        let wrapperClass = $(this).data('state-holder');
        let ajaxUrl = $(this).data('ajax-url');
        let viewFile = $(this).data('view-file');
        let country_id = $(this).val();
        showLoader();

        $.ajax({
            type: 'POST',
            data: {_token: TOKEN, country_id: country_id, view_file: viewFile},
            url: ajaxUrl,
            success: function (data) {
                $('.' + wrapperClass).replaceWith(data);
                $('.' + wrapperClass + ' select').trigger('change');
                hideLoader()
            },
            error: function (xhr, status, error) {
                alert(xhr.responseText);
                hideLoader()
            }
        });
        console.log(wrapperClass);
    });




    bodySelector.on('change', '.load-cities-on-change', function () {
        let wrapperClass = $(this).data('city-holder');
        let ajaxUrl = $(this).data('ajax-url');
        let state_id = $(this).val();
        let viewFile = $(this).data('view-file');
        showLoader();

        $.ajax({
            type: 'POST',
            data: {_token: TOKEN, state_id: state_id, view_file: viewFile},
            url: ajaxUrl,
            success: function (data) {
                $('.' + wrapperClass).replaceWith(data);
                $('.' + wrapperClass + ' select').trigger('change');
                hideLoader()
            },
            error: function (xhr, status, error) {
                alert(xhr.responseText);
                hideLoader()
            }
        });
        console.log(wrapperClass);
    });

    bodySelector.on('change', '.load-areas-on-change', function () {
        let wrapperClass = $(this).data('area-holder');
        let ajaxUrl = $(this).data('ajax-url');
        let city_id = $(this).val();
        let viewFile = $(this).data('view-file');
        showLoader();

        $.ajax({
            type: 'POST',
            data: {_token: TOKEN, city_id: city_id, view_file: viewFile},
            url: ajaxUrl,
            success: function (data) {
                $('.' + wrapperClass).replaceWith(data);
                hideLoader()
            },
            error: function (xhr, status, error) {
                alert(xhr.responseText);
                hideLoader()
            }
        });
        console.log(wrapperClass);
    });

    bodySelector.on('change', '.logistic-type-input', function () {
        let inputType = $(this).val();

        if (inputType === 'COMPANY') {
            $('.transporter_name_input').show();
           
        }
        else {
            $('.transporter_name_input').hide();
           
        }
    });

    

    bodySelector.on('click', '.add', function () {
        if ($(this).prev().val() < 100) {
            $(this).prev().val(+$(this).prev().val() + 1);
        }
    });
    bodySelector.on('click', '.sub', function () {
        if ($(this).next().val() > 0) {
            if ($(this).next().val() > 0) $(this).next().val(+$(this).next().val() - 1  );
        }
    });

    bodySelector.on('click', '.ajax-modal', function () {
        let dataurl = $(this).attr('data-url');
        let dataproduct = $(this).attr('data-product');
        // AJAX request
        $.ajax({
            url: dataurl,
            type: 'get',
            data: {product_id: dataproduct},
            success: function (response) {
                // Add response in Modal body
                $('.modal-content').html(response);

                // Display Modal
                $('#ajax-modal').modal('show');
            }
        });
    });

    $(document).on("click",".popover-external-html", function(e){
      let productid = $(this).attr('data-product');
        $(productid).submit();
    });

 
   bodySelector.on('click', '.supplier-offers-apply', function () {
        let pcodeBtn = $(this).attr("id");
        let dataurl = $(this).data('ajax-url');
        let offerval = $(this).data('offer-amount');
        let total_amount = $(this).data('total-amount');
        let tax_amount = $(this).data('tax-amount');
        let payble_amount = $(this).data('payble-amount');
        let delivery_price = $(this).data('delivery-amount');
        let payble_amount_with_delivery = $(this).data('payble-amount');

        
        let supplier_id = $(this).data('supplier-id');
        let pcode = $("#txtcode_" + supplier_id).val();
        $("#promocode-error-"+supplier_id).html('');
        if ($("#"+pcodeBtn).val() == "CLEAR") {
            $("#"+pcodeBtn).val("Apply");
            $(".strike-price-total-"+supplier_id).html(payble_amount);
            $(".offerwithtotal"+supplier_id).html("");
            $("#txtcode_" + supplier_id).val("");
           
            $("#tabledetail"+supplier_id).find(".finaltotal").html(payble_amount);
            $("#tabledetail"+supplier_id).find(".finaltotal_with_delivery").html(parseFloat(payble_amount) + parseFloat(delivery_price));
            $(this).data('offer-amount', 0);
            $("#checkout"+supplier_id).data("offer-price", 0);
            $("#checkout"+supplier_id).data("offer-id", "");
            $("#checkout"+supplier_id).data("amt-payble", payble_amount);
            $("#checkout"+supplier_id).data("item-tax", tax_amount);
            $("#supplierProductDetail"+supplier_id+" .discountPrice").html("0");
            $("#supplierProductDetail"+supplier_id+" .payblePrice").html(parseFloat(payble_amount) + parseFloat(delivery_price));
        } else if (pcode) {
           if ($("#promocode-error-"+supplier_id).hasClass('hidden')) {
                $("#promocode-error-"+supplier_id).removeClass('hidden');
           }
           $("#txtcode_" + supplier_id).css('border', 'none');
            // AJAX request
            $.ajax({
                url: dataurl,
                type: 'post',
                dataType: "json",
                data: {_token: TOKEN ,supplierid: supplier_id ,promocode:  pcode, total_amount: payble_amount},
                success: function (response) {
                    if(response.status == 1){
                        $(".offerwithtotal"+supplier_id).html("");
                        $("#promocode-error-"+supplier_id).html('<strong>Promo Code is already used.</strong> ' + response.data);
                        $("#txtcode_" + supplier_id).css('border', '2px solid red');
                        $("#promocode-error-"+supplier_id).removeClass('hidden');
                    } else if (response.status == 0){
                        $(".offerwithtotal"+supplier_id).html("");
                        $("#promocode-error-"+supplier_id).html('<strong>Promo Code is not valid.</strong> ' + response.data);
                        $("#txtcode_" + supplier_id).css('border', '2px solid red');
                        $("#promocode-error-"+supplier_id).removeClass('hidden');
                    } else {
                        var offerval = response.offerAmount;
                        var amountAfterDiscount = response.amountAfterDiscount;
                        $(".strike-price-total-"+supplier_id).html('<strike>' + payble_amount + '</strike>');
                        $(".offerwithtotal"+supplier_id).html(amountAfterDiscount.toFixed(2));
                        
                        var sumNetPay = parseFloat(amountAfterDiscount) + parseFloat(delivery_price);
                        sumNetPay = sumNetPay.toFixed(2);
                        $("#tabledetail"+supplier_id).find(".finaltotal").html(amountAfterDiscount.toFixed(2));
                        $("#tabledetail"+supplier_id).find(".finaltotal_with_delivery").html(sumNetPay);
                        
                        
                        $("#"+pcodeBtn).val("CLEAR");
                        $(this).data('offer-amount', offerval);
                        $("#checkout"+supplier_id).data("offer-price",offerval);
                        $("#checkout"+supplier_id).data("offer-id", response.status);
                        $("#checkout"+supplier_id).data("amt-payble", amountAfterDiscount.toFixed(2));
                        $("#checkout"+supplier_id).data("item-tax", tax_amount);
                        $("#supplierProductDetail"+supplier_id+" .discountPrice").html(offerval);
                        $("#supplierProductDetail"+supplier_id+" .payblePrice").html((payble_amount-offerval).toFixed(2));
                        $("#supplierProductDetail"+supplier_id+" .payblePricewithdelivery").html(((payble_amount+delivery_price)-offerval).toFixed(2));
                        
                    }
                }
            });
       } else {
            $(".offerwithtotal"+supplier_id).html("");
            $("#promocode-error-"+supplier_id).html('<strong>Promo Code field is required.</strong>');
            $("#promocode-error-"+supplier_id).removeClass('hidden');
            $("#txtcode_" + supplier_id).css('border', '2px solid red');
       }
    });

   
    // $('#popover').popover({
    //     html : true,
    //     title: function() {
    //         return $("#popover-head").html();
    //     },
    //     content: function() {
    //         return $("#popover-content").html();
    //     }
    // });

   /* $('.popover-external-html').on("click", function(e){
        $('.popover-external-html').not(this).popover('hide');
    });

    $('.popover-external-html').popover({
        html : true,
        // trigger: "focus",
        placement: 'top',
        title: function() {
            let headerClass=$(this).data('header-class');
            return $(headerClass).html();
        },
        content: function() {
            let contentClass=$(this).data('content-class');
            console.log(contentClass);
            return $(contentClass).html();
        }
    });

*/
     bodySelector.on("click", ".bank_details_btn", function(){
        $('#bankDetailModal').modal('show');
    });

      bodySelector.on("submit", "#bankbranchform", function(e){
                
                 e.preventDefault();
                 $(".errorclass").hide();
                 $(".errorclass").html('');
                 let ajaxUrl = $(this).attr('action');
              //  let ajaxUrl = $(this).data('ajax-url');
                $.ajax({
                    type: 'POST',
                    data:  $('#bankbranchform').serialize(),
                    url: ajaxUrl,
                    success: function (response) {
                    // $(".errorclass").html(response.errors);
                        if(response.uuid){
                            $('#bankDetailModal').modal('hide');
                           
                            $('#bankgrid').load("/frontend/ajax/refresh-bankbranch/"+response.uuid);
                            //$('#bank_branch_id_'+response.uuid).prop("checked", true);
                            //alert(response.uuid);
                           // $('#bank_branch_id_791be6dc-3b8d-4e7a-9cd9-52c7938ee86e').prop("checked", true); 
                           // setTimeout(function(){ $('#bank_branch_id_'+response.uuid).prop("checked", true);   }, 1000);
                           
                        }
                      $.each(response.errors, function( index, value ) {
                          $(".errorclass").show();  
                          $(".errorclass").append(response.errors[index]);
                          $(".errorclass").append('<br>');
                         });

                    },
                   
                });

          });


       bodySelector.on("click", ".bank_btn", function(){
        $('#bankModal').modal('show');
    });
    
    bodySelector.on("submit", "#bankfrmodel", function(e){
                
                 e.preventDefault();
                 $(".errorclass").hide();
                 $(".errorclass").html('');
                 let ajaxUrl = $(this).attr('action');
              //  let ajaxUrl = $(this).data('ajax-url');
                $.ajax({
                    type: 'POST',
                    data:  $('#bankfrmodel').serialize(),
                    url: ajaxUrl,
                    success: function (response) {
                    // $(".errorclass").html(response.errors);
                        if(response.uuid){
                            $('#bankModal').modal('hide');
                           
                        }
                      $.each(response.errors, function( index, value ) {
                          $(".errorclass").show();  
                          $(".errorclass").append(response.errors[index]);
                          $(".errorclass").append('<br>');
                         });

                    },
                   
                });

          });



    bodySelector.on("click", ".selectDeliveryTypeModalButton", function(){
        let deliveryPrice = "+ R" + $(this).data("delivery-price");
        let deliveryVehicle = " - " + $(this).data("delivery-vehicle");
        let approxDistance = " - " + $(this).data("distance");
        let supplierId = $(this).data("supplier-id");

        let productTotal = $(this).data('total-price');
        let shippingTotal = $(this).data('delivery-price');
        let offerPrice = $(this).data('offer-price');
        let offerId = $(this).data('offer-id');
        let paybleAmount = $(this).data('amt-payble');
        let itemTax = $(this).data('item-tax');
        let palletsCapacity = $(this).data('pallets-capacity');
        let total_weight = $(this).data('total-weight');
        let total_distance = $(this).data("distance");
        
        $('.checkout-delivery-charge').text(deliveryPrice);
        $('.checkout-delivery-vehicle').text(deliveryVehicle);
        $('.checkout-delivery-approx-distance').text(approxDistance);
        $('.product-total-input').val(productTotal);
        $('.shipping-total-input').val(shippingTotal);
        
        $('.total-distance').val(total_distance);
        $('.total-weight').val(total_weight);
        $('.offer-total-input').val(offerPrice);
        $('.offer-id-input').val(offerId);
        $('.supplier-id-input').val(supplierId);
        $('.paybel-amt-input').val(paybleAmount);
        $('.item-tax-input').val(itemTax);
        $('.checkout-delivery-pallet').val(palletsCapacity);
        $('#selectDeliveryTypeModal').modal("show");
    });
});

function showLoader() {
    $(".site-loader").show();
}

function hideLoader() {
    $(".site-loader").hide();
}

function numbericOnly(evt) {
    let e = evt || window.event;
    let key = e.keyCode || e.which;

    if (!e.shiftKey && !e.altKey && !e.ctrlKey &&
        // numbers
        key >= 48 && key <= 57 ||
        // Numeric keypad
        key >= 96 && key <= 105 ||
        key === 8 || key === 9 || key === 13 ||
        // Home and End
        key === 35 || key === 36 ||
        // left and right arrows
        key === 37 || key === 39 ||
        // Del and Ins
        key === 46 || key === 45)
    {
    }
else
    {
        // input is INVALID resgg
        e.returnValue = false;
        if (e.preventDefault) e.preventDefault();
    }
}

$(document).on('click', '.delete-item', function(){
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

$(document).on('keypress keyup blur','.allownumericwithoutdecimal',function(e) {
        $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
    });

    $(document).ready(function(){
      $("#startdate").datepicker({
        startDate: new Date(),
        format: 'yyyy-mm-dd',
        todayBtn:  1,
        autoclose: true,
    }).on('changeDate', function (selected) {
     
        var minDate = new Date(selected.date.valueOf());
        $('#enddate').datepicker('setStartDate', minDate);
    });
    
    $("#enddate").datepicker({
        format: 'yyyy-mm-dd',
    }).on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $('#startdate').datepicker('setEndDate', minDate);
        });


    $('.product_tabs ul.tabs li').click(function(){
        var tab_id = $(this).attr('data-tab');

        $('.product_tabs ul.tabs li').removeClass('current');
        $('.product_tabs .tab-content').removeClass('current');

        $(this).addClass('current');
        $("#"+tab_id).addClass('current');
    });

    $('.owl-carousel').owlCarousel({
                items: 6,
                nav: false,
                dots: true,
                mouseDrag: false,
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 1
                    },
                    480: {
                        items: 3
                    },
                    769: {
                        items: 4
                    },
                    991:{
                        items: 6

                    }
                }
            });




});



function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    url = url.replace("/detail/manufacturer", "");
    url = url.replace("/detail/supplier", "");
    url = url.replace("/detail/category", "");
    name = name.replace(/[\[\]]/g, '\\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
}
function UpdateQueryString(key, value, url) {
    if (!url) url = window.location.href;
    url = window.location.href;
    url = url.replace("/detail/manufacturer", "");
    url = url.replace("/detail/supplier", "");
    url = url.replace("/detail/category", "");

    var re = new RegExp("([?&])" + key + "=.*?(&|#|$)(.*)", "gi"),
        hash;

    if (re.test(url)) {
        if (typeof value !== 'undefined' && value !== null) {
            return url.replace(re, '$1' + key + "=" + value + '$2$3');
        } 
        else {
            hash = url.split('#');
            url = hash[0].replace(re, '$1$3').replace(/(&|\?)$/, '');
            if (typeof hash[1] !== 'undefined' && hash[1] !== null) {
                url += '#' + hash[1];
            }
            return url;
        }
    }
    else {
        if (typeof value !== 'undefined' && value !== null) {
            var separator = url.indexOf('?') !== -1 ? '&' : '?';
            hash = url.split('#');
            url = hash[0] + separator + key + '=' + value;
            if (typeof hash[1] !== 'undefined' && hash[1] !== null) {
                url += '#' + hash[1];
            }
            return url;
        }
        else {
            return url;
        }
    }
}