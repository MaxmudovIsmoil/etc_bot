
// file upload
$(document).on('submit', '.js_file_form', function(e) {
    e.preventDefault()

    let url = $(this).attr('action');
    let form_data = new FormData(this);

    $.ajax({
        url: url,
        type: "POST",
        data: form_data,
        contentType: false,
        cache: false,
        processData: false,
        success: (response) => {
            // console.log(response)

            if(response.status) {

                let tbody = $('.js_file_tbody');

                if(tbody.html() == '') {
                    let new_tr = order_file_crate_new_tr(response.data.id, response.data.file, 1);
                    tbody.html(new_tr);
                }
                else {
                    let number = tbody.find('.js_this_tr').length + 1;
                    let new_tr = order_file_crate_new_tr(response.data.id, response.data.file, number);
                    tbody.append(new_tr);
                }
                $(this).find('input[name="file"]').val('');

            }

        },
        error: (response) => {
            console.log('error: ', response)
        }
    })

});


function order_file_crate_new_tr(id, file, number)
{
    let file_path = window.location.protocol + "//" + window.location.host + '/file_uploaded/' + file;
    let file_delete_path = window.location.protocol + "//" + window.location.host + '/order-file/delete';

    let new_tr = '<tr class="js_this_tr">'+
                    '<td align="center">' + number + '</td>'+
                    '<td>' + file + '</td>'+
                    '<td class="text-center">'+
                        '<a href="' + file_path + '" target="_blank" class="btn btn-primary js_file_download_bt">'+
                            '<i class="fa-solid fa-file-arrow-down"></i>'+
                        '</a>'+
                        '<a class="btn btn-danger js_file_delete_btn" data-url="' + file_delete_path + '" data-id="' + id + '">'+
                            '<i class="fa-solid fa-trash"></i>'+
                        '</a>'+
                    '</td>'+
                '</tr>';

    return new_tr;
}

/**  ########################################################################## **/

// delete
$(document).on('click', '.js_file_delete_btn', function(e) {
    e.preventDefault()

    let url = $(this).data('url')
    let id = $(this).data('id')
    let token = $('#js_meta').attr('content')

    $.ajax({
        url: url,
        type: "POST",
        dataType: "JSON",
        data: { '_token': token, 'id': id },
        success: (response) => {

            if (response.status) {
                $(this).closest('.js_this_tr').remove();

                let this_trs = $('.js_this_tr');
                this_trs.each(function (item, tr) {
                    item++;
                    $(tr).find('td:first-child').html(item);
                });
            }
            else {
                console.log(response)
            }
        },
        error: (response) => {
            console.log('error: ', response)
        }
    })

});
