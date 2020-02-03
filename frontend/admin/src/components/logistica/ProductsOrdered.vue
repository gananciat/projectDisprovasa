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
            <h1 class="m-0 text-dark">CANTIDAD COMPRADA POR PRODUCTOS FILTRADO POR FECHAS</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content" v-loading="loading">
      <div class="container-fluid" style="height:100px;">
        <div class="row" >
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header no-border">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">LISTA DE PRODUCTOS</h3>
                </div>
              </div>
              <div class="card-body">
                  
              <div>
                  <div>
                      <form>
                        <div class="row">
                          <div class="form-group col-md-4 col-lg-4 col-xs-12">
                            <label>inicio</label>
                            <input type="date" class="form-control" placeholder="fecha inicio"
                            name="fecha_inicio"
                            v-model="form.start_date"
                            data-vv-as="fecha inicio"
                            @change="getData"
                            v-validate="'required'"
                          :class="{'input':true,'has-errors': errors.has('fecha_innicio')}">
                          <FormError :attribute_name="'fecha_inicio'" :errors_form="errors"> </FormError>
                          </div>
                          <div class="form-group col-md-4 col-lg-4 col-xs-12">
                            <label>fin</label>
                            <input type="date" class="form-control" placeholder="fecha fin"
                            name="fecha_fin"
                            v-model="form.end_date"
                            data-vv-as="fecha fin"
                            @change="getData"
                            v-validate="dateRules(form)"
                          :class="{'input':true,'has-errors': errors.has('fecha_fin')}">
                          <FormError :attribute_name="'fecha_fin'" :errors_form="errors"> </FormError>
                          </div>
                        </div>
                      </form>
                      <div class="col-md-12" v-if="show_form">
                        <h4>del {{form.start_date | moment('dddd, DD MMMM YYYY')  }} al {{form.end_date | moment('dddd, DD MMMM YYYY')  }}</h4>
                      </div>
                      <el-divider></el-divider>
                      
                    <button v-if="show_form" @click="exportExel" class="btn btn-success btn-sm" type="button"><i class="fa fa-file-excel-o"></i> exportar</button>
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
                      {{ data.item.product }}
                    </template>
                    <template v-slot:cell(original_quantity)="data">
                      {{data.item.original_quantity}}
                    </template>
                    <template v-slot:cell(subtraction)="data">
                      {{data.item.quantity}}
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
                        <!-- /.card -->
                        <!-- /.card -->
              </div>
              </div>
            </div>
            <!-- /.card -->
            <!-- /.card -->
          </div>
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
import FormError from '../shared/FormError'
import fileSaver from 'file-saver'
export default {
  name: "ProductsPurcharsed",
  components: {
      FormError
  },
  data() {
    return {
      loading: false,
      show_form: false,
      items: [],
      fields: [
        { key: 'product_name', label: 'Producto', sortable: true },
        { key: 'original_quantity', label: 'Cantidad pedida', sortable: true },
        { key: 'quantity', label: 'Cantidad entregada', sortable: true },
      ],
      filter: null,
      currentPage: 1,
      perPage: 5,
      totalRows: 0,
      pageOptions: [ 5, 10, 25 ],
      showStringEmpty: 'no hay registros que mostrar',
      form: {
        start_date: '',
        end_date: ''
      }
    };
  },
  created() {
    let self = this;
  },

  methods: {
    onFiltered (filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length
      this.currentPage = 1
    },

    getAll() {
      let self = this;
      var data = self.form
      self.loading = true
      self.$store.state.services.reportService
        .getOrderProduct(data.start_date, data.end_date)
        .then(r => {
          self.loading = false
          self.items = r.data.data
          self.totalRows = self.items.length
        })
        .catch(r => {});
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

    dateRules (item) {
      if(item.start_date !== ''){
        return {
          required: true,
          date_format: 'yyyy-MM-dd',
          after: item.start_date
        };
      }
      return {
        required: true
      }
        
    },

    getData(){
      let self = this
      if(self.form.start_date !== '' && self.form.end_date !== ''){
        self.$validator.validateAll().then((result) => {
          if (result) {
            self.show_form = true
            self.getAll()
          }else{
            self.show_form = false
          }
        });
      }
    },

    exportExel(id){
        let self = this
        var data = self.form
        self.loading = true
        self.$store.state.services.reportService
            .exportOrderProduct(data.start_date, data.end_date)
            .then(response => {
              self.loading = false
                var file_name = 'productos_pedidos_'+data.start_date+'_'+data.end_date
                if(response.response){
                    this.$toastr.error(r.response.data.error, 'error')
                    return
                }
                var blob = new Blob([response.data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
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