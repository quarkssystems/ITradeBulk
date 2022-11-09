function ajaxLoad(filename, containerClass) {
    showLoader()
    console.log(containerClass);
    $.ajax({
        type: "GET",
        url: filename,
        contentType: false,
        success: function (data) {
            $(containerClass).html(data);
            $('[data-toggle="tooltip"]').tooltip();
            initiateDateSearch();
            hideLoader()
        },
        error: function (xhr, status, error) {
            alert(xhr.responseText);
            hideLoader()
        }
    });
}

function ajaxDelete(filename, token, content) {
    content = typeof content !== 'undefined' ? content : 'content';
    showLoader()
    $.ajax({
        type: 'POST',
        data: {_method: 'DELETE', _token: token},
        url: filename,
        success: function (data) {
            $('.data-grid').html(data);
            hideLoader()
        },
        error: function (xhr, status, error) {
            alert(xhr.responseText);
            hideLoader()
        }
    });
}

function showLoader() {
    $('.site-loader').show();
}

function hideLoader() {
    // $('select.select-dropdown').each(function(){
    //     if(!$(this).hasClass('selectized') && !$(this).hasClass('selectize-control'))
    //     {
    //         $(this).selectize();
    //     }
    // });
    $('.site-loader').hide();
}

function ajaxPost(postUrl, postData = {}, container) {
    showLoader();

    postData._token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        type: 'POST',
        data: postData,
        url: postUrl,
        success: function (data) {
            $(container).html(data);
            hideLoader()
        },
        error: function (xhr, status, error) {
            alert(xhr.responseText);
            hideLoader()
        }
    });
}

function initiateDateSearch()
{
    // $('.search-date-input').datepicker({
    //     format: 'dd/mm/yyyy',
    //     autoclose : true
    //
    // }).on('changeDate', function(e){
    //     var searchUrl = $(this).data('url');
    //     var inputName = $(this).data('name');
    //     searchUrl = searchUrl + inputName + '_start=' + $('.search-date-input[name="'+inputName + '_start"]').val() + '&' + inputName + '_end=' + $('.search-date-input[name="'+inputName + '_end"]').val();
    //     ajaxLoad(searchUrl);
    // });
}

jQuery(document).ready(function() {

    $(document).on('submit', 'form#frm', function (event) {
        event.preventDefault();
        var form = $(this);
        var data = new FormData($(this)[0]);
        var url = form.attr("action");
        $.ajax({
            type: form.attr('method'),
            url: url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                $('.is-invalid').removeClass('is-invalid');
                if (data.fail) {
                    for (control in data.errors) {
                        $('#' + control).addClass('is-invalid');
                        $('#error-' + control).html(data.errors[control]);
                    }
                } else {
                    ajaxLoad(data.redirect_url, '.data-grid');
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                alert("Error: " + errorThrown);
            }
        });
        return false;
    });


    // $(document).on('click', 'a.page-link', function (event) {
    //     event.preventDefault();
    //     let containerClass = $(this).parents('.pagination-wrapper').data('container');
    //     ajaxLoad($(this).attr('href'), containerClass);
    //     console.log('Parent Class: ' + containerClass);
    // });

    $(document).on('keyup', '.search-input-field', function(){
        if($.trim($(this).val()) !== '')
        {
            $(this).addClass('search-input-has-value');
        }
        else
        {
            $(this).removeClass('search-input-has-value');
        }
        return false;
    });

    $(document).on('keypress', '.search-input-field', function(e){
        if(e.keyCode == 13)
        {
            var searchUrl = $(this).data('url');
            var containerClass = $(this).data('container');
            if($(this).hasClass('search-date-input'))
            {
                var inputName = $(this).data('name');
                searchUrl = searchUrl + inputName + '_start=' + $('.search-date-input[name="'+inputName + '_start"]').val() + '&' + inputName + '_end=' + $('.search-date-input[name="'+inputName + '_end"]').val();
                ajaxLoad(searchUrl, containerClass);
            }
            else
            {
                ajaxLoad(searchUrl + $(this).val(), containerClass);
            }
            return false;
        }

    });

    $(document).on('click', '.clear-search-input-button', function(){
        console.log('clear button clicked');
        $(this).prev('.search-input-field').val('');
        let e = $.Event("keypress");
        e.keyCode = 13; // # Some key code value
        $(this).prev('.search-input-field').trigger(e);
    });

    initiateDateSearch();
});