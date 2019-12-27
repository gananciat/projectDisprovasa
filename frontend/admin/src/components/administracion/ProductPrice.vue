<template>
<div v-loading="loading">
    <!-- Modal para nuevo registro -->
       <div class="">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Precios de producto {{product.name}} </h1> 
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 col-lg-4 col-xs-12">
              
              <div class="card">
                <div class="card-header no-border">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">Nuevo</h3>
                </div>
              </div>
                <form>
                  <div class="row container">
                    
                    <div class="form-group col-lg-12">
                    <label>Precio</label>
                    <input type="text" class="form-control" placeholder="precio producto"
                    name="price"
                    v-model="form.price"
                    data-vv-as="precio producto"
                    v-validate="'required|decimal'"
                    :class="{'input':true,'has-errors': errors.has('price')}">
                    <FormError :attribute_name="'price'" :errors_form="errors"> </FormError>
                    </div>
                    
                    <div class="col-md-12 col-lg-12 text-right form-group">
                        <button type="button" class="btn btn-danger btn-sm" @click="clearData" v-tooltip="'limpiar formulario'"><i class="fa fa-eraser"></i></button>
                        <button type="button" class="btn btn-primary btn-sm" @click="createOrEdit" v-tooltip="'guardar precio'"><i class="fa fa-save"></i> </button>
                    </div>
                  </div>
                </form>
              </div>
              </div>
            <div class="col-md-8">
              <div class="card">
                
              <div class="card-header no-border">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">Lista de precios </h3>
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
                    <b-table responsive hover small flex
                        bordered
                       :fields="fields" 
                       :items="items"
                       :filter = "filter"
                       :current-page="currentPage"
                       :per-page="perPage"
                       @filtered="onFiltered">
                      <!-- A virtual column -->
                       <template v-slot:cell(price)="data">
                         <small :class="data.item.current ? 'badge badge-success':'badge badge-danger'"> {{ data.item.price | currency('Q ') }}</small>
                      </template>
                      <template v-slot:cell(option)="data">
                          <button type="button" class="btn btn-danger btn-sm" @click="destroy(data.item)" v-tooltip="'remover'">
                              <i class="fa fa-minus">
                              </i>
                          </button>
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
import FormError from '../shared/FormError'
export default {
  name: "product_price",
  components: {
      FormError
  },
  data() {
    return {
      loading: false,
      items: [],
      product: {},
      fields: [
        { key: 'price', label: 'Precio', sortable: true },
        { key: 'option', label: 'Opciones', sortable: true },
      ],
      filter: null,
      currentPage: 1,
      perPage: 5,
      totalRows: 0,
      pageOptions: [ 5, 10, 25 ],
      showStringEmpty: 'no hay registros que mostrar',

      form: {
          id: null,
          price: null,
          products_id: null
      }
    };
  },
  created() {
    let self = this;
    //self.getAll();
    events.$on('product_price',self.onEventPrice)
  },

  beforeDestroy(){
    let self = this
    events.$off('product_price',self.onEventPrice)
  },

  methods: {

    onFiltered (filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length
      this.currentPage = 1
    },

    onEventPrice(product){
        let self = this
        self.product = product
        self.getAll(product.id)
        self.clearData()
    },

    getAll(id) {
      let self = this;
      self.loading = true;

      self.$store.state.services.productService
        .getPrices(id)
        .then(r => {
          self.loading = false;
          self.items = r.data.data
          self.items.sort(function(a, b) {
              return b.id - a.id;
          });

          self.totalRows = self.items.length
        })
        .catch(r => {});
    },

    //funcion para guardar registro
    create(){
      let self = this
      self.loading = true
      let data = self.form
      data.products_id = self.product.id
      self.$store.state.services.priceService
        .create(data)
        .then(r => {
          self.loading = false
          if(r.response){
            this.$toastr.error(r.response.data.error, 'error')
            return
          }
          this.$toastr.success('registro agregado con exito', 'exito')
          self.getAll(self.product.id)
          self.clearData()
          events.$emit('update_products')
        })
        .catch(r => {});
    },

    //funcion para actualizar registro
    update(){
      let self = this
      self.loading = true
      let data = self.form
       
      self.$store.state.services.priceService
        .update(data)
        .then(r => {
          self.loading = false
          if(r.response){
            this.$toastr.error(r.response.data.error, 'error')
            return
          }
          this.$toastr.success('registro actualizado con exito', 'exito')
          self.$emit('update_products')
        })
        .catch(r => {});
    },

    //funcion para eliminar registro
    destroy(data){
      let self = this
      self.$swal({
        title: "¿Eliminar registro?",
        text: "Esta seguro de elminar "+data.price + '?',
        type: "warning",
        showCancelButton: true
      }).then((result) => { // <--
          if (result.value) { // <-- if confirmed
              self.loading = true
              self.$store.state.services.priceService
                .destroy(data)
                .then(r => {
                  self.loading = false
                  if(r.response){
                        this.$toastr.error(r.response.data.error, 'error')
                        return
                    }
                  this.$toastr.success('registro eliminado con exito', 'exito')
                  self.getAll(self.product.id)
                  events.$emit('update_products')
                })
                .catch(r => {});
          }
      });
    },

    //funcion, validar si se guarda o actualiza
    createOrEdit(){
      this.$validator.validateAll().then((result) => {
          if (result) {
              self.form.id === null ? self.create() : self.update()
           }
      });

      let self = this
    },

     //mapear datos a formulario
    mapData(data){
        let self = this
        self.form.id = data.id
        self.form.products_id = data.products_id
        self.form.price = data.price
    },

    //limpiar data de formulario
    clearData(){
        let self = this

        self.form.price = null

        self.$validator.reset()
        self.$validator.reset()
    },

    open(){
        let self = this
        self.clearData()
    },

    //cerrar modal limpiar registros
    close(){
        let self= this
    }
  },

  mounted(){
        $("body").resize()
  },

  computed:{
      title(){
          let self = this
          return self.form.id == null ? 'Nueva precio' : 'Editar '+self.form.price
      }
  }
};
</script>