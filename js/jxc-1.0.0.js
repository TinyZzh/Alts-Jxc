/*! tinyzzh-jxc v1.0.0 | (c) 2016, 2026 TinyZ, Inc. | Apache License Version 2.0 */
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

function checkRepeatedField(w2grid, col_ind) {
    var map = {};
    for (var i = 0; i < w2grid.records.length; i++) {
        var val = w2grid.getCellValue(i, col_ind);
        if (map[val]) {
            return val;
        }
        map[val] = 1;
    }
    return null;
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

const Const = {
    CACHE_OF_COLORS: 'cacheOfColors',
    CACHE_OF_CUSTOMERS: 'cacheOfCustomers',
};

/**
 * 缓存工具
 */
var CacheUtil = (function () {

    var map = {};

    var obj = {
        cache: cache
    };
    return obj;

    function cache(k, v) {
        return $.data(map, k, v);
    }
})();

var W2Util = (function () {

    var obj = {
        request: request,
        exportW2grid: exportW2grid,
        exportXLSX: exportXLSX,
        renderJxcColorCell: renderJxcColorCell,
        renderJxcCustomerNameCell: renderJxcCustomerNameCell,
        renderJxcPdtSizeCell: renderJxcPdtSizeCell,
        onMouseOverPdtSizeCell: onMouseOverPdtSizeCell,
        onMouseOutPdtSizeCell: onMouseOutPdtSizeCell
    };
    return obj;

    /**
     * Http请求
     * @param url
     * @param params
     * @param callback
     */
    function request(url, params, callback) {
        if (url == undefined || callback == undefined) return;
        var options = {
            type: 'GET',
            url: '',
            data: {},
            dataType: 'JSON'
        };
        options.url = url;
        $.extend(options.data, {}, params);
        $.ajax(options)
            .done(callback)
            .fail(function (xhr, status, error) {
                w2alert('HTTP ERROR:[' + error.message + ']', "Error");
            });
    }

    /**
     * w2grid - 渲染进销存系统颜色格子
     * @param record
     * @param index
     * @param col_index
     * @returns {string}
     */
    function renderJxcColorCell(record, index, col_index) {
        var html = this.getCellValue(index, col_index);
        var val = CacheUtil.cache(Const.CACHE_OF_COLORS);
        if (val != undefined && val[html]) {
            return '<div style="height:24px;text-align:center;background-color: \#' + val[html].color_rgba + ';">'
                + val[html].color_name
                + '</div>';
        }
        return '<div>' + html + '</div>';
    }

    /**
     *  w2grid - 渲染客户名称
     */
    function renderJxcCustomerNameCell(record, index, col_index) {
        var html = this.getCellValue(index, col_index);
        var val = CacheUtil.cache(Const.CACHE_OF_CUSTOMERS);
        return '<div style="text-align:center;">' + (val != undefined && val[html]) ? val[html].ct_name : html + '</div>';
    }

    /**
     * w2grid - 选择单品尺寸(库存tooltip)
     */
    function renderJxcPdtSizeCell(record, index, col_index) {
        var html = this.getCellValue(index, col_index);
        return '<div onmouseout="W2Util.onMouseOutPdtSizeCell($(this));" '
            + ' onmouseover="W2Util.onMouseOverPdtSizeCell($(this), \'' + this.name + '\', ' + index + ', ' + col_index + ');">'
            + (html || '') + '</div>';
    }

    /**
     * 字段[尺码]的tooltip
     * @param com_name
     * @param index
     * @param col_index
     */
    function onMouseOverPdtSizeCell(div_tooltip, com_name, index, col_index) {
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

    function onMouseOutPdtSizeCell(div_tooltip) {
        //div_tooltip.w2tag();    //  隐藏tooltip
    }

    /**
     * 导出w2grid的记录
     * @param name
     * @param w2grid
     */
    function exportW2grid(name, w2grid) {
        var columns = w2grid.columns,
            records = w2grid.records;
        var data = [];
        var frow = [];
        for (var e = 0; e < columns.length; e++) {
            frow[e] = columns[e].caption;
        }
        data[0] = frow;
        var searchIds = w2grid.last.searchIds;
        if (searchIds && searchIds.length > 0) {
            w2grid.last.searchIds.map(function (rec_ind, ind) {
                var row = [];
                for (var j = 0; j < columns.length; j++) {
                    row[j] = records[rec_ind][columns[j].field];
                }
                data[ind + 1] = row;
            })
        } else {
            records.map(function (record, ind) {
                var row = [];
                for (var j = 0; j < columns.length; j++) {
                    row[j] = record[columns[j].field];
                }
                data[ind + 1] = row;
            });
        }
        exportXLSX(name, data);
    }

    /**
     * 导出xlsx
     * @param name
     * @param data
     */
    function exportXLSX(name, data) {
        var wb = {SheetNames: [], Sheets: {}};
        wb.SheetNames.push(name);
        wb.Sheets[name] = createSheet(data);
        var wopts = {bookType: 'xlsx', bookSST: false, type: 'binary'};
        var wbout = XLSX.write(wb, wopts);
        data = string2ArrayBuffer(wbout);
        saveAs(new Blob([data], {type: "application/vnd.ms-excel;utf-8"}), name + ".xlsx", true);
    }

    /**
     * Creates an Excel spreadsheet from a data string
     * @param data {String}
     * @returns {Number} epoch time
     */
    function createSheet(data) {
        var ws = {};
        var range = {s: {c: 10000000, r: 10000000}, e: {c: 0, r: 0}};
        for (var R = 0; R != data.length; ++R) {
            for (var C = 0; C != data[R].length; ++C) {
                if (range.s.r > R) range.s.r = R;
                if (range.s.c > C) range.s.c = C;
                if (range.e.r < R) range.e.r = R;
                if (range.e.c < C) range.e.c = C;
                var cell = {v: data[R][C]};
                if (cell.v == null) continue;
                var cell_ref = XLSX.utils.encode_cell({c: C, r: R});

                if (typeof cell.v === 'number') cell.t = 'n';
                else if (typeof cell.v === 'boolean') cell.t = 'b';
                else if (cell.v instanceof Date) {
                    cell.t = 'n';
                    cell.z = XLSX.SSF._table[14];
                    cell.v = dateNum(cell.v);
                }
                else cell.t = 's';
                ws[cell_ref] = cell;
            }
        }
        if (range.s.c < 10000000) ws['!ref'] = XLSX.utils.encode_range(range);
        return ws;
    }

    /**
     * Formats datetimes for compatibility with Excel
     * @param v {Number}
     * @param date1904 {Date}
     * @returns {Number} epoch time
     */
    function dateNum(v, date1904) {
        if (date1904) v += 1462;
        var epoch = Date.parse(v);
        return (epoch - new Date(Date.UTC(1899, 11, 30))) / (24 * 60 * 60 * 1000);
    }

    /**
     * Converts a string to an arraybuffer
     * @param s {String}
     * @returns {ArrayBuffer}
     */
    function string2ArrayBuffer(s) {
        var buf = new ArrayBuffer(s.length);
        var view = new Uint8Array(buf);
        for (var i = 0; i != s.length; ++i) view[i] = s.charCodeAt(i) & 0xFF;
        return buf;
    }

})();

function popupColorsOption(w2Target, w2Index, w2Column, popGridName, popRecords) {
    var targetGrid = w2Target;
    var ops = {
        name: popGridName,
        show: {
            columnHeaders: false
        },
        columns: [
            {field: 'color0', caption: '颜色0', size: '20%', render: W2Util.renderJxcColorCell},
            {field: 'color1', caption: '颜色1', size: '20%', render: W2Util.renderJxcColorCell},
            {field: 'color2', caption: '颜色2', size: '20%', render: W2Util.renderJxcColorCell},
            {field: 'color3', caption: '颜色3', size: '20%', render: W2Util.renderJxcColorCell},
            {field: 'color4', caption: '颜色4', size: '20%', render: W2Util.renderJxcColorCell}
        ],
        records: popRecords,
        onDblClick: function (event) {
            var that = this;
            console.log(event);
            var rec = that.get(event.recid);
            var targetFieldName = targetGrid.columns[w2Column].field;
            var changeData = targetGrid.records[w2Index].changes;
            if (!changeData) changeData = {};
            changeData[targetFieldName] = rec[that.columns[event.column].field];
            //console.log(w2gridObj);
            //console.log('last_recid = ' + w2gridObj.last.sel_recid);
            //console.log(changeData);
            targetGrid.set(targetGrid.last.sel_recid, {
                'changes': changeData
            });
            event.onComplete = function (event) {

                w2popup.close();
            }
        }
    };
    return ops;
}

/**
 * 生成pop的w2grid
 * @param w2Target
 * @param w2Index
 * @param w2Column
 * @param popGridName
 * @param popRecords
 * @returns {{name: string, show: {columnHeaders: boolean}, columns: [*,*,*,*,*,*,*,*,*,*,*,*,*,*,*,*], records: *, onClick: onClick}}
 */
function popupPdtOption(w2Target, w2Index, w2Column, popGridName, popRecords) {
    var targetGrid = w2Target;
    var ops = {
        name: popGridName,
        header: '产品',
        multiSelect: false,
        columns: [
            {field: 'pdt_id', caption: '编号', size: '80px', style: 'text-align:center'},
            {field: 'pdt_name', caption: '名称', size: '120px', style: 'text-align:center'},
            {field: 'pdt_color', caption: '颜色', size: '80px', render: W2Util.renderJxcColorCell},
            {field: 'pdt_count_0', caption: '3XS', size: '55px', render: 'int'},
            {field: 'pdt_count_1', caption: '2XS', size: '55px', render: 'int'},
            {field: 'pdt_count_2', caption: ' XS', size: '55px', render: 'int'},
            {field: 'pdt_count_3', caption: '  S', size: '55px', render: 'int'},
            {field: 'pdt_count_4', caption: '  M', size: '55px', render: 'int'},
            {field: 'pdt_count_5', caption: '  L', size: '55px', render: 'int'},
            {field: 'pdt_count_6', caption: ' XL', size: '55px', render: 'int'},
            {field: 'pdt_count_7', caption: '2XL', size: '55px', render: 'int'},
            {field: 'pdt_count_8', caption: '3XL', size: '55px', render: 'int'},
            {field: 'pdt_total', caption: '总数量', size: '80px', render: 'int'}
        ],
        show: {toolbar: true, toolbarSearch: true, lineNumbers: true},
        searches: [
            {field: 'pdt_id', caption: '货号', type: 'text'},
            {field: 'pdt_name', caption: '名称', type: 'text'},
            {field: 'pdt_color', caption: '颜色', type: 'text'}
        ],
        toolbar: {
            items: [
                {type: 'break'}
            ]
        },
        records: popRecords,
        onDblClick: function (event) {
            var that = this;
            console.log(event);
            var rec = that.get(event.recid);
            var targetField = targetGrid.columns[w2Column].field;
            var targetRcd = targetGrid.records[w2Index];
            var changeData = targetRcd.changes;
            if (changeData == undefined) changeData = {};
            changeData[targetField] = rec[targetField];
            changeData['pdt_price'] = rec['pdt_price'];
            changeData['pdt_zk'] = 100; //  折扣缺省值为100
            targetRcd['recid'] = rec['pdt_id'];
            targetGrid.set(rec['pdt_id'], {
                pdt_id: rec[targetField],
                pdt_name: rec['pdt_name'],
                pdt_color: rec['pdt_color'],
                pdt_total: 0,
                total_rmb: 0.00,
                changes: changeData,
            });
            // targetRcd['pdt_id'] = rec[targetField];
            // targetRcd['pdt_total'] = 0;
            // targetRcd['total_rmb'] = 0.00;
            // targetRcd['changes'] = changeData;
            //console.log(w2gridObj);
            //console.log(changeData);
            // targetGrid.remove(targetGrid.last.sel_recid);
            // targetGrid.add(targetRcd);
            // targetGrid.refresh();
            //  最后一行
            var nextRcd = that.nextRow(targetGrid.last.sel_recid);
            if (nextRcd == null) {
                //  移除空白行
                for (var i = targetGrid.records.length - 1; i >= 0; i--) {
                    if (targetGrid.records[i][targetField] == '') {
                        targetGrid.remove(targetGrid.records[i].recid);
                    }
                }
                w2GridAddEmptyRecord(targetGrid);
            }
            event.onComplete = function (event) {

                w2popup.close();
            }
        }
    };
    return ops;
}

function popupCustomerOption(w2Target, w2Index, w2Column, popGridName, popRecords) {
    var targetToolbar = w2Target;
    var ops = {
        name: popGridName,
        header: '客户信息',
        multiSelect: false,
        columns: [
            {field: 'ct_id', caption: '客户ID', size: '5%', style: 'text-align:center'},
            {field: 'ct_name', caption: '客户姓名', size: '7%', style: 'text-align:center', editable: {type: 'text'}},
            {field: 'ct_address', caption: '通信地址', size: '25%', style: 'text-align:right', editable: {type: 'text'}},
            {field: 'ct_phone', caption: '联系电话', size: '8%', style: 'text-align:right', editable: {type: 'text'}},
            {field: 'ct_money', caption: '账户余额', size: '7%', editable: {type: 'float'}, render: 'money:2'}
        ],
        show: {toolbar: true, toolbarSearch: true, lineNumbers: true},
        searches: [
            {field: 'ct_id', caption: '客户ID', type: 'text'},
            {field: 'ct_name', caption: '客户姓名', type: 'text'},
            {field: 'ct_phone', caption: '联系电话', type: 'text'}
        ],
        records: popRecords,
        onDblClick: function (event) {
            var that = this;
            console.log(event);
            var rec = that.get(event.recid);
            targetToolbar.set('label_custom_id', {'caption': rec['ct_id']});
            targetToolbar.set('label_custom_name', {'caption': rec['ct_name']});
            targetToolbar.set('label_custom_adr', {'caption': rec['ct_address']});
            event.onComplete = function (event) {
                w2popup.close();
            }
        }
    };
    return ops;
}

/**
 *  弹窗工具
 */
var PopupUtil = (function () {

    var obj = {
        version: '1.0.0',
        options: {},
        defaults: {
            name_layout: 'popup_w2layout_jxc',
            content: null,  //  Popup Content
            max: true,
            width: 500,
            height: 300,
            subRender: renderW2grid,  //  content render
            sub_type: 'w2grid',
            subOptions: {
                name: 'popup_w2grid_jxc'
            }
        },
        onPopupShow: onPopupShow,
        onPopupReset: onPopupReset,
        onPopupDestroy: onPopupDestroy,
        onPopupMax: onPopupMax
    };
    return obj;

    /**
     * 打开面板
     * @param options
     */
    function onPopupShow(options) {
        // $.extend(obj.options.subOptions, obj.defaults.subOptions, options.subOptions);
        $.extend(obj.options, obj.defaults, options);
        console.log(obj.options);
        popupInit();
        popupShow();
    }

    function onPopupReset() {
        onPopupShow(obj.options);
    }

    /**
     * 初始化颜色面板
     */
    function popupInit() {
        console.log('popupInit');
        //  w2layout    -
        var popup_layout = {
            name: obj.options.name_layout,
            padding: 4,
            panels: [
                {type: 'main'}
            ]
        };
        $().w2layout(popup_layout);
        //  popup content
        if (obj.options.subRender != undefined && obj.options.subRender != null) {
            obj.options.subRender();
        }
    }

    function popupShow() {
        console.log(obj);
        var width = obj.options.width;
        var height = obj.options.height;
        w2popup.open({
            title: '产   品',
//                body: '<div id="pop_layout">This is text inside the popup</div>',
//                body: '<div id="pop_layout" style="position: absolute; left: 5px; top: 5px; right: 5px; bottom: 5px;"></div>',
            body: '<div id="pop_layout" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px;"></div>',
//                body: '<div id="pop_layout" style="position: absolute; margin: 5px;"></div>',
            modal: true,
            showClose: true,
            showMax: true,
            width: width,
            height: height,
            onOpen: function (event) {
                console.log(event);
                event.onComplete = function (event) {
                    console.log(event);
                    // if (obj.options.max) {
                    //     onPopupMax();
                    // }
                    $('#pop_layout').w2render(obj.options.name_layout);
                    w2ui[obj.options.name_layout].content('main', obj.options.content);
                };
            },
            onMax: function (event) {
                console.log(event);

            },
            onToggle: function (event) {
                event.onComplete = function () {
                    w2ui[obj.options.name_layout].resize();
                }
            },
            onClose: function (event) {
                console.log(event);
                onPopupDestroy();
            }
        });
    }

    function onPopupMax() {
        w2popup.max();
        // setTimeout(function () {
        //     console.log('x1');
        //     if (w2ui[obj.options.name_layout]) {
        //         w2ui[obj.options.name_layout].refresh();
        //     }
        //     if (obj.content)
        //         obj.content.refresh();
        // }, 1);
    }

    /**
     * 销毁popup面板
     */
    function onPopupDestroy() {
        var options = obj.options;
        var ary = [
            options.name_layout,
            options.subOptions.name
        ];
        for (var i = 0; i < ary.length; i++) {
            if (w2ui[ary[i]])
                w2ui[ary[i]].destroy();
        }
    }

    function renderW2grid() {
        console.log('renderW2grid');
        if (obj.options.sub_type == 'w2grid') {
            var w2grid = $().w2grid(obj.options.subOptions);
            obj.options.content = w2grid;
            return w2grid;
        }
        return null;
    }
})();
