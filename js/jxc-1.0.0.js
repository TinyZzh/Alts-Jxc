/**
 * Created by TinyZ on 2016/10/26.
 */


/**
 * w2ui-grid创建新行
 * @param grid
 */
function w2GridAddEmptyRecord(grid) {
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
    if (grid.records.length <= 0) {
        event.onComplete = function () {
            w2uiEmptyColumn(grid, 1);
        };
    }
}

function w2uiEmptyColumn(grid, length) {
    for (var i = 0; i < length; i++)
        w2GridAddEmptyRecord(grid);
}

/**
 * 保存并更新本地数据
 * @param event onSave event.
 */
function w2GridOnSaveAndUpdate(event) {
    var that = this;
    if (event.xhr != null) {
        var cb = JSON.parse(event.xhr.responseText);
        if (cb['updates']) {
            cb['updates'].map(function (v) {
                if (v['depId']) {
                    //  移除空白行
                    for (var i = that.records.length - 1; i >= 0; i--) {
                        var firstField = that.columns[0].field;
                        if (that.records[i][firstField] == '') {
                            that.remove(that.records[i].recid);
                        }
                    }
                    that.remove(v['depId']);
                    that.add(v);
                    w2GridAddEmptyRecord(that);
                } else {
                    that.set(v['recid'], v);
                }
            });
        }
    }
}

/**
 * 检查record中某个字段值在Grid中是否唯一
 * @param grid
 * @param fieldName
 * @returns {boolean}
 */
function w2GridCheckUniqueID(grid, fieldName) {
    var list = [];
    grid.records.map(function (v) {
        list.push(v[fieldName]);
    });
    var changes = grid.getChanges();
    var val = '';
    changes.every(function (element, index, array) {
        var ind = ($.inArray(element[fieldName], list));
        if (ind >= 0) {
            val = element[fieldName];
            return false;
        } else {
            return true;
        }
    });
    return val;
}

/**
 * 键盘事件
 *
 * Ctrl + Enter : 创建新行
 * @param event
 */
function w2GridOnKeyDown(event) {
    var that = this;
    console.log(event.originalEvent);
    if (event.originalEvent.keyCode == 13
        && event.originalEvent.ctrlKey
    ) {    //  Ctrl + 回车
        if (that.records) {
            var nextRcd = that.nextRow(that.last.sel_recid);
            console.log(nextRcd);
            if (nextRcd == null) {
                var targetRcd = w2GridAddEmptyRecord(that);
                that.selectNone();
                that.select(targetRcd['recid']);
                that.editField(targetRcd['recid'], 1);
            }
        }
    }
}

/**
 * 编辑
 * @param event
 */
function w2GridOnEditField(event) {
    var that = this;
    var nextRow = that.nextRow(that.last.sel_ind);
    if (nextRow == null) w2GridAddEmptyRecord(that);
}

function w2GridOnAdd(event) {
    w2GridAddEmptyRecord(this);
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
                var targetRcd = w2GridAddEmptyRecord(that);
                that.selectNone();
                that.select(targetRcd['recid']);
                that.editField(targetRcd['recid'], 1);
            }
        }
    }
};

/**
 * 根据list的item的id删除item
 * @param items
 * @param id
 * @returns {Array}
 */
function removeByItemId(items, id) {
    var tmpItems = [];
    var tmpIndex = 0;
    var isMove = false;
    for (var i = 0; i < items.length; i++) {
        if (items[i].id == id) {
            delete items[i];
            isMove = true;
        } else if (isMove) {
            if (typeof(items[i]) != 'undefined') {
                items[i].id = tmpIndex;
                tmpItems.push(items[i]);
                tmpIndex++;
            }
        } else {
            tmpItems.push(items[i]);
            tmpIndex++;
        }
    }
    return tmpItems;
}


function renderSizeField(record, index, col_index) {
    var html = this.getCellValue(index, col_index);
    return '<div onmouseout="onSizeFieldMouseOut($(this));" ' +
        ' onmouseover="onSizeFieldMouseOver($(this), \'' + this.name + '\', ' + index + ', ' + col_index + '); " ' +
        ' style=" height: 24px; ">'
        + html + '</div>' || '';
}

/**
 * 字段[尺码]的tooltip
 * @param com_name
 * @param index
 * @param col_index
 */
function onSizeFieldMouseOver(div_tooltip, com_name, index, col_index) {
    var grid = w2ui[com_name];
    var j = grid.columns[col_index].field.substr(10);
    var record = grid.records[index];
    if (record['pdt_id'] == undefined || record['pdt_id'] == '') {
        //w2alert("[Error]请先输入货号.", "Error");
        return;
    }
    var pdt_id = typeof(record['pdt_id']) == 'object' ? record['pdt_id'].text : record['pdt_id'];
    var limit = cacheOfPdtInfo[pdt_id].pdt_counts[j] || 0;
    var str = '<div style="padding: 5px">最大数量:[' + limit + ']</div>';
    div_tooltip.w2overlay(str);
    //div_tooltip.w2tag(str);
}

function onSizeFieldMouseOut(div_tooltip) {
    //div_tooltip.w2tag();    //  隐藏tooltip
}