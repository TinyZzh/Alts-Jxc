/**
 * Created by TinyZ on 2016/10/26.
 */


/**
 * w2ui-grid创建新行
 * @param grid
 */
function w2GridAddRecord(grid) {
    var rcd = {'recid': Date.now()};
    grid.columns.map(function (v) {
        rcd[v.field] = '';
    });
    grid.add(rcd);
    return rcd;
}

/**
 *
 * @param field
 * @param caption
 * @param size
 * @param items
 * @returns {{field: *, caption: *, size: *, editable: {type: string, items: *}, render: Function}}
 */
function w2GridColumnList(field, caption, size, items) {
    var column = {
        field: field, caption: caption, size: size,
        editable: {
            type: 'list',
            items: items
        },
        render: function (record, index, col_index) {
            var html = this.getCellValue(index, col_index);
            //console.log(html);
            return html.text || '';
        }
    };
    return column;
}

/**
 * 空Grid初始化
 * @param grid  w2grid object.
 * @param event onLoad event.
 */
function w2uiInitEmptyGrid(grid, event) {
    if (grid.records.length <= 0){
        event.onComplete = function() {
            for (var i = 0; i < 5; i++)
                w2GridAddRecord(grid);
        };
    }
}

//
/**
 * 按Ctrl+Enter创建新记录行
 * @param event
 */
var w2uiFuncGridOnKeydown = function (event) {
    var that = this;
    console.log(event.originalEvent);
    if (event.originalEvent.keyCode == 13
        && event.originalEvent.ctrlKey
    ) {    //  Ctrl + 回车
        if (that.records) {
            var nextRcd = that.nextRow(that.last.sel_recid);
            console.log(nextRcd);
            if (nextRcd == null) {
                var targetRcd = w2GridAddRecord(that);
                that.selectNone();
                that.select(targetRcd['recid']);
                that.editField(targetRcd['recid'], 1);
            }
        }
    }
};
