<?php
/**
 * 销售订单.
 *
 */
include_once "../Templates/include.php";
use Jxc\Impl\Core\JxcConst;

$type = JxcConst::IO_TYPE_SALES;
?>
<!DOCTYPE html>
<html lang="zh-cn">
<body id="body">
</body>
<script>
    var configJxc = {
        header: '销售管理',
        isSales: true,
        url_popup_pdt: 'Jxc/do.php?api=product&c=pdtW2gridRecords',
        url_popup_submit: 'Jxc/do.php?api=product&c=sell',
        toolbar: {
            items: [
                {
                    type: 'button', id: 'btn_customer', caption: '选择客户', icon: 'w2ui-icon-check',
                    onClick: function (event) {
                        console.log(event);
                        var that = this;
                        event.preventDefault();
                        var url = "Jxc/do.php?api=custom&c=records";
                        $.getJSON(url, null, function (data) {
                            if (data['status'] == 'success') {
                                console.log('popup_initialized');
                                var pdtOptions = popupCustomerOption(that, event.index, event.column, 'pop_w2grid_custom', data['records']);
                                PopupUtil.onPopupShow({
                                    subOptions: pdtOptions
                                });
                            }
                        });
                    }
                },
                {type: 'break'},
                {type: 'button', id: 'label_custom_id', caption: '', disabled: true},
                {type: 'button', id: 'label_custom_name', caption: '', disabled: true},
                {type: 'button', id: 'label_custom_adr', caption: '', disabled: true}
            ]
        }
    };
</script>
<?php
include_once "../Templates/TplJxcOrderBase.php";
?>
</html>