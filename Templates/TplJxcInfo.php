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
                lineNumbers: true, footer: true, toolbarEdit: true
            },
            toolbar: {
                items: [
                    {type: 'break'},
                    {
                        type: 'button', id: 'export_excel', caption: '导出Excel',
                        onClick: function (event) {
                            console.log(event);
                            W2Util.exportW2grid(w2ui['div_main_cnt'].name, w2ui['div_main_cnt']);
                        }
                    }
                ]
            },
            onLoad: function (event) {
                w2uiInitEmptyGrid(this, event);
            },
            onEdit: function (event) {
                console.log(event);
                var that = this;
                that.editField(event.recid, that.last.sel_col);
            },
            onAdd: w2GridOnAdd,
            onSave: w2GridOnSaveAndUpdate,
            onKeydown: w2GridOnKeyDown
        });
        w2ui['layout'].content('main', content);
        w2GridAddEmptyRecord(content);
    });
</script>