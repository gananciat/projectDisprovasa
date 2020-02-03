export default {
    getData(series, labels, modo, text, prefix = '', suffix = '', decimals = 0) {
        return {
            chart: { type: modo },
            title: { text: '' },
            series: series,
            xAxis: {
                categories: labels
            },
            yAxis: {
                title: {
                    text: text
                }
            },

            tooltip: {
                valueDecimals: decimals,
                valuePrefix: prefix,
                valueSuffix: suffix
            },

            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true
                    },
                    enableMouseTracking: false
                },
                pie: {
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                    }
                }
            },
        }
    },
}