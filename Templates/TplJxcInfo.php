<script>
    $(document).ready(function () {
        var defaultOpts = {
            name: 'div_main_cnt',
            header: 'div_main_cnt_header',
            url: undefined,
            columns: [],
            multiSelect: true,
            show: {
                header: true, toolbar: true, toolbarAdd: true, toolbarSave: true, toolbarDelete: true,
                lineNumbers: true, footer: true, toolbarEdit: true, toolbarSearch: true
            },
            searches: undefined,
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
        };
        var opts = $.extend({}, defaultOpts, configJxc);

        var content = $('#div_main_cnt').w2grid(opts);
        w2ui['layout'].content('main', content);
        w2GridAddEmptyRecord(content);
    });
</script>