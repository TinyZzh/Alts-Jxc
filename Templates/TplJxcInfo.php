<script>
    $(document).ready(function () {
        var content = $('#div_main_cnt').w2grid({
            name: 'div_main_cnt',
            header: configJxc.header,
            url: configJxc.urls,
            columns: configJxc.columns,
            multiSelect: true,
            show: {
                header: true, toolbar: true, toolbarAdd: true, toolbarSave: true, toolbarDelete: true,
                lineNumbers: true, footer: true
            },
            toolbar: {
                items: [
                    {type: 'break'},
                    {
                        type: 'button', id: 'pdt_save', caption: '保存', icon: 'w2ui-icon-check',
                        onClick: function (event) {
                            console.log(event);

                            var dataTable = $("#grid_div_main_cnt_records table").tableExport();

                            dataTable.export2file();
                        }
                    }
                ]
            },
            onLoad: function (event) {
                w2uiInitEmptyGrid(this, event);
            },
            onAdd: w2GridOnAdd,
            onSave: w2GridOnSaveAndUpdate,
            onKeydown: w2GridOnKeyDown
        });
        w2ui['layout'].content('main', content);
        w2GridAddEmptyRecord(content);
    });
</script>