<template>

  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Dashboard</h1>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <div class="content" v-loading="loading">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="card">

              <highcharts :options="chartAlimentacion" class="card-img-top"></highcharts>

              <div class="card-body">
                <h2 class="card-title"><b>Porcentaje de pedidos</b></h2>
                <p class="card-text">
                  En está gráfica representamos el estado actual de los pedidos realizados.
                </p> 
                <p class="card-text small text-muted pull-right">{{ time_update }}</p>
              </div>
            </div>
          </div>
          <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="card">

              <highcharts :options="chartGratuidad" class="card-img-top"></highcharts>

              <div class="card-body">
                <h2 class="card-title"><b>Porcentaje de pedidos</b></h2>
                <p class="card-text">
                  En está gráfica representamos el estado actual de los pedidos realizados.
                </p> 
                <p class="card-text small text-muted pull-right">{{ time_update }}</p>
              </div>
            </div>
          </div>
          <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="card">

              <highcharts :options="chartUtiles" class="card-img-top"></highcharts>

              <div class="card-body">
                <h2 class="card-title"><b>Porcentaje de pedidos</b></h2>
                <p class="card-text">
                  En está gráfica representamos el estado actual de los pedidos realizados.
                </p> 
                <p class="card-text small text-muted pull-right">{{ time_update }}</p>
              </div>
            </div>
          </div>
          <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="card">

              <highcharts :options="chartValijas" class="card-img-top"></highcharts>

              <div class="card-body">
                <h2 class="card-title"><b>Porcentaje de pedidos</b></h2>
                <p class="card-text">
                  En está gráfica representamos el estado actual de los pedidos realizados.
                </p> 
                <p class="card-text small text-muted pull-right">{{ time_update }}</p>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <template v-for="(item, index) in data_disbursement">
            <div v-bind:key="index" class="col-sm-12 col-md-6 col-lg-6">
              <div class="card card-widget widget-user-2">
                <div class="widget-user-header bg-warning text-center">
                  <h3><b>{{ year(item.name) }}</b></h3>
                </div>
                <div class="card-footer p-0">
                    <div class="table-responsive">
                        <table class="table table-striped hover bordered">
                            <thead>
                                <tr class="text-center">
                                    <th>Código</th>
                                    <th>Desembolso</th>
                                    <th>Monto Apobado</th>
                                    <th>Monto Pedido</th>
                                    <th>Monto Facturado</th>
                                    <th>Disponible</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="(balance,index_balance) in item.balances">
                                    <tr v-bind:key="index_balance" :style="color_fila(balance.current)+' color: white;'">
                                        <td style="vertical-align:middle; font-size: 12px; text-align: center; font-weight: bold;">{{ balance.code }}</td>
                                        <td style="vertical-align:middle; font-size: 12px; text-align: left; font-weight: bold;">{{ balance.type_balance }}</td>
                                        <td style="vertical-align:middle; font-size: 12px; text-align: right; font-weight: bold;">{{ balance.balance | currency('Q',',',2,'.','front',true) }}</td>
                                        <td style="vertical-align:middle; font-size: 12px; text-align: right; font-weight: bold;">{{ balance.subtraction_temporary | currency('Q',',',2,'.','front',true) }}</td>
                                        <td style="vertical-align:middle; font-size: 12px; text-align: right; font-weight: bold;">{{ balance.subtraction | currency('Q',',',2,'.','front',true) }}</td>
                                        <td style="vertical-align:middle; font-size: 12px; text-align: right; font-weight: bold;">{{ balance.balance - balance.subtraction_temporary | currency('Q',',',2,'.','front',true) }}</td>
                                    </tr>                       
                                </template>
                            </tbody>
                        </table>   
                    </div>
                </div>
              </div>              
            </div>
          </template>
        </div>

      </div>
    </div>
  </div>

</template>

<script>
import moment from 'moment'
export default {
  name: 'Default',
  components: {
      moment 
  },
  data() {
      return {
          loading: false,
          data_alimetnacion: [],
          data_gratuidad: [],
          data_utiles: [],
          data_valija: [],
          data_disbursement: []
      }
  },
  created() {
    let self = this
    self.getData()
  },
  methods: {
    getData(){
      let self = this;
      self.loading = true;
      self.data_alimetnacion = []
      self.data_gratuidad = []
      self.data_utiles = []
      self.data_valija = []
        
      self.$store.state.services.graphService
        .getSchoolOrder()
        .then(r => {
            self.loading = false; 
            self.data_alimetnacion.push({name: 'Completo', y: r.data.data[0].order_complete_a})      
            self.data_alimetnacion.push({name: 'Incompleto', y: r.data.data[0].order_incomplete_a})   
            self.data_gratuidad.push({name: 'Completo', y: r.data.data[0].order_complete_g})      
            self.data_gratuidad.push({name: 'Incompleto', y: r.data.data[0].order_incomplete_g})   
            self.data_utiles.push({name: 'Completo', y: r.data.data[0].order_complete_u})      
            self.data_utiles.push({name: 'Incompleto', y: r.data.data[0].order_incomplete_u})    
            self.data_valija.push({name: 'Completo', y: r.data.data[0].order_complete_v})      
            self.data_valija.push({name: 'Incompleto', y: r.data.data[0].order_incomplete_v})  
        })
        .catch(r => {});
    },

    getInformation(){
      let self = this;
      self.loading = true;
      self.data_disbursement = []
        
      self.$store.state.services.informationschoolService
        .getDisbursement(self.$store.state.school.schools_id)
        .then(r => {
            self.loading = false; 
            self.data_disbursement = r.data
        })
        .catch(r => {});
    },

    color_fila(estado){
      if(estado)
        return 'background: green;';
      else
        return 'background: red;';
    },

    year(title){
      return title+" "+moment(new Date()).format('YYYY')
    }
  },
  computed: {
      chartAlimentacion() { 
          this.getInformation()
          return {
                  chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie',
                    styledMode: true
                  },
                  title: {
                    text: 'ALIMENTACION '+ moment(new Date()).format('YYYY')
                  },
                  tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                  },
                  plotOptions: {
                    pie: {
                      allowPointSelect: true,
                      cursor: 'pointer',
                      dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                      }
                    }
                  },
                  series: [{ 
                              name: 'Porcentaje',
                              colorByPoint: true,
                              allowPointSelect: true,
                              showInLegend: true,
                              data: this.data_alimetnacion
                          }],
            }
      },
      chartGratuidad() { 
          return {
                  chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie',
                    styledMode: true
                  },
                  title: {
                    text: 'GRATUIDAD '+ moment(new Date()).format('YYYY')
                  },
                  tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                  },
                  plotOptions: {
                    pie: {
                      allowPointSelect: true,
                      cursor: 'pointer',
                      dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                      }
                    }
                  },
                  series: [{ 
                              name: 'Porcentaje',
                              colorByPoint: true,
                              allowPointSelect: true,
                              showInLegend: true,
                              data: this.data_gratuidad
                          }],
            }
      },
      chartUtiles() { 
          return {
                  chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie',
                    styledMode: true
                  },
                  title: {
                    text: 'UTILES '+ moment(new Date()).format('YYYY')
                  },
                  tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                  },
                  plotOptions: {
                    pie: {
                      allowPointSelect: true,
                      cursor: 'pointer',
                      dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                      }
                    }
                  },
                  series: [{ 
                              name: 'Porcentaje',
                              colorByPoint: true,
                              allowPointSelect: true,
                              showInLegend: true,
                              data: this.data_utiles
                          }],
            }
      },
      chartValijas() { 
          return {
                  chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie',
                    styledMode: true
                  },
                  title: {
                    text: 'VALIJAS DIDACTICA '+ moment(new Date()).format('YYYY')
                  },
                  tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                  },
                  plotOptions: {
                    pie: {
                      allowPointSelect: true,
                      cursor: 'pointer',
                      dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                      }
                    }
                  },
                  series: [{ 
                              name: 'Porcentaje',
                              colorByPoint: true,
                              allowPointSelect: true,
                              showInLegend: true,
                              data: this.data_valija
                          }],
            }
      },
      time_update(){
        return moment(new Date()).format('dddd DD MMMM YYYY h:mm:ss a')
      }
  },

  mounted(){
        $("body").resize()
  }
}
</script>