
/** btn ---> Agreed or Declined **/
$(document).on('click', '.js_reply_btn', function() {

    let modal = $('#reply_modal')
    let status = $(this).data('status');
    let text = $(this).data('text');

    let b_text = modal.find('.js_text');
    if (status == 1) {
        if (b_text.hasClass('text-danger'))
            b_text.removeClass('text-danger');

        b_text.addClass('text-success');
        b_text.html(text)

        modal.find('.js_status').val(status)
    } else {
        if (b_text.hasClass('text-success'))
            b_text.removeClass('text-success');

        b_text.addClass('text-danger');
        b_text.html(text)

        modal.find('.js_status').val(status)
    }

    modal.modal('show');
});


$('.js_copy_to_email').select2({
    width: '100%'
});


/**  ########################################################################## **/


/** registration number modal form save **/
$(document).on('submit', '.js_registration_form', function(e) {
    e.preventDefault()

    let modal = $('#registration_modal')
    let form = modal.find('.js_registration_form');
    let url = form.attr('action')

    $.ajax({
        url: url,
        type: "POST",
        data: form.serialize(),
        dataType: "JSON",
        success: (response) => {
            console.log(response)
            if(response.status)
                modal.modal('hide');
        },
        error: (response) => {
            console.log('error: ', response)
        }
    })

});



/**  ########################################################################## **/

// order status accept or decline
$(document).on('submit', '.js_order_status_form', function(e) {
    e.preventDefault()

    let form = $(this)

    $.ajax({
        url: form.attr('action'),
        type: "POST",
        dataType: "JSON",
        data: form.serialize(),
        success: (response) => {
            console.log(response)

            if (response.status)
                location.reload()
        },
        error: (response) => {
            console.log('error: ', response)
        }
    })

});


// **********************************************************************
    // Order detail edit model

// edit btn
$(document).on('click', '.js_edit_order_detail_btn', function(e) {
    e.preventDefault();

    let modal = $('#add_edit_modal')
    let order_detail_url = $(this).data('one_od_url');

    let update_url = $(this).data('update_url')
    let form = modal.find('.js_add_edit_form');
    form.attr('action', update_url);
    form.append('<input type="hidden" name="_method" value="PUT">');


    $.ajax({
        url: order_detail_url,
        type: "GET",
        dataType: "JSON",
        success: (response) => {

            if (response.status) {
               form.find('.js_name').val(response.data.name);
               form.find('.js_pcs').val(response.data.pcs);
               form.find('.js_count').val(response.data.count);
               form.find('.js_purpose').val(response.data.purpose);
               form.find('.js_comment').val(response.data.comment);
               form.find('.js_address').val(response.data.address);
               form.find('.js_capex').val(response.data.capex);
               form.find('.js_project_price').val(response.data.project_price);
               form.find('.js_installation_time').val(response.data.installation_time);
               form.find('.js_contract_number').val(response.data.contract_number);
               form.find('.js_contract_date').val(response.data.contract_date);

               modal.modal('show')
            }
            // console.log(response)
        },
        error: (response) => {
            console.log('errors: ', response);
        }
    });

});



$('#add_edit_modal button[data-dismiss="modal"]').click(function () {

    let form = $('.js_add_edit_form')

    form.find('.js_name').val('');
    form.find('.js_pcs').val('');
    form.find('.js_count').val('');
    form.find('.js_purpose').val('');
    form.find('.js_comment').val('');
    form.find('.js_address').val('');
    form.find('.js_capex').val('');
    form.find('.js_project_price').val('');
    form.find('.js_installation_time').val('');
    form.find('.js_contract_number').val('');
    form.find('.js_contract_date').val('');

    form.find('input[name="_method"]').remove();
});


// edit order detail
$(document).on('submit', '.js_add_edit_form', function(e) {
    e.preventDefault();
    let name = $(this).find('.js_name');

    $.ajax({
        url: $(this).attr('action'),
        type: "POST",
        dataType: "JSON",
        data: $(this).serialize(),
        success: (response) => {
            console.log(response)

            if (response.status)
                location.reload()
        },
        error: (response) => {
            console.log('error: ', response)
            let errors = response.responseJSON.errors;

            if (typeof errors !== 'undefined') {
                if(errors.name)
                    name.addClass('is-invalid')
            }
        }
    });

});


// add one order detail
$(document).on('click', '.js_add_order_detail_btn', function(e) {
    e.preventDefault();

    let url = $(this).data('add_url');

    let modal = $('#add_edit_modal')
    modal.find('.js_add_edit_form').attr('action', url);
    modal.modal('show');
});
