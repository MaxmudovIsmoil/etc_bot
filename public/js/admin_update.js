
/** registration number modal form save **/
$(document).on('submit', '.js_admin_update_form', function(e) {
    e.preventDefault()

    let modal = $('#admin_update_modal')
    let form = $(this)
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
            let errors = response.responseJSON.errors;
            let email = form.find('.js_email');
            let username = form.find('.js_username');
            let password = form.find('.js_password');

            if (typeof errors !== 'undefined') {
                if(errors.email) {
                    email.addClass('is-invalid')
                    email.siblings('.invalid-feedback').html(errors.email)
                }
                if(errors.email) {
                    email.addClass('is-invalid')
                    email.siblings('.invalid-feedback').html(errors.email)
                }

                if(errors.username) {
                    username.addClass('is-invalid')
                    username.siblings('.invalid-feedback').html(errors.username)
                }

                if(errors.password) {
                    password.addClass('is-invalid')
                    password.siblings('.invalid-feedback').html(errors.password)
                }
            }
        }
    })

});
