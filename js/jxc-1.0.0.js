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


/**
 * 进销存颜色管理
 */
var colorUtils = (function () {

    var obj = {
        version: '1.0.0',
        openPop: onOpenPop,
        onDestroy: onDestroy,
        initJxcColorsPopup: initJxcColorPopup,
        renderJxcColorCell: onRenderColorCell,
        showColorsPopup: onShowColorPopup
    };
    return obj;

    /**
     * 打开面板
     * @param parentW2Grid
     */
    function onOpenPop(parentW2Grid) {
        if (w2ui['popup_jxc_colors_w2grid']) {
            onShowColorPopup();
        } else {
            $.getJSON("Jxc/do.php?api=color&c=w2Records", null, function (data) {
                if (data['status'] == 'success') {
                    initJxcColorPopup(parentW2Grid, data['data']);
                    onShowColorPopup();
                }
            });
        }
    }

    /**
     * 销毁popup面板
     */
    function onDestroy() {
        if (w2ui['popup_jxc_colors_layout'])
            w2ui['popup_jxc_colors_layout'].destroy();
        if (w2ui['popup_jxc_colors_w2grid'])
            w2ui['popup_jxc_colors_w2grid'].destroy();
    }

    /**
     * 渲染颜色格子
     * @param record
     * @param index
     * @param col_index
     * @returns {string}
     */
    function onRenderColorCell(record, index, col_index) {
        var voColor = this.getCellValue(index, col_index);
        if ($.isPlainObject(voColor)) {
            return '<div style="height:24px;text-align:center;background-color: #' + voColor.color_rgba + ';">' + ' ' + voColor.color_name + '</div>';
        }
        return voColor ? '<div>' + voColor.color_id + '</div>' : '';
    }

    /**
     * 初始化颜色面板
     * @param w2gridObj
     * @param recordData
     */
    function initJxcColorPopup(w2gridObj, recordData) {
        //  w2layout    -
        var popup_jxc_colors_layout = {
            name: 'popup_jxc_colors_layout',
            padding: 4,
            panels: [
                {type: 'main'}
            ]
        };
        $().w2layout(popup_jxc_colors_layout);
        //  w2grid - colors
        var popup_jxc_colors_w2grid = {
            name: 'popup_jxc_colors_w2grid',
            show: {
                columnHeaders: false
            },
            columns: [
                {field: 'color0', caption: '颜色0', size: '20%', render: onRenderColorCell},
                {field: 'color1', caption: '颜色1', size: '20%', render: onRenderColorCell},
                {field: 'color2', caption: '颜色2', size: '20%', render: onRenderColorCell},
                {field: 'color3', caption: '颜色3', size: '20%', render: onRenderColorCell},
                {field: 'color4', caption: '颜色4', size: '20%', render: onRenderColorCell},
            ],
            records: recordData,
            onClick: function (event) {
                var that = this;
                console.log(event);
                var rec = that.get(event.recid);
                var targetFieldName = w2gridObj.columns[w2gridObj.last.sel_col].field;
                var changeData = [];
                //changeData['recid'] = w2gridObj.last.sel_recid;
                changeData[targetFieldName] = rec[that.columns[event.column].field].color_id;
                console.log(w2gridObj);
                console.log('last_recid = ' + w2gridObj.last.sel_recid);
                console.log(changeData);
                w2gridObj.set(w2gridObj.last.sel_recid, {
                    'changes': changeData
                });
                event.onComplete = function (event) {


                    w2popup.close();
                }
            }
        };
        $().w2grid(popup_jxc_colors_w2grid);
    }

    function onShowColorPopup() {
        w2popup.open({
            title: '颜   色',
//                body: '<div id="pop_layout">This is text inside the popup</div>',
//                body: '<div id="pop_layout" style="position: absolute; left: 5px; top: 5px; right: 5px; bottom: 5px;"></div>',
            body: '<div id="pop_layout" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px;"></div>',
//                body: '<div id="pop_layout" style="position: absolute; margin: 5px;"></div>',
            modal: true,
            showClose: true,
            showMax: true,
            width: 500,
            height: 300,
            onOpen: function (event) {
                console.log(event);
                event.onComplete = function (event) {
                    console.log(event);
                    $('#pop_layout').w2render('popup_jxc_colors_layout');
                    w2ui['popup_jxc_colors_layout'].content('main', w2ui['popup_jxc_colors_w2grid']);
                };
            },
            onToggle: function (event) {
                event.onComplete = function () {
                    w2ui['popup_jxc_colors_layout'].resize();
                }
            }
        });
    }
})();

/**
 *
 */
var pdtUtils = (function () {

    var obj = {
        version: '1.0.0',
        onOpen: onOpen,
        onInitPopup: onInitPopup,
        onRenderCell: onRenderCell,
        onShowPopup: onShowPopup,
        onDestroy: onDestroy
    };
    return obj;

    /**
     * 打开面板
     * @param parentW2Grid
     */
    function onOpen(parentW2Grid) {
        if (w2ui['popup_jxc_pdt_w2grid']) {
            onShowPopup();
        } else {
            $.getJSON("Jxc/do.php?api=color&c=w2Records", null, function (data) {
                if (data['status'] == 'success') {
                    onInitPopup(parentW2Grid, data['data']);
                    onShowPopup();
                }
            });
        }
    }

    /**
     * 销毁popup面板
     */
    function onDestroy() {
        if (w2ui['popup_jxc_colors_layout'])
            w2ui['popup_jxc_colors_layout'].destroy();
        if (w2ui['popup_jxc_pdt_w2grid'])
            w2ui['popup_jxc_pdt_w2grid'].destroy();
    }

    /**
     * 渲染格子
     * @param record
     * @param index
     * @param col_index
     * @returns {string}
     */
    function onRenderCell(record, index, col_index) {
        var voColor = this.getCellValue(index, col_index);
        if ($.isPlainObject(voColor)) {
            return '<div style="height:24px;text-align:center;background-color: #' + voColor.color_rgba + ';">' + ' ' + voColor.color_name + '</div>';
        }
        return voColor ? '<div>' + voColor.color_id + '</div>' : '';
    }

    /**
     * 初始化颜色面板
     * @param w2gridObj
     * @param recordData
     */
    function onInitPopup(w2gridObj, recordData) {
        var w2grid = w2gridObj;
        var records = recordData;
        //  w2layout    -
        var popup_layout = {
            name: 'popup_jxc_pdt_layout',
            padding: 4,
            panels: [
                {type: 'main'}
            ]
        };
        $().w2layout(popup_layout);
        //  w2grid - colors
        var popup_w2grid = {
            name: 'popup_jxc_pdt_w2grid',
            show: {
                columnHeaders: false
            },
            columns: [
                {field: 'color0', caption: '颜色0', size: '20%', render: onRenderColorCell},
                {field: 'color1', caption: '颜色1', size: '20%', render: onRenderColorCell},
                {field: 'color2', caption: '颜色2', size: '20%', render: onRenderColorCell},
                {field: 'color3', caption: '颜色3', size: '20%', render: onRenderColorCell},
                {field: 'color4', caption: '颜色4', size: '20%', render: onRenderColorCell},
            ],
            records: records,
            onClick: function (event) {
                var that = this;
                console.log(event);
                var rec = that.get(event.recid);
                var targetFieldName = w2grid.columns[w2grid.last.sel_col].field;
                var changeData = [];
                //changeData['recid'] = w2gridObj.last.sel_recid;
                changeData[targetFieldName] = rec[that.columns[w2grid.column].field].color_id;
                console.log(w2grid);
                console.log('last_recid = ' + w2grid.last.sel_recid);
                console.log(changeData);
                w2grid.set(w2grid.last.sel_recid, {
                    'changes': changeData
                });
                event.onComplete = function (event) {


                    w2popup.close();
                }
            }
        };
        $().w2grid(popup_w2grid);
    }

    function onShowPopup() {
        w2popup.open({
            title: '颜   色',
//                body: '<div id="pop_layout">This is text inside the popup</div>',
//                body: '<div id="pop_layout" style="position: absolute; left: 5px; top: 5px; right: 5px; bottom: 5px;"></div>',
            body: '<div id="pop_layout" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px;"></div>',
//                body: '<div id="pop_layout" style="position: absolute; margin: 5px;"></div>',
            modal: true,
            showClose: true,
            showMax: true,
            width: 500,
            height: 300,
            onOpen: function (event) {
                console.log(event);
                event.onComplete = function (event) {
                    console.log(event);
                    $('#pop_layout').w2render('popup_jxc_pdt_layout');
                    w2ui['popup_jxc_pdt_layout'].content('main', w2ui['popup_jxc_pdt_w2grid']);
                };
            },
            onToggle: function (event) {
                event.onComplete = function () {
                    w2ui['popup_jxc_pdt_layout'].resize();
                }
            }
        });
    }
})();
