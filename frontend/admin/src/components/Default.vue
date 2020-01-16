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
                    <span class="text-bold text-lg">30</span>
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
export default {
  name: 'Default',
  data() {
      return {
          title: '',
          options: ['spline', 'line', 'bar', 'pie'],
          modo: 'spline',
          series: [],
          data: []
      }
  },

  created() {
    let self = this
    this.getPurchases()
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
            self.getSeries()
        })
        .catch(r => {});
    },

    getSeries(){
      let self = this
      var data = []
      var series = []
      self.data.forEach(item => {
        data.push(parseFloat(item.sum))
      })
      series.push({
          name: 'COMPRAS',
          data: data
        })
      return series
    },

    getLabels(){
      let self = this
      var labels = []
      self.data.forEach(item => {
        labels.push(moment().month(item.month-1).format("MMM")+' '+item.year)
      })
      return labels
    },
    
  },
   computed: {
        chartOptions() { 
            return {
                    chart: {  type: this.modo, height: (9 / 16 * 100) + '%'},
                    title: {  text: this.title  },
                    series: this.getSeries(),
                    xAxis: {
                        categories: this.getLabels()
                    },
                    yAxis: {
                        title: {
                            text: 'COMPRAS '
                        }
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
                        formatter: function() {
                          var sliceIndex = this.point.index;
                          var sliceName = this.series.chart.axes[0].categories[sliceIndex];
                          return sliceName
                        }
                      }
                    }
                    },
              }
        },
    },
}
</script>
