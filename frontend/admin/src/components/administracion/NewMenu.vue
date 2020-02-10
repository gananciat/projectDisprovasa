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
<div class="row" v-loading="loading_detail">
    <div class="col-md-12 col-sm-12">
        <div class="form-group">
        <label>Menú</label>
        <input type="text" class="form-control" placeholder="titulo del menú"
        name="title"
        v-model="form.title"
        data-vv-as="titulo del menú"
        v-validate="'required|min:5|max:125'"
        data-vv-scope="menu"
        :class="{'input':true,'has-errors': errors.has('menu.title')}">
        <FormError :attribute_name="'menu.title'" :errors_form="errors"> </FormError>
        </div>
    </div>  
    <div class="col-md-12 col-sm-12">
        <div class="form-group">
        <label>Descripción</label>
        <textarea class="form-control" 
        cols="10" rows="3" 
        placeholder="descripción del menú"
        name="description"
        v-model="form.description"
        data-vv-as="descripción del menú"
        v-validate="'required|min:5|max:1000'"
        data-vv-scope="menu"
        :class="{'input':true,'has-errors': errors.has('menu.description')}">
        </textarea>
        <FormError :attribute_name="'menu.description'" :errors_form="errors"> </FormError>
        </div>
    </div> 
    <hr>
    <div class="col-md-12 col-sm-12">            
        <div class="form-group">
        <label>Producto</label>
        <multiselect v-model="product_id"
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
        v-model="observation"
        data-vv-as="observación del producto"
        v-validate="'min:5|max:1000'"
        data-vv-scope="detail"
        :class="{'input':true,'has-errors': errors.has('detail.observation')}">
        </textarea>
        <FormError :attribute_name="'detail.observation'" :errors_form="errors"> </FormError>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 text-right">
        <button type="button" class="btn btn-success btn-sm" v-b-tooltip.hover title="agregar" @click="addProductDetail">Agregar producto</button>
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
            <tbody v-if="form.detail_order.length >= 1">
                <template v-for="(item, index) in form.detail_order">
                    <tr v-bind:key="index" style="font-size: 14px;">
                        <td v-text="item.producto"></td>
                        <td v-text="item.observation"></td>
                        <td class="text-center"> 
                            <button class="btn btn-danger btn-sm" @click="quitarProductDetail(index)">
                            Quitar
                            </button>
                        </td>                    
                    </tr>
                </template>
            </tbody>          
        </table>
        </div>
    </div>   
    <div class="col-md-12 col-sm-12 text-right">
        <button type="button" class="btn btn-primary btn-md" v-b-tooltip.hover title="guardar" @click="create"><i class="fa fa-save"></i> Guardar</button>
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
  name: "newmenu",
  components: {
      FormError
  },
  data() {
    return {
      loading: false,
      loading_detail: false,
      products: [],
      product_id: null,
      observation: '',
      form: {
          title: '',
          description: '',
          detail_order: []
      }
    };
  },
  created() {
    let self = this;
    self.getProduct()
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

    //Agregar número de teléfono de la persona
    addProductDetail(){
      let self = this
      let encontro = false

        self.$validator.validateAll("detail").then((result) => {
            if (result) {
                
                self.loading_detail = true
                self.form.detail_order.forEach(function(item) {
                    if(item.products_id == self.product_id.id){
                    self.loading_detail = false
                    self.$swal({
                        title: "ADVERTENCIA",
                        text: "EL PRODUCTO "+ self.product_id.name + ", YA FUE AGREGADO.",
                        type: "warning",
                        showCancelButton: false
                    })
                    encontro = true
                    }
                })

                if(self.form.detail_order.length == 0 || encontro == false ) {
                    self.pasarMayusculas()
                    self.form.detail_order.push({ 
                                                    observation:self.observation,
                                                    products_id:self.product_id.id,
                                                    producto:self.product_id.name
                                                })
                    self.$toastr.success('producto agregado al menú.', 'información') 
                    self.product_id = null
                    self.observation = ''
                    self.loading_detail = false                
                }   
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

    //Quitar número de teléfono de la escuela
    quitarProductDetail(index) {
      let self = this
      self.loading_detail = true
      self.form.detail_order.splice(index, 1);
      self.loading_detail = false
    },  
    
    //funcion, validar si se guarda o actualiza
    create(){
      let self = this
      
      if(self.form.detail_order.length == 0){
        self.$toastr.error('No hay productos en el detalle del menú.', 'error')
        return
      }

      self.$validator.validateAll('menu').then((result) => {
          if (result) {
            self.pasarMayusculas()

            self.$swal({
                title: "ADVERTENCIA",
                text: "¿ ESTA SEGURO QUE DESEA GUARDAR EL MENU ?",
                type: "info",
                showCancelButton: true
            }).then((result) => {
                if (result.value) {
                    self.loading = true
                    let data = self.form       
                    self.$store.state.services.menusuggestionService
                    .create(data)
                    .then(r => {
                        self.loading = false
                        if( self.interceptar_error(r) == 0) return
                        self.$toastr.success('registro agregado con exito', 'exito') 
                        self.$router.push('/menu') 
                    })
                    .catch(r => {});                     
                }
            });

          } else {
            self.$toastr.error('Es necesario que ingrese información del Menú."', 'error')
          }
      });
    },    
  },

  mounted(){
    $("body").resize()
  },

};
</script>