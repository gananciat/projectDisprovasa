<template>
<!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
        <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Dashboard</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-6">
            <div class="card">
              <div class="card-header no-border">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">COMPRAS EN LOS ULTIMOS 12 MESES</h3>
                  <a href="javascript:void(0);">Reportes</a>
                </div>
              </div>
              <div class="card-body">
                <div class="d-flex">
                  <p class="d-flex flex-column">
                    <span class="text-bold text-lg">{{ getTotal(data) }}</span>
                    <span>compras en total</span>
                  </p>
                </div>
                <!-- /.d-flex -->

               <div >
                  <div class="row">
                    <div class="col-md-4 col-lg-4">
                        <b-form-select v-model="modo" :options="options" size="sm" class="mt-3"></b-form-select>
                    </div>
                  </div>
                      
                      <highcharts class="card-img-top" :options="chartOptions" ></highcharts>
                  </div>

                <div class="d-flex flex-row justify-content-end">
                  <span class="mr-2">
                    <i class="fa fa-square text-primary"></i> This Week
                  </span>

                  <span>
                    <i class="fa fa-square text-gray"></i> Last Week
                  </span>
                </div>
              </div>
            </div>
            <!-- /.card -->
            <!-- /.card -->
          </div>

          <div class="col-lg-6">
            <div class="card">
              <div class="card-header no-border">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">PEDIDOS EN LOS ULTIMOS 12 MESES</h3>
                  <a href="javascript:void(0);">Reportes</a>
                </div>
              </div>
              <div class="card-body">
                <div class="d-flex">
                  <p class="d-flex flex-column">
                    <span class="text-bold text-lg">{{  }}</span>
                    <span>compras en total</span>
                  </p>
                </div>
                <!-- /.d-flex -->

               <div >
                  <div class="row">
                    <div class="col-md-4 col-lg-4">
                        <b-form-select v-model="modo" :options="options" size="sm" class="mt-3"></b-form-select>
                    </div>
                  </div>
                      
                      <highcharts class="card-img-top" :options="chartOptionsOrder" ></highcharts>
                  </div>

                <div class="d-flex flex-row justify-content-end">
                  <span class="mr-2">
                    <i class="fa fa-square text-primary"></i> This Week
                  </span>

                  <span>
                    <i class="fa fa-square text-gray"></i> Last Week
                  </span>
                </div>
              </div>
            </div>
            <!-- /.card -->
            <!-- /.card -->
          </div>
          <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!--Fin-Contenido-->
</template>

<script>
import moment from 'moment'
import graps from './dashboard/graps'
export default {
  name: 'Default',
  data() {
      return {
          title: '',
          options: ['spline', 'line', 'bar', 'pie'],
          modo: 'spline',
          data: [],
          data: [],
          orders: [],
          original_orders: [],
          series: [
                        {
                          name: 'alimentacion',
                          data: [{
                            name:'enero',
                            y: [11,2]
                          }]
                        },
                        {
                          name: 'gratuidad',
                          data: [{
                            name:'enero',
                            y: [12,1]
                          }]
                        },
                        ],
      }
  },

  created() {
    let self = this
    this.getPurchases()
    self.getOrders()
  },

  methods: {
    getPurchases(){
      let self = this
      self.loading = true
      self.$store.state.services.dashboardService
        .getPurchases()
        .then(r => {
            self.loading = false
            self.data = r.data.data
        })
        .catch(r => {});
    },

    getOrders(){
      let self = this
      self.loading = true
      self.$store.state.services.dashboardService
        .getOrders()
        .then(r => {
            self.loading = false
            self.original_orders = r.data.data
            /*var groups = _(r.data.data)
                .groupBy('type_order')
                .map(function(items, type_order) {
                var grado_nivel = items.find(x=>x.type_order === type_order)
                return {
                    name: type_order,
                    data: items.filter(x=>x.type_order === type_order),
                };
            }).value();
            self.orders = groups
            self.getSeriesOrders()*/
        })
        .catch(r => {});
    },

   /* getSeriesOrders(){
      let self = this
      var labels = self.getLabelsOrders()
      var series = []
      for(var item of self.orders) {
        var data = []
        for(var label of labels){
          var sum = item.data.find(x=>moment().month(x.month-1).format("MMM")+' '+x.year==label)
          sum !== undefined ? data.push(parseFloat(sum.sum)) : data.push(0)
        }
        series.push({
          name: item.name,
          y: data
        })
      }
      console.log(series)
      console.log(labels)
      console.log(self.orders)
    },*/

    getLabelsOrders(){
      let self = this
      var labels = []
      var groups = _.groupBy(self.original_orders, function(item) {
        return moment().month(item.month-1).format("MMM")+' '+item.year
      })
      
      for(var item in groups){
        labels.push(item)
      }
      return labels

    },

    getSeries(data_values,name){
      let self = this
      var data = []
      var series = []
      data_values.forEach(item => {
        data.push({
          y: parseFloat(item.sum),
          name: moment().month(item.month-1).format("MMM")+' '+item.year
        })
      })
      series.push({
          name: name,
          data: data
        })
      return series
    },

    getLabels(data_labels){
      let self = this
      var labels = []
      data_labels.forEach(item => {
        labels.push(moment().month(item.month-1).format("MMM")+' '+item.year)
      })
      return labels
    },

    getTotal(data){
      var total = data.reduce((a,b)=>{
              return a + b.total
          },0)
      return total
    }
    
  },
   computed: {
        chartOptions() { 
          return graps.getData(this.getSeries(this.data,'COMPRAS'), this.getLabels(this.data),this.modo)
        },

        chartOptionsOrder() { 
          return graps.getData(this.getSeries(this.original_orders,'PEDIDOS'), this.getLabels(this.original_orders), this.modo)
        },

        /*chartOptionsOrder() { 
            return {
                    title: {
                    text: 'Solar Employment Growth by Sector, 2010-2016'
                },

                subtitle: {
                    text: 'Source: thesolarfoundation.com'
                },

                yAxis: {
                    title: {
                        text: 'Number of Employees'
                    }
                },

                xAxis: {
                    accessibility: {
                        rangeDescription: 'Range: 2010 to 2017'
                    }
                },

                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle'
                },

                plotOptions: {
                    series: {
                        label: {
                            connectorAllowed: false
                        },
                        pointStart: 2010
                    }
                },

                series: [{
                    name: 'Installation',
                    data: [43934, 52503, 57177, 69658, 97031, 119931, 137133, 154175]
                }, {
                    name: 'Manufacturing',
                    data: [24916, 24064, 29742, 29851, 32490, 30282, 38121, 40434]
                }
                ],

                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            legend: {
                                layout: 'horizontal',
                                align: 'center',
                                verticalAlign: 'bottom'
                            }
                        }
                    }]
                }
              }
        },*/
    },
}
</script>
