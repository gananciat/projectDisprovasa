<template>
<div v-loading="loading">
  <div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Productos faltantes</h1> 
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header no-border">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">Lista de productos faltantes </h3>
                </div>
              </div>
              <div class="card-body">
                <div class="d-flex">
                  <p class="d-flex flex-column">
                  </p>
                </div>
                <!-- /.d-flex -->
                 <template>

                    <div class="col-sm-12">
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
                        {{ data.item.product.name }}
                      </template>
                      <template v-slot:cell(subtraction)="data">
                        {{data.item.subtraction}}
                      </template>
                      <template v-slot:cell(year)="data">
                        {{data.item.year}}
                      </template>

                      <template slot="row-details"  slot-scope="data">
                        <div v-loading="loading_table">
                            <div v-if="providers.length > 0" class="row">
                              
                              <div class="col-md-6 col-sm-6 col-lg-6 col-xs-12">
                                <div class="card" style="font-size: 14px;">
                                  <!-- /.card-header -->
                                  <div class="card-body">
                                    <h3 class="card-title">Top 3 de compras, precios mas bajos en los ultimos 6 meses</h3>

                                    <table class="table table-condensed">
                                      <thead>
                                        <tr>
                                          <th>Fecha</th>
                                          <th>Proveedor</th>
                                          <th>Precio</th>
                                          <th>Cantidad</th>
                                          <th>Merma</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <tr v-for="(item,i) in provider_low_prices" :key="item.id">
                                          <td>{{item.purchase.date | moment('DD/MM/YYYY')}}</td>
                                          <td>{{item.purchase.provider.name}}</td>
                                          <td>{{item.purcharse_price | currency('Q ')}}</td>
                                          <td>{{item.quantity}}</td>
                                          <td>{{item.decrease}}</td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </div>
                                  <!-- /.card-body -->
                                </div>
                              </div>

                              <div class="col-md-6 col-sm-6 col-lg-6 col-xs-12">
                                <div class="card" style="font-size: 14px;">
                                  <!-- /.card-header -->
                                  <div class="card-body">
                                    <h3 class="card-title">Top 3 de compras, precios mas altos en los ultimos 6 meses</h3>

                                    <table class="table table-condensed">
                                      <thead>
                                        <tr>
                                          <th>Fecha</th>
                                          <th>Proveedor</th>
                                          <th>Precio</th>
                                          <th>Cantidad</th>
                                          <th>Merma</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <tr v-for="(item,i) in provider_high_prices" :key="item.id">
                                          <td>{{item.purchase.date | moment('DD/MM/YYYY')}}</td>
                                          <td>{{item.purchase.provider.name}}</td>
                                          <td>{{item.purcharse_price | currency('Q ')}}</td>
                                          <td>{{item.quantity}}</td>
                                          <td>{{item.decrease}}</td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </div>
                                  <!-- /.card-body -->
                                </div>
                              </div>
                              <div class="col-md-4 col-sm-4 col-xs-12 col-lg-4" v-if="last_purchase !== null">
                                  <div class="card">
                                    <div class="card-header">
                                      <h3 class="card-title">Ultima compra</h3>
                                    </div>
                                    <div class="card-body p-0">
                                      <div class="form-group">
                                        <div class="col-sm-12 invoice-col">
                                          <address>
                                            <strong>{{last_purchase.purchase.provider.name}}.</strong><br>
                                            <strong>NIT: </strong> {{last_purchase.purchase.provider.nit}}<br>
                                            <strong>Precio: </strong> {{last_purchase.purcharse_price | currency('Q ')}}<br>
                                            <strong>Cantidad: </strong> {{last_purchase.quantity}}<br>
                                            <strong>Merma: </strong> {{last_purchase.decrease}}<br>
                                          </address>
                                        </div>
                                        <!-- /.col -->
                                      </div>
                                    </div>
                                    <!-- /.card-body -->
                                  </div>
                              </div>
                               <div class="col-md-8 col-sm-8 col-lg-8 col-xs-12">
                                <div class="card" style="font-size: 14px;">
                                  <!-- /.card-header -->
                                  <div class="card-body">
                                    <h3 class="card-title">Precios promedio por proveedor</h3>

                                    <table class="table table-condensed">
                                      <thead>
                                        <tr>
                                          <th>Proveedor</th>
                                          <th>Precio promedio</th>
                                          <th>Cantidad comprada</th>
                                          <th>Merma</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <tr v-for="(item,i) in providers" :key="item.provider">
                                          <td>{{item.provider}}</td>
                                          <td>{{item.average | currency('Q ')}}</td>
                                          <td>{{item.quantity}}</td>
                                          <td>{{item.decrease}}</td>
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
                                <label> no se encontro historial de compras de producto {{data.item.product.name | lowercase  }} </label>
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
                  </template>
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
</div>
</template>

<script>
import moment from 'moment'
import FormError from '../shared/FormError'
export default {
  name: "missing_product",
  components: {
      FormError
  },
  data() {
    return {
      loading: false,
      loading_table: false,
      items: [],
      providers: [],
      provider_low_prices: [],
      provider_high_prices: [],
      last_purchase: null,
      fields: [
        { key: 'product_name', label: 'Producto', sortable: true },
        { key: 'subtraction', label: 'Faltante', sortable: true },
        { key: 'year', label: 'Año', sortable: true },
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

      self.$store.state.services.quantifyService
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
          self.last_purchase = null
          r.data.data = r.data.data.filter(x=>!x.purchase.cancel)
          if(r.data.data.length > 0){
            self.last_purchase = r.data.data[0]
          }
          self.providers = self.mapDataProduct(r.data.data)
          self.provider_low_prices = self.getPrices(r.data.data)
          self.provider_high_prices = self.getPrices(r.data.data,false)
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
              average: self.getAverage(items),
              quantity: self.getQuantity(items),
              decrease: self.getDecrease(items)
          };
      }).value();

      return _.sortBy(providers, o => o.average)
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
    getAverage(items){
      var total = items.reduce((a,b)=>{
          return a + parseFloat(b.purcharse_price)
      },0)

      return total / items.length
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
      self.getPurchases(data.products_id)
    },
  },



  computed:{
  }
};
</script>

<style scoped>

</style>