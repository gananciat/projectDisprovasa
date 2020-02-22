<template>
<div v-loading="loading">

      <div class="content-wrapper">
        <div class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-7">
                <h1 class="m-0 text-dark">REPORTE PRECIO DE PRODUCTOS POR PROVEEDOR</h1> 
              </div><!-- /.col -->
              <div class="col-sm-5">
                <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#/report">REPORTES</a></li>
                <li class="breadcrumb-item active">PRODUCTO PROVEEDORES</li>
                </ol>
            </div>
            </div><!-- /.row -->
          </div><!-- /.container-fluid -->
        </div>

        <div class="content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header no-border">
                    <div class="d-flex justify-content-between">
                      <h3 class="card-title">Lista de precios de productos por proveedor 
                        <b-button @click="exportExel()" variant="success" size="sm"><i class="fa fa-file-excel-o"></i> exportar</b-button></h3>
                    </div>
                  </div>
                  <div class="card-body">
                    <b-row>

                      <b-col md="4" class="my-1 form-inline">
                        <label>mostrando: </label>
                            <b-form-select :options="pageOptions" v-model="perPage" />
                          <label>entradas </label>
                      </b-col>

                      <b-col  class="my-1 form-group pull-right">
                          <b-input-group>
                            <b-form-input v-model="filter" placeholder="buscar" />
                          </b-input-group>
                      </b-col>
                    </b-row>
                    <b-table responsive hover flex
                        bordered
                        :fields="fields" 
                        :items="items"
                        :filter = "filter"
                        :current-page="currentPage"
                        :per-page="perPage"
                        @filtered="onFiltered">
                      <!-- A virtual column -->
                      
                      <template v-slot:cell(product_name)="data">
                        <span>
                          <a @click="showDetails(data.item)" href="javascript:void(0);" class="text-info pull-left"> <i :class="data.detailsShowing ? 'fa fa-arrow-down' : 'fa fa-arrow-right'"></i></a>
                        </span>&nbsp;
                        {{ data.item.name }}
                      </template>

                      <template slot="row-details"  slot-scope="data">
                        <div v-loading="loading_table">
                            <div v-if="providers.length > 0" class="row">
                                <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
                                <div class="card" style="font-size: 14px;">
                                  <!-- /.card-header -->
                                  <div class="card-body">
                                    <h3 class="card-title">Cantidad comprada por proveedor</h3>

                                    <table class="table table-condensed">
                                      <thead>
                                        <tr>
                                          <th>Proveedor</th>
                                          <th>Cantidad comprada</th>
                                          <th>Merma</th>
                                          <th>Total comprado</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <tr v-for="(item,i) in providers" :key="item.provider">
                                          <td>{{item.provider}}</td>
                                          <td>{{item.quantity}}</td>
                                          <td>{{item.decrease}}</td>
                                          <td>{{item.total | currency('Q ')}}</td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </div>
                                  <!-- /.card-body -->
                                </div>
                              </div>

                              
                            </div>
                            <div v-else>
                              <div class="text-center alert alert-info">
                                <label> no se encontro historial de compras de producto {{data.item.name | lowercase  }} </label>
                              </div>
                            </div>
                        </div>
                      </template>

                    </b-table>
                    <b-row>
                        <b-col md="12" class="my-1">
                            <label v-if="totalRows > 0">total: {{totalRows}} registros</label> 
                            <div class="text-center">
                              <label v-if="totalRows === 0">No hay registros que mostrar</label> 
                            </div>   
                          </b-col>
                          <div class="pull-right col-md-12">
                          <div class="mt-3" v-if="totalRows > 0">
                              <b-pagination 
                                v-model="currentPage" 
                                :per-page="perPage" 
                                :total-rows="totalRows" 
                                align="right"
                                first-text="⏮"
                                prev-text="⏪"
                                next-text="⏩"
                                last-text="⏭">
                              </b-pagination>
                            </div>
                          </div>
                    </b-row>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
            <!-- /.card -->
            <!-- /.card -->
  </div>
</template>

<script>
import moment from 'moment'
import fileSaver from 'file-saver'
export default {
  name: "product_Wprovider",
  components: {
  },
  data() {
    return {
      loading: false,
      loading_table: false,
      items: [],
      providers: [],
      fields: [
        { key: 'product_name', label: 'Producto', sortable: true }
      ],
      filter: null,
      currentPage: 1,
      perPage: 5,
      totalRows: 0,
      pageOptions: [ 5, 10, 25 ],
      showStringEmpty: 'no hay registros que mostrar'
    };
  },
  created() {
    let self = this;
    self.getAll()
  },

  methods: {
    onFiltered (filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length
      this.currentPage = 1
    },

    getAll() {
      let self = this;
      self.loading = true;
      self.$store.state.services.productService
        .getAll()
        .then(r => {
          self.loading = false
          r.data.data = r.data.data.map(obj=>({ ...obj, _showDetails: false}))
          self.items = r.data.data
          self.totalRows = self.items.length
        })
        .catch(r => {});
    },

    //obtener detalle de compras
    getPurchases($id) {
      let self = this;
      self.loading_table = true;

      self.$store.state.services.productService
        .getPurchases($id)
        .then(r => {
          self.loading_table = false
          r.data.data = r.data.data.filter(x=>!x.purchase.cancel)
          if(r.data.data.length > 0){
            self.last_purchase = r.data.data[0]
          }
          self.providers = self.mapDataProduct(r.data.data)
        })
        .catch(r => {});
    },

    //mapear data información
    mapDataProduct(data){
      let self = this
      var providers = _(data)
          .groupBy('purchase.provider_id')
          .map(function(items, provider_id) {
           var purchase = items.find(x=>x.purchase.provider_id === parseInt(provider_id))
          return {
              provider: purchase.purchase.provider.name,
              total: self.getTotal(items),
              quantity: self.getQuantity(items),
              decrease: self.getDecrease(items)
          };
      }).value();

      return _.sortBy(providers, o => o.total).reverse()
    },

    //get top 3 low prices of last six month
    getPrices(data,asc=true){
      let self = this
      var date = moment().subtract(6, 'months').format('YYYY/MM/DD')
      var ordered_data = asc ? _.sortBy(data, o => parseFloat(o.purcharse_price)) : _.sortBy(data, o => parseFloat(o.purcharse_price)).reverse()
      ordered_data = ordered_data.filter(x=>moment(x.purchase.date).format('YYYY/MM/DD') >= date)
      ordered_data = ordered_data.slice(0,3)
      return ordered_data
    },

    //obtener promedio
    getTotal(items){
      var total = items.reduce((a,b)=>{
          return a + parseFloat(b.purcharse_price * b.quantity)
      },0)

      return total
    },

    //obtener cantidad
    getQuantity(items){
      var total = items.reduce((a,b)=>{
          return a + b.quantity
      },0)
      return total
    },

    //obtener merma
    getDecrease(items){
      var total = items.reduce((a,b)=>{
          return a + b.decrease
      },0)
      return total
    },

    showDetails(data){
      let self = this
      if(data._showDetails){
        data._showDetails = false
        return
      }
      self.items.map(a=>a._showDetails=false)
      data._showDetails = true
      self.getPurchases(data.id)
    },

    exportExel(id){
        let self = this
        self.loading = true
        self.$store.state.services.reportService
            .exportProductProviders()
            .then(r => {
              self.loading = false
                var file_name = 'productos_pedidos_'
                if(self.$store.state.global.captureError(r)){
                  return
                }
                var blob = new Blob([r.data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                fileSaver.saveAs(blob, file_name);
                a.click();
            })
            .catch(r => {});
        }
  },



  computed:{
  }
};
</script>

<style scoped>

</style>