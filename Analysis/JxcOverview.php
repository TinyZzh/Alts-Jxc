<?php
/**
 * 采购管理.
 *
 */
include_once "../Templates/include.php";

?>
<!DOCTYPE html>
<html lang="zh-cn">
<body id="body">
    <div id="div_analysis" style="width: 600px;height:400px;"></div>
</body>
<script>
    $(document).ready(function () {
        var divA1 = document.getElementById('div_analysis');
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(divA1);

        // 指定图表的配置项和数据
        var option = {
            title: {
                text: 'ECharts 入门示例'
            },
            tooltip: {},
            legend: {
                data:['销量']
            },
            xAxis: {
                data: ["衬衫","羊毛衫","雪纺衫","裤子","高跟鞋","袜子"]
            },
            yAxis: {},
            series: [{
                name: '销量',
                type: 'bar',
                data: [5, 20, 36, 10, 10, 20]
            }]
        };
        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);

        w2ui['layout'].content('main', divA1);
    });
</script>
</html>