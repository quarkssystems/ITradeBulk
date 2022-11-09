$(document).ready(function($) {
    $('.callPopover').popover({
        container: 'body',
        html: true,
        placement: 'bottom',
    });
});


/**********************************Order Status***********************************************************/
$(document).on('change', '.orderpayment_status', function(){


    /* let selectedstatus = $(".orderpayment_status option:selected").val();
      let selectednext = $(".orderpayment_status option:selected").next().val();
      
      if (typeof selectednext !== "undefined") {*/
      
          /*  $('.orderpayment_status')
            .find('option')
            .remove()
            .end()
            .append('<option value="'+selectedstatus+'">'+selectedstatus+'</option>')
            .append('<option value="'+selectednext+'">'+selectednext+'</option>')
            ;*/
       //}
     

    let order_status = $(this).val();
    let order_id = $(this).data('id');
    let ajaxUrl = $(this).data('ajax-url');
    let wrapperClass = $(this).data('area-holder');
    if(order_status != '--SELECT ORDER STATUS--')
    {
        if((order_status != 'ACCEPT ORDER') && (order_status != 'PACKED')){

            swal({
                title: "Order Status Change",
                text: "Would you like to change order status to "+ order_status +"?",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, change it!",
                closeOnConfirm: true
            },
            function(inputValue){

                if (inputValue===false) {
                    location.reload();
                } else {
                // showAdminLoader();
                    $('.waitProcess').show();
                    $('.waitProcess').text('Please wait....');
                    $.ajax({
                        type: 'POST',
                        data: { _token: TOKEN, order_id: order_id, order_status: order_status},
                        url: ajaxUrl,
                        success: function (data) {
                            $('#'+wrapperClass).html(data);
                            hideAdminLoader(); 
                            $('.waitProcess').hide();
                            $('.waitProcess').text('');
                            location.reload();                   
                        },
                        error: function (xhr, status, error) {
                            hideAdminLoader();
                            $('.waitProcess').hide();
                            $('.waitProcess').text('');
                            alert(xhr.responseText);
                        }    
                    });
                }
                

            });
        }else {
            if(order_status == 'ACCEPT ORDER'){
                $('.statusAcceptOrder').show();
            }
            else if(order_status == 'PACKED'){
                $('.statusChooseDispatcher').show();
            }
            else{
                $('.statusChoosePicker').show();
            } 
        }
    } 
});

function showAdminLoader() {
    $(".site-loader").show();
}

function hideAdminLoader() {
    $(".site-loader").hide();
}

/**********************************finished Order Status***********************************************************/
$('.suggestions').click(function() {
    $('.suggestions').hide();
  });
  $('.suggestions').hide();
// $(".searchBox").focus(function() {
//     console.log('in');
//     // suggestions
// }).blur(function() {
//     $(".suggestions").focus(function() {
//         console.log('in');
//         // suggestions
//     }).blur(function() {
//         console.log(1);
//             $('.suggestions').hide();
//     });
// });



/**********************************************Vehical Form comman admin and frontend*******************************************************************/

/*$(".orderpayment_status option").each(function()
    {


      let selectedstatus = $(".orderpayment_status option:selected").val();
      let selectednext = $(".orderpayment_status option:selected").next().val();
       if (typeof selectednext !== "undefined") {
         $('.orderpayment_status')
            .find('option')
            .remove()
            .end()
            .append('<option value="'+selectedstatus+'">'+selectedstatus+'</option>')
            .append('<option value="'+selectednext+'">'+selectednext+'</option>')
            ;
        }    
    });*/

$(document).on('change', '.transport_type', function(){

    let wrapperClass = $(this).data('area-holder');
    let  transport_type = $(this).val();
      showLoader();
    let ajaxUrl = $(this).data('ajax-url');
    let viewFile = $(this).data('view-file');
     $.ajax({
        type: 'POST',
        data: { _token: TOKEN, transport_type: transport_type, view_file: viewFile},
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

$(document).on('change', '#vehicle_type', function(){
    
    let vehicle_capacity_id = $("#vehicle_type option:selected").text();
    let ajaxUrl = $(this).data('ajax-url');
    if(vehicle_capacity_id !== 'Select Vechile Type'){
        
        $.ajax({
        type: 'POST',
        data: { _token: TOKEN,  transport_capacity : vehicle_capacity_id},
        url: ajaxUrl,
        success: function (data1) {
           var data = $.parseJSON(data1);
       
            $('.pallet_capacity_class').val(data.pallet_capacity_standard);
            $('.capacity_class').val(data.capacity);
        },
        error: function (xhr, status, error) {
            alert(xhr.responseText);
          }
        });
    }
 });
$(document).on('change', '.load-capacity-on-change', function(){
    
    let wrapperClass = $(this).data('area-holder');
    let ajaxUrl = $(this).data('ajax-url');
    let ajaxUrl_pallet = $(this).data('ajax-url-pallet');
    let vehicle_capacity_id = $(this).val();
    let viewFile = $(this).data('view-file');
     $('.pallet_capacity_class').val(0);
     $('.capacity_class').val(0);
 
    showLoader();

    $.ajax({
        type: 'POST',
        data: { _token: TOKEN, vehicle_capacity_id: vehicle_capacity_id, view_file: viewFile},
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

});

$(document).ready(function(){

   //$(".transport_vehicle").trigger('change');

   var strType = $(".transport_vehicle").val();
   if(strType != 'Truck') {
    if(strType == 'Truck with trailer') {
        console.log('123123123:');
        $(".withtrailer").show();
         $(".truckwithtrailer").show();

    } else {
        $("#vehicle_capacity_id").parents(".col-lg-4").hide();
        $(".withtrailer").hide();
        $(".truckwithtrailer").hide();
    } 
} else {
    if(strType == 'Truck') {
        $(".truckwithtrailer").show();
        $("#vehicle_capacity_id").parents(".col-lg-4").show();
        $(".withtrailer").hide();
    }
}
    
});
$(".transport_vehicle").change(function(){
     $('.pallet_capacity_class').val(0);
      $('.capacity_class').val(0);
    var strType = $(".transport_vehicle").val();
    console.log('strType:',strType,':123');
    if(strType != 'Truck') {
        if(strType == 'Truck with trailer') {
            console.log('123123123:');
            $(".withtrailer").show();
             $(".truckwithtrailer").show();

        } else {
            $("#vehicle_capacity_id").parents(".col-lg-4").hide();
            $(".withtrailer").hide();
            $(".truckwithtrailer").hide();
        } 
    } else {
        if(strType == 'Truck') {
            $(".truckwithtrailer").show();
            $("#vehicle_capacity_id").parents(".col-lg-4").show();
            $(".withtrailer").hide();
        }
    }
});


/**********************************************role 'company' form admin and frontend***********************************************/
$(document).on('change', '#logistic_company_id', function(){ // get log nad lat of company when company driver add 

    let company_uuid = $(this).val();
    let ajaxUrl = $(this).data('ajax-url');
  
    $.ajax({
        type: 'POST',
        data: { _token: TOKEN,company_uuid: company_uuid},
        url: ajaxUrl,
        success: function (data) {
          var data1 = $.parseJSON(data);
           $('#default_latitude').val(data1.latitude);
           $('#default_longitude').val(data1.longitude);
          
        },
        error: function (xhr, status, error) {
          
            alert(xhr.responseText);
          
        }
    });
 });

$(document).on('click', '#view-Tran', function(){ 
    let amt = $(this).data('amt');
    let remarks = $(this).data('remarks');
    let receipt = $(this).data('receipt');
    let transaction_type = $(this).data('transaction_type');
    let status = $(this).data('status');
    $('#m_amt').html(amt);
    $('#m_remarks').html(remarks);
    $('#m_receipt').html(receipt);
    $('#m_transaction_type').html(transaction_type);
     $('#m_status').html(status);


    $('#viewTransModal').modal('show');
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


    $('#starttime').timepicker({
        timeFormat: 'HH:mm',
        dynamic: false,
        dropdown: true,
        scrollbar: true
        
    });

    $('#endtime').timepicker({
        timeFormat: 'HH:mm',
        dynamic: false,
        dropdown: true,
        scrollbar: true
        
    });


});



/**********************************************rname & slug***********************************************/
function updateSlug(name) {
    var slug = document.getElementById('slug');
    slug.value = name.value.toLowerCase().replace('[','').replace(']','').replace('_','-').replace(' ','-');
  }