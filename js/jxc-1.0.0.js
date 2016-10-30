/**
 * Created by TinyZ on 2016/10/26.
 */


/**
 * w2ui-grid创建新行
 * @param grid
 */
function jxcAddRecord(grid) {
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
function jxcColumnList(field, caption, size, items) {
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

function fieldChange() {

}