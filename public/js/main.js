$('#order_datatable').DataTable({
    paging: false,
    pageLength: 10,
    lengthChange: false,
    info: false,
    "language": {
        "processing": "Подождите...",
        "search": "",
        "lengthMenu": "_MENU_",
        "info": "Записи с _START_ до _END_ из _TOTAL_ записей",
        "infoEmpty": "Записи с 0 до 0 из 0 записей",
        "infoFiltered": "(отфильтровано из _MAX_ записей)",
        "loadingRecords": "Загрузка записей...",
        "zeroRecords": "Записи отсутствуют.",
        "emptyTable": "В таблице отсутствуют данные",
        "paginate": {
            "first": "Первая",
            "previous": "Предыдущая",
            "next": "Следующая",
            "last": "Последняя"
        },
        "searchPlaceholder": "Поиск"
    },
    "lengthMenu": [25, 50, 75, 100, 200],
    pagingType: 'full_numbers',
    order: [[0, 'desc']],
});


$(".datepicker").datepicker({
    uiLibrary: 'bootstrap4',
    format: "dd.mm.yyyy"
});