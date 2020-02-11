<template>
<div>

  <div class="content-wrapper" v-loading="loading">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Nuevo menú para sugerir</h1> 
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
<div class="row">
    <div class="col-md-12 col-sm-12">            
        <div class="form-group">
        <label>Producto</label>
        <multiselect v-model="form.product_id"
            v-validate="'required'" 
            data-vv-name="detail.product_id"
            data-vv-as="producto"
            :options="products" placeholder="seleccione producto"  
            :searchable="true"
            :allow-empty="false"
            :show-labels="false"
            data-vv-scope="detail"
            label="name" track-by="id">
            <span slot="noResult">No se encontro ningún registro</span>
            </multiselect>
            <FormError :attribute_name="'detail.product_id'" :errors_form="errors"> </FormError>
        </div>
    </div>   
    <div class="col-md-12 col-sm-12">
        <div class="form-group">
        <label>Observación</label>
        <textarea class="form-control" 
        cols="10" rows="3" 
        placeholder="observación del producto"
        name="observation"
        v-model="form.observation"
        data-vv-as="observación del producto"
        v-validate="'min:5|max:1000'"
        data-vv-scope="detail"
        :class="{'input':true,'has-errors': errors.has('detail.observation')}">
        </textarea>
        <FormError :attribute_name="'detail.observation'" :errors_form="errors"> </FormError>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 text-right">
        <button type="button" class="btn btn-primary btn-md" v-b-tooltip.hover title="guardar" @click="createOredit"><i class="fa fa-save"></i> Guardar</button>
    </div>  
    &nbsp;
    <div class="col-md-12 col-sm-12">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm">
                <thead style="font-size: 16px; text-align:center;">
                    <tr>
                        <th>Producto</th>
                        <th>Observación</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody v-if="items.length >= 1">
                    <template v-for="(item, index) in items[0].details">
                        <tr v-if="item.current" v-bind:key="index" style="font-size: 14px;">
                            <td>{{ item.product.name+" / "+item.product.presentation.name }}</td>
                            <td>{{ item.observation }}</td>
                            <td class="text-center"> 
                                <button type="button" class="btn btn-warning btn-sm" v-b-tooltip.hover v-b-tooltip.rightbottom title="editar" @click="mapData(item)">
                                    <i class="fa fa-pencil">
                                    </i>
                                </button>                                                          
                                <button type="button" class="btn btn-danger btn-sm" v-b-tooltip.hover v-b-tooltip.rightbottom title="eliminar" @click="destroy(item)">
                                    <i class="fa fa-trash">
                                    </i>
                                </button>
                            </td>                    
                        </tr>
                    </template>
                </tbody>          
            </table>
        </div>
    </div>                           
</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</template>

<script>
import FormError from '../shared/FormError'
export default {
  name: "editmenu",
  components: {
      FormError
  },
  data() {
    return {
      loading: false,
      items: [],
      products: [],
      menu: '',
      form: {
          id: 0,
          observation: '',
          product_id: null,
          menu_suggestions_id: null
      }
    };
  },
  created() {
    let self = this;
    self.getProduct()
    self.getAll(self.$route.params.id)
  },

  methods: {
    //Clasificar error
    interceptar_error(r){
      let self = this
      let error = 1;

        if(r.response){
            if(r.response.status === 422){
                this.$toastr.info(r.response.data.error, 'Mensaje')
                error = 0
            }

            if(r.response.status != 201 && r.response.status != 422){
                for (let value of Object.values(r.response.data)) {
                    self.$toastr.error(value, 'Mensaje')
                }
                error = 0
            }
        }
      
      return error
    },

    getAll(id) {
      let self = this;
      self.loading = true;
      self.form.id = 0
      self.form.menu_suggestions_id = id

      self.$store.state.services.menusuggestionService
        .get(self.form.menu_suggestions_id)
        .then(r => {
            self.loading = false; 
            self.items = r.data.data
            self.menu = r.data.data[0].title
        })
        .catch(r => {});
    }, 

    //Llamar todos los productos que pertenecen al tipo de orden seleccionada
    getProduct(){
      let self = this
      self.loading = true

      self.$store.state.services.productService
        .get('ALIMENTACION')
        .then(r => {
          self.products = []
          if(r.response){
            self.$toastr.error(r.response.data.error, 'error')
            return
          }
          r.data.data.forEach(function(item) {
            self.products.push({id: item.id, name: item.name+' / '+item.presentation.name})
          })
          self.loading = false
        })
        .catch(r => {});      
    },
    
    //pasar a mayusculas
    pasarMayusculas(){
        let self = this

        Object.keys(self.form).forEach(function(key,index) {
          if(typeof self.form[key] === "string") 
            self.form[key] = self.form[key].toUpperCase()
        });
    },   
    
    //funcion, validar si se guarda o actualiza
    createOredit(){
      let self = this      
      self.$validator.validateAll('detail').then((result) => {
          if (result) {
              self.loading = true

                if(self.form.id > 0)
                    self.update()
                else
                    self.create()

           }
      });
    }, 

    //mapear datos a formulario
    mapData(data){
        let self = this
        self.form.id = data.id
        self.form.observation = data.observation
        self.form.product_id = {id: data.id, name: data.product.name+" / "+data.product.presentation.name}
        self.form.menu_suggestions_id = data.menu_suggestions_id
    },   

    create(){
        let self = this
        let data = self.form    
        data.product_id = self.form.product_id.id   
        self.$store.state.services.detailsuggestionService
        .create(data)
        .then(r => {
            self.loading = false
            if( self.interceptar_error(r) == 0) return
            self.$toastr.success('registro agregado con exito', 'exito') 
            self.form.id = 0
            self.form.observation = ''
            self.form.product_id = null
            self.form.menu_suggestions_id = null
            self.getAll(self.$route.params.id)
        })
        .catch(r => {});  
    },

    //funcion, validar si se guarda o actualiza
    update(){
        let self = this      
        let data = self.form
        data.product_id = self.form.product_id.id
        self.loading = true
        self.pasarMayusculas()
        self.$store.state.services.detailsuggestionService
            .update(data)
            .then(r => {
                self.loading = false
                if( self.interceptar_error(r) == 0) return 
                self.$toastr.success('registro actualizado con exito', 'exito') 
                self.form.id = 0
                self.form.observation = ''
                self.form.product_id = null
                self.form.menu_suggestions_id = null
                self.getAll(self.$route.params.id)
            })
            .catch(r => {});
    },  
    
    //funcion para eliminar registro
    destroy(data){
      let self = this

      self.$swal({
        title: "¿ELIMINAR EL PRODUCTO?",
        text: "¿ ESTA SEGURO QUE DESEA ELIMINAR EL PRODUCTO DEL MENU ?",
        type: "warning",
        showCancelButton: true
      }).then((result) => {
          if (result.value) { 
              self.loading = true
              self.$store.state.services.detailsuggestionService
                .destroy(data)
                .then(r => {
                    self.loading = false
                    if( self.interceptar_error(r) == 0) return
                    self.$toastr.success('registro eliminado con exito', 'exito') 
                    self.getAll(self.$route.params.id)
                })
                .catch(r => {});
          }
      });
    },    
  },

  mounted(){
    $("body").resize()
  },

};
</script>