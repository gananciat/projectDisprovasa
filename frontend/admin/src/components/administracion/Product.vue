<template>
<div v-loading="loading">
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Productos</h1> 
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
            <!-- Modal para nuevo registro -->
            <b-modal ref="new_price" title="Precios" hide-footer class="modal-backdrop" no-close-on-backdrop size="s">
                <price></price>
            </b-modal>
            <b-modal ref="nuevo" :title="title" hide-footer class="modal-backdrop" no-close-on-backdrop size="xl">
              <form>
                  <div class="row">
                      <div class="form-group col-md-4 col-sm-6 col-xs-12 col-lg-4">
                      <label>Nombre</label>
                      <input type="text" class="form-control" placeholder="nombre"
                      name="name"
                      v-model="form.name"
                      data-vv-as="nombre"
                      v-validate="'required'"
                      :class="{'input':true,'has-errors': errors.has('name')}">
                      <FormError :attribute_name="'name'" :errors_form="errors"> </FormError>
                      </div>
                      <div class="form-group col-md-4 col-sm-6 col-lg-4">
                          <label for="categoria">Categoría producto</label>
                          <multiselect v-model="category"
                              v-validate="'required'" 
                              data-vv-name="category"
                              data-vv-as="categoría producto"
                              :options="categories" placeholder="seleccione categoría producto"  
                              :searchable="true"
                              :allow-empty="false"
                              label="name" track-by="id">
                              <span slot="noResult">Ninguna categoría encontrada</span>
                              </multiselect>
                              <FormError :attribute_name="'category'" :errors_form="errors"> </FormError>
                      </div>
                        <div class="form-group col-md-4 col-sm-6 col-lg-4">
                          <label for="categoria">Marca producto</label>
                          <multiselect v-model="presentation"
                              v-validate="'required'" 
                              data-vv-name="presentation"
                              data-vv-as="marca producto"
                              :options="presentations" placeholder="seleccione marca del producto"  
                              :searchable="true"
                              :allow-empty="false"
                              label="name" track-by="id">
                              <span slot="noResult">Ninguna marca encontrada</span>
                              </multiselect>
                              <FormError :attribute_name="'presentation'" :errors_form="errors"> </FormError>
                      </div>
                  </div>
                  <div class="row">
                      <div class="form-group col-md-4 col-sm-6 col-xs-12 col-lg-4" v-if="form.id === null">
                          <label>Precio</label>
                          <input type="text" class="form-control" placeholder="precio"
                          name="price"
                          v-model="form.price"
                          data-vv-as="precio producto"
                          v-validate="'required|decimal'"
                          :class="{'input':true,'has-errors': errors.has('price')}">
                          <FormError :attribute_name="'price'" :errors_form="errors"> </FormError>
                      </div>
                      <div class="form-group col-md-4 col-sm-6 col-xs-12 col-lg-4">
                          <label></label>
                          <b-form-checkbox
                          v-model="form.camouflage"
                          name="camouflage"
                          >
                          ¿Producto facturado con otro nombre ?
                          </b-form-checkbox>

                          <div><strong>{{ form.camouflage ? 'Si':'No' }}</strong></div>
                      </div>
                  </div>
                  <div class="row">
                    <!-- /.col -->
                    <div class="col-12 text-right">
                      <button type="button" class="btn btn-danger btn-sm" @click="close"><i class="fa fa-undo"></i> Cancelar</button>
                      <button type="button" class="btn btn-primary btn-sm" @click="createOrEdit"><i class="fa fa-save"></i> Guardar</button>
                    </div>
                    <!-- /.col -->
                  </div>
                </form>
            </b-modal>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header no-border">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">Lista de productos 
                    <b-button variant="success" @click="open(1,'Alimentación')" size="sm"><i class="fa fa-plus"></i> alimentación</b-button>
                    <b-button variant="success" @click="open(2,'Gratuidad')" size="sm"><i class="fa fa-plus"></i> gratuidad</b-button>
                    <b-button variant="success" @click="open(3,'Utiles')" size="sm"><i class="fa fa-plus"></i> utiles</b-button>
                  </h3>
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
                      <template v-slot:cell(name)="data">
                        {{ data.item.name }}
                      </template>
                      <template v-slot:cell(category)="data">
                        {{data.item.category.name}}
                      </template>
                       <template v-slot:cell(presentation)="data">
                        {{data.item.presentation.name}}
                      </template>
                       <template v-slot:cell(price)="data">
                           {{getPrice(data.item.prices).price | currency('Q ')}}
                      </template>
                      <template v-slot:cell(option)="data">
                          <button type="button" class="btn btn-success btn-sm" @click="showPrice(data.item)" v-tooltip="'ver precios'">
                              <i class="fa fa-money">
                              </i>
                          </button>
                          <button type="button" class="btn btn-info btn-sm" @click="mapData(data.item)" v-tooltip="'editar'">
                              <i class="fa fa-pencil">
                              </i>
                          </button>
                          <button type="button" class="btn btn-danger btn-sm" @click="destroy(data.item)" v-tooltip="'eliminar'">
                              <i class="fa fa-trash">
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
import FormError from '../shared/FormError'
import Price from './ProductPrice'
export default {
  name: "category",
  components: {
      FormError,
      Price
  },
  data() {
    return {
      loading: false,
      items: [],
      categories: [],
      presentations: [],
      category: null,
      presentation: null,
      mensaje: '',
      fields: [
        { key: 'propierty', label: 'Pertenece', sortable: true },
        { key: 'name', label: 'Nombre', sortable: true },
        { key: 'category', label: 'Categoría', sortable: true },
        { key: 'presentation', label: 'Marca', sortable: true },
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
          name: '',
          camouflage: false,
          categories_id: null,
          presentations_id: null,
          price: null,
          propierty: null,
      }
    };
  },

  created() {
    let self = this
    self.getAll()
    self.getCategories()
    self.getPresentations()

    events.$on('update_products',self.onEventProduct)
  },

  beforeDestroy(){
      let self = this
      events.$off('update_products',self.onEventProduct)
  },

  methods: {

    onFiltered (filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length
      this.currentPage = 1
    },

    onEventProduct(){
        let self = this
        self.getAll()
    },

    getAll() {
      let self = this;
      self.loading = true;

      self.$store.state.services.productService
        .getAll()
        .then(r => {
          self.loading = false; 
          self.items = r.data.data;
          self.totalRows = self.items.length
        })
        .catch(r => {});
    },

    getPresentations(){
      let self = this;
      self.loading = true;

      self.$store.state.services.presentationService
        .getAll()
        .then(r => {
          self.loading = false; 
          self.presentations = r.data.data
        })
        .catch(r => {});
    },

    getCategories(){
      let self = this;
      self.loading = true;

      self.$store.state.services.categoryService
        .getAll()
        .then(r => {
          self.loading = false
          self.categories = r.data.data
        })
        .catch(r => {});
    },

    //funcion para guardar registro
    create(){
      let self = this
      self.loading = true
      let data = self.form
      data.categories_id = self.category.id
      data.presentations_id = self.presentation.id
      self.$store.state.services.productService
        .create(data)
        .then(r => {
          self.loading = false
          if(r.response){
            this.$toastr.error(r.response.data.error, 'error')
            return
          }
          this.$toastr.success('registro agregado con exito', 'exito')
          self.close()
          self.getAll()
        })
        .catch(r => {});
    },

    //funcion para actualizar registro
    update(){
      let self = this
      self.loading = true
      let data = self.form
       
      self.$store.state.services.productService
        .update(data)
        .then(r => {
          self.loading = false
          if(r.response){
            this.$toastr.error(r.response.data.error, 'error')
            return
          }
          this.$toastr.success('registro actualizado con exito', 'exito')
          self.getAll()
          self.close()
        })
        .catch(r => {});
    },

    //funcion para eliminar registro
    destroy(data){
      let self = this

      self.$swal({
        title: "¿Eliminar registro?",
        text: "Esta seguro de elminar "+data.name + '?',
        type: "warning",
        showCancelButton: true
      }).then((result) => { // <--
          if (result.value) { // <-- if confirmed
              self.loading = true
              self.$store.state.services.companyService
                .destroy(data)
                .then(r => {
                  self.loading = false
                  if(r.response){
                        this.$toastr.error(r.response.data.error, 'error')
                        return
                    }
                  this.$toastr.success('registro eliminado con exito', 'exito')
                  self.getAll()
                  self.close()
                })
                .catch(r => {});
          }
      });
    },

    //funcion, validar si se guarda o actualiza
    createOrEdit(){
      let self = this
      this.$validator.validateAll().then((result) => {
          if (result) {
              self.pasarMayusculas()
              self.form.id === null ? self.create() : self.update()
           }
      });
    },

    //pasar a mayusculas
    pasarMayusculas(){
        let self = this

        Object.keys(self.form).forEach(function(key,index) {
          
          if(typeof self.form[key] === "string") 
            self.form[key] = self.form[key].toUpperCase()

        });
    }, 

     //mapear datos a formulario
    mapData(data){
        let self = this
        self.form.id = data.id
        self.form.name = data.name
        self.form.categories_id = data.categories_id
        self.form.presentations_id = data.presentations_id
        self.form.camouflage = !!data.camouflage
        self.category = data.category
        self.presentation = data.presentation
        this.$refs['nuevo'].show()
    },

    //limpiar data de formulario
    clearData(){
        let self = this

        Object.keys(self.form).forEach(function(key,index) {
          if(typeof self.form[key] === "string") 
            self.form[key] = ''
          else if (typeof self.form[key] === "boolean") 
            self.form[key] = false
          else if (typeof self.form[key] === "number") 
            self.form[key] = null
        });

        self.$validator.reset()
        self.$validator.reset()
    },

    open(numero,titulo){
        let self = this
        self.mensaje = titulo
        self.form.propierty = numero
        this.$refs['nuevo'].show()
        self.clearData()
    },

    //cerrar modal limpiar registros
    close(){
        let self= this
        self.$refs['nuevo'].hide()
        self.$refs['new_price'].hide()
    },
    
    getPrice(prices){
        let self = this
        var price = prices.find(x=>x.current)
        if(price == undefined){
            return 'sin precio actual'
        }
        return price
    },

    showPrice(product){
        let self = this
        self.$refs['new_price'].show()
        self.$nextTick(()=>{
            events.$emit('product_price',product)
        })
    }
  },

  mounted(){
        $("body").resize()
  },

  computed:{
      title(){
          let self = this
          return self.form.id == null ? 'Nuevo producto de '+self.mensaje : 'Editar '+self.form.name
      },

  }
};
</script>