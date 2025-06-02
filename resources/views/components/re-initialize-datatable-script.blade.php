<script>
    let table;

    function initializeDataTable() {
        if (table) {
            table.destroy();
        }

        table = new DataTable('.datatable', {
            "ordering": true,
            "searching": true,
            "paging": true,
            "info": true,
            "pageLength": 25,
            "lengthChange": true,
            select: false,
            colReorder: true,
            responsive: true,
            "language": {
                "paginate": {
                    "first": "&laquo;&laquo;",
                    "last": "&raquo;&raquo;",
                    "previous": "&laquo;",
                    "next": "&raquo;"
                }
            },
            dom: '<"top"lfB>rt<"bottom"ip><"clear">',
            buttons: [
                'copy',
                {
                    extend: 'excelHtml5',
                    text: 'Excel',
                    title: document.title,
                    filename: function () {
                        let date = new Date();
                        let formattedDate = date.getFullYear() + '-' +
                            (date.getMonth() + 1).toString().padStart(2, '0') + '-' +
                            date.getDate().toString().padStart(2, '0') + '_' +
                            date.getHours().toString().padStart(2, '0') + '-' +
                            date.getMinutes().toString().padStart(2, '0');
                        return document.title + '_' + formattedDate;
                    }, exportOptions: {
                        columns: ':not(.action)'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: 'PDF (Portrait)',
                    orientation: 'portrait',
                    pageSize: 'A4',
                    title: 'Report (Portrait)',
                    filename: function () {
                        let date = new Date();
                        let formattedDate = date.getFullYear() + '-' +
                            (date.getMonth() + 1).toString().padStart(2, '0') + '-' +
                            date.getDate().toString().padStart(2, '0') + '_' +
                            date.getHours().toString().padStart(2, '0') + '-' +
                            date.getMinutes().toString().padStart(2, '0');
                        return document.title + '_' + formattedDate;
                    },
                    customize: function (doc) {
                        const pageSize = doc.pageSize;
                        doc.watermark = {
                            text: 'Savior',
                            color: 'grey',
                            fontSize: 60,
                            width: 300,
                            height: 300,
                            opacity: 0.3,
                            absolutePosition: {
                                x: (pageSize.width - 300) / 2,
                                y: (pageSize.height - 300) / 2
                            }
                        };
                    }, exportOptions: {
                        columns: ':not(.action)'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: 'PDF (Landscape)',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    title: 'Report (Landscape)',
                    filename: function () {
                        let date = new Date();
                        let formattedDate = date.getFullYear() + '-' +
                            (date.getMonth() + 1).toString().padStart(2, '0') + '-' +
                            date.getDate().toString().padStart(2, '0') + '_' +
                            date.getHours().toString().padStart(2, '0') + '-' +
                            date.getMinutes().toString().padStart(2, '0');
                        return document.title + '_' + formattedDate;
                    },
                    customize: function (doc) {
                        const pageSize = doc.pageSize;
                        doc.watermark = {
                            text: 'Savior',
                            color: 'grey',
                            fontSize: 60,
                            width: 300,
                            height: 300,
                            opacity: 0.3,
                            absolutePosition: {
                                x: (pageSize.width - 300) / 2,
                                y: (pageSize.height - 300) / 2
                            }
                        };
                    }, exportOptions: {
                        columns: ':not(.action)'
                    }
                },
                'print',
                {
                    extend: 'csv',
                    exportOptions: {
                        columns: ':not(.action)'
                    }
                }
            ]
        });

        $('.datatable thead').prepend('<tr class="filter-row"></tr>');

        table.columns().every(function () {
            let column = this;
            let header = $(column.header());

            let select = $('<th><select><option value="">All</option></select></th>')
                .appendTo('.datatable thead tr.filter-row')
                .find('select')
                .on('change', function () {
                    let val = $.fn.DataTable.util.escapeRegex($(this).val());
                    column.search(val ? '^' + val + '$' : '', true, false).draw();
                });

            if (header.hasClass('nofilter')) {
                return;
            }

            let uniqueData = [];
            column.data().unique().each(function (d, j) {
                if (d) {
                    let cleanData = $('<div>').html(d).text().trim();
                    if (cleanData && !uniqueData.includes(cleanData)) {
                        uniqueData.push(cleanData);
                    }
                }
            });

            uniqueData.sort(function (a, b) {
                let numA = parseInt(a.match(/\d+/)) || 0;
                let numB = parseInt(b.match(/\d+/)) || 0;
                if (numA === numB) {
                    return a.localeCompare(b);
                }
                return numA - numB;
            });

            uniqueData.forEach(function (cleanData) {
                select.append('<option value="' + cleanData + '">' + cleanData + '</option>');
            });
        });
    }

    document.addEventListener("DOMContentLoaded", () => {
        Livewire.on('initialize-data-table', function () {
            setTimeout(initializeDataTable, 100);
        })
    });

</script>
