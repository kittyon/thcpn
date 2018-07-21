import highcharts from 'highcharts';
var _ = require('lodash');;
var defaultOption = {
    chart: {
        type: 'spline', //曲线样式
        animation: highcharts.svg, // don't animate in old IE
        marginRight: 10,
        zoomType: 'x'
    },
    title: {
        text: ''
    },

    xAxis: {
        type: 'datetime',
        minRange: 24 * 60 * 60 * 1000,
        minTickInterval: 60 * 1000,
            //minRange:60*1000
            // dateTimeLabelFormats: {
            //     millisecond: '%H:%M:%S.%L',
            //     second: '%H:%M:%S',
            //     minute: '%H:%M',
            //     hour: '%H:%M',
            //     day: '%m-%d',
            //     week: '%m-%d',
            //     month: '%Y-%m',
            //     year: '%Y'
            // }
    },
    yAxis: {
        title_v: {
            text: '摄氏度'
        },
        title: {
            text: ''
        },
        plotLines: [{
            value: 0,
            width: 1,
            color: 'blue'
                //color: '#808080'
        }]
    },
    tooltip: {
        backgroundColor: '#fff',
        borderColor: 'black',
        formatter: function() { //数据提示框中单个点的格式化函数
            return '<b>' + this.series.name + '</b><br/>' +
                highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) + '<br/>' +
                highcharts.numberFormat(this.y, 3); //小数后几位
        }
    },
    legend: {
        enabled: true
    },
    exporting: {
        enabled: true
    },
    plotOptions: {
        spline: {
            lineWidth: 1,
            fillOpacity: 0.1,
            marker: {
                enabled: true,
                states: {
                    hover: {
                        enabled: true,
                        radius: 1.5
                    }
                }
            },
            shadow: false
        }
    },
    series: [], //format [{name: "name", data: [[x1,y1],[x2,y2]]},{name: "name", data:[[x1,y1],[x2,y2]]}]
};

export default defaultOption;
