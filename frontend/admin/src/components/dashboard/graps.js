
export default {
          getData(series,labels,modo){
            return {
              chart: {  type: modo, height: (9 / 16 * 100) + '%'},
              title: {  text: ''  },
              series: series,
              xAxis: {
                  categories: labels
              },
              yAxis: {
                  title: {
                      text: 'COMPRAS '
                  }
              },

              tooltip: {
                  valueDecimals: 2,
                  valuePrefix: 'Q ',
                  valueSuffix: ' GTQ'
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