
function create_table_report(order, lang)
{
    let tr = '', status = '', count = 0, pcs = 0, capex = 0;

    if (lang == 'en')
        status = 'In the making';
    else (lang == 'ru')
        status = 'В процессе';

    for(let i = 0; i < order.length; i++) {

        count = (order[i].count) ? order[i].count : 0;
        pcs   = (order[i].pcs) ? order[i].pcs : 0;
        capex = (order[i].capex) ? order[i].capex : 0;

        tr += '<tr>'+
                '<td>'+order[i].order_id+'</td>'+
                '<td>'+order[i].id+'</td>'+
                '<td>'+order[i].name+'</td>'+
                '<td>'+order[i].email+'</td>'+
                '<td>'+date_time_format(order[i].created_at)+'</td>'+
                '<td>'+count+'</td>'+
                '<td>'+pcs+'</td>'+
                '<td>'+status+'</td>'+
                '<td>'+capex+'</td>'+
            '</tr>';
    }

    let tbody = $('.js_report_tbody');

    tbody.html(tr)
}


function date_time_format(date) {
    let d = new Date(date);
    let minut = d.getMinutes();
    let hour = d.getHours();
    let day = d.getDate();
    let month = d.getMonth() + 1;
    let year = d.getFullYear();

    if (minut < 10)
        minut = "0" + minut;

    if (hour < 10)
        hour = "0" + hour;

    if (day < 10)
        day = "0" + day;

    if (month < 10)
        month = "0" + month;


    return day + "." + month + "." + year + " " + hour + ":" + minut;
};




// report search form
$(document).on('click', '.js_report_form_btn', function(e) {
    e.preventDefault()

    let form = $(this).closest('.js_report_form')
    let url = form.attr('action')

    $.ajax({
        url: url,
        type: "POST",
        dataType: "JSON",
        data: form.serialize(),
        success: (response) => {
            console.log(response)

            if(response.status)
                create_table_report(response.data.order, response.data.lang)

        },
        error: (response) => {
            console.log('error: ', response)
        }
    })

});


// report table download btn
// $(document).on('click', '.js_report_export_excel_btn', function(e) {
//     e.preventDefault()

//     let table = $('#js_report_table').html();
//     let url = $(this).attr('href')
//     let token = $('#js_meta').attr('content')

//     $.ajax({
//         url: url,
//         type: "POST",
//         dataType: "JSON",
//         data: {'_token': token, 'table': table},
//         success: (response) => {
//             console.log(response)

//         },
//         error: (response) => {
//             console.log('error: ', response)
//         }
//     })

// });




function exportTableToExcel(tableID, filename = '') {
    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById(tableID);
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

    // Specify file name
    filename = filename ? filename + '.xls' : 'report_data.xls';

    // Create download link element
    downloadLink = document.createElement("a");

    document.body.appendChild(downloadLink);

    if (navigator.msSaveOrOpenBlob) {
        var blob = new Blob(['\ufeff', tableHTML], {
            type: dataType
        });
        navigator.msSaveOrOpenBlob(blob, filename);
    } else {
        // Create a link to the file
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
        // Setting the file name
        downloadLink.download = filename;

        //triggering the function
        downloadLink.click();
    }
}


$(document).on('click', '.js_report_export_excel_btn', function(e) {
    e.preventDefault();

    exportTableToExcel('js_report_table')

});
