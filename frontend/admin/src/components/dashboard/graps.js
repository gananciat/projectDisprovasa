
export default {
          getData(series,labels,modo,text){
            return {
              chart: {  type: modo},
              title: {  text: ''  },
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