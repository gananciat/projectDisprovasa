<template>
<div>

  <div class="content-wrapper" v-loading="loading">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">

        </div>
      </div>
    </div>

    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            
            <div class="card">
              <div class="card-header border-0">
                <h3 class="card-title">Aregar nuevo pedido de {{ title }}</h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12 col-sm-12">
                    <div class="card card-primary card-tabs">
                      <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                          <li class="nav-item">
                            <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Información del Menú</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" @click="generationOrder" id="custom-tabs-one-settings-tab" data-toggle="pill" href="#custom-tabs-one-settings" role="tab" aria-controls="custom-tabs-one-settings" aria-selected="false">Pedido</a>
                          </li>
                        </ul>
                      </div>
                      <div class="card-body">
<form> 
  <div class="tab-content" id="custom-tabs-one-tabContent">
    <div class="tab-pane fade active show" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
      <div class="row">
        <div class="col-md-4 col-sm-12">
          <div class="form-group">
            <label>Fecha para la entrega del pedido</label>
            <div class="block">
              <el-date-picker
                v-model="form.date"
                type="date"
                data-vv-name="date"
                data-vv-as="fecha"
                v-validate="'required|date_format:yyyy-MM-dd'"
                :class="{'input':true,'has-errors': errors.has('date')}"                
                placeholder="Seleccionar una fecha"
                format="dd/MM/yyyy"
                value-format="yyyy-MM-dd">
              </el-date-picker>
            </div>
            <FormError :attribute_name="'date'" :errors_form="errors"> </FormError>
          </div>
        </div>
        <div class="col-md-12 col-sm-12">
          <div class="form-group">
            <label>Menú</label>
            <input type="text" class="form-control" placeholder="titulo del menú"
            name="title"
            v-model="form.title"
            data-vv-as="titulo del menú"
            v-validate="'required|min:5|max:125'"
            :class="{'input':true,'has-errors': errors.has('title')}">
            <FormError :attribute_name="'title'" :errors_form="errors"> </FormError>
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
            :class="{'input':true,'has-errors': errors.has('description')}">
            </textarea>
            <FormError :attribute_name="'description'" :errors_form="errors"> </FormError>
          </div>
        </div>                
      </div>
    </div>

    <div class="tab-pane fade" id="custom-tabs-one-settings" role="tabpanel" aria-labelledby="custom-tabs-one-settings-tab">
      <div class="row">
        <div class="col-md-8 col-sm-12">
          <div class="row">          
            <div class="col-md-12 col-sm-12">            
              <div class="form-group">
                <label>Producto</label>
                <multiselect v-model="product_id"
                    @input="information"
                    v-validate="'required'" 
                    data-vv-name="product_id"
                    data-vv-as="producto"
                    :options="products" placeholder="seleccione producto"  
                    :searchable="true"
                    :allow-empty="false"
                    :show-labels="false"
                    data-vv-scope="detail"
                    label="name" track-by="id">
                    <span slot="noResult">No se encontro ningún registro</span>
                    </multiselect>
                    <FormError :attribute_name="'product_id'" :errors_form="errors"> </FormError>
              </div>
            </div>
            <div class="col-md-6 col-sm-12 text-center">
              <div class="form-group">
                <label>Categoría</label>
                <h1>{{ information_product.category }}</h1>
              </div>
            </div>     
            <div class="col-md-6 col-sm-12 text-center">
              <div class="form-group">
                <label>Marca</label>
                <h1>{{ information_product.marca }}</h1>
              </div>
            </div>              
            <div class="col-md-4 col-sm-12">
              <div class="form-group">
                <label>Cantidad</label>
                <el-input-number v-model="quantity" 
                :precision="2" :step="0.1" :min="1" :max="10000"
                data-vv-name="quantity"
                data-vv-as="cantidad"
                v-validate="'required|between:1,10000'"
                data-vv-scope="detail"
                :class="{'input':true,'has-errors': errors.has('quantity')}"></el-input-number> <br>
                <FormError :attribute_name="'quantity'" :errors_form="errors"> </FormError>
              </div>
            </div>    
            <div class="col-md-4 col-sm-12 text-right">
              <div class="form-group">
                <label>Precio Unitario</label>
                <h1>{{ information_product.price | currency('Q ') }}</h1>
              </div>
            </div>     
            <div class="col-md-4 col-sm-12 text-right">
              <div class="form-group">
                <label>Sub Total</label>
                <h1>{{ information_product.sub_total = quantity * information_product.price | currency('Q ') }}</h1>
              </div>
            </div>                     
            <div class="col-md-12 col-sm-12 text-right">
              <button type="button" class="btn btn-success btn-sm" @click="addProductDetail">Agregar producto</button>
            </div>      
          </div>
        </div>
        <div class="col-md-4 col-sm-12">
          <div class="row">
            <div class="col-md-12 col-sm-12 text-right">
              <p><label style="font-size:32px;">Pedido #</label> <label style="font-size:48px;">{{ no_reservation }}</label></p>
            </div>            
            <div class="col-md-12 col-sm-12">
              <div class="position-relative p-5 bg-green" style="height: 180px">
                <div class="ribbon-wrapper ribbon-xl">
                  <div class="ribbon bg-primary text-xl">
                    Total
                  </div>
                </div>
                <p>En esta sección mostraremos el <br>
                monto total del pedido.</p>
                <div style="text-align: left; font-size: 32px; justify-content: center; align-items: center;">
                  <label>{{ total | currency('Q ') }}</label>
                </div>             
              </div>       
            </div>
          </div>
        </div>
        <hr>
        &nbsp;
        <div class="col-md-12 col-sm-12">
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm">
              <thead>
                  <tr>
                      <th>Número de Teléfono</th>
                      <th>Compania</th>
                      <th></th>
                  </tr>
              </thead>
              <tbody v-if="form.detail_order.length >= 1">
                <template v-for="(item, index) in form.detail_order">
                  <tr v-bind:key="index">
                      <td v-text="item.number"></td>
                      <td v-text="item.name"></td>
                      <td>
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
      </div>
    </div>
  </div>
  <div class="col-md-12 col-sm-12 text-right">
      <button type="button" class="btn btn-primary btn-sm" @click="createOrEdit"><i class="fa fa-save"></i> Guardar</button>
  </div>  
</form>
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
</div>
</template>

<script>
import FormError from '../shared/FormError'
export default {
  name: "newschool",
  components: {
      FormError
  },
  data() {
    return {
      loading: false,
      no_reservation: '',
      title: '',
      items: {},
      products: [],
      product_id: null,
      quantity: '',
      total: 0,
      information_product: {
        category: '',
        marca: '',
        price: '',
        sub_total: '',
      },
      form: {
        id: null,
        order: '',
        title: '',
        description: '',
        date: new Date(),
        schools_id: '',
        detail_order: []
      }
    };
  },
  created() {
    let self = this;
    let renombrar = '';
    switch (self.$route.params.type_order) {
      case 'A':
        renombrar = 'alimentacion'
        self.title = 'alimentación'
        break;
      case 'G':
        renombrar = 'gratuidad'
        self.title = 'gratuidad'
        break;
      case 'U':
        renombrar = 'utiles'
        self.title = 'utiles'
        break;                        
      default:
        self.$router.Push('/school/management/order') 
        break;
    }
    self.getProduct(renombrar)
  },

  methods: {
    //Generar correlativo de la orden
    generationOrder(){
      let self = this

      if(self.no_reservation == '' || self.no_reservation == null)
      {
        self.loading = true
        let data = ''        
        self.$store.state.services.reservationService
          .create(data)
          .then(r => {
            self.loading = false
            if(r.response){
              self.$toastr.error(r.response.data.error, 'error')
              return
            }
            self.no_reservation = r.data.data.correlative+'-'+r.data.data.year
            self.$toastr.success('número de pedido reservado', 'exito')
          })
          .catch(r => {});
      }

    },

    //funcion, validar si se guarda o actualiza
    createOrEdit(){
      let self = this
      self.$validator.validateAll().then((result) => {
          if (result) {
            self.pasarMayusculas()
            self.form.id === null ? self.create() : self.update()
          }
      });
    },

    //Limpiar formulario
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

    //pasar a mayusculas
    pasarMayusculas(){
        let self = this

        Object.keys(self.form).forEach(function(key,index) {
          
          if(typeof self.form[key] === "string") 
            self.form[key] = self.form[key].toUpperCase()

        });
    }, 

    //funcion para guardar registro
    create(){
      let self = this
      self.loading = true
      let data = self.form

      self.$store.state.services.schoolService
        .create(data)
        .then(r => {
          self.loading = false
          if(r.response){
            self.$toastr.error(r.response.data.error, 'error')
            return
          }
          self.$toastr.success('registro agregado con exito', 'exito')
          self.clearData()
        })
        .catch(r => {});
    },

    //Llamar todos los productos que pertenecen al tipo de orden seleccionada
    getProduct(type){
      let self = this
      self.loading = true

      self.$store.state.services.productService
        .get(type)
        .then(r => {
          self.products = []
          if(r.response){
            self.$toastr.error(r.response.data.error, 'error')
            return
          }
          r.data.data.forEach(function(item) {
            self.products.push({id: item.id, name: item.name, category: item.category.name, marca: item.presentation.name, price: item.prices[0].price})
          })
          self.loading = false
        })
        .catch(r => {});      
    },

    //Agregar número de teléfono de la persona
    addProductDetail(){
      let self = this
      self.$validator.validateAll("detail").then((result) => {
          if (result) {
            self.$toastr.error('lirbe', 'error')
          } else {
            self.$toastr.warning('lirbe', 'error')
          }
      });
    },

    //Quitar número de teléfono de la escuela
    quitarProductDetail(index) {
      this.form.detail_order.splice(index, 1);
    },    

    //Mostrar información del producto seleccionado
    information(data){
      let self = this
      self.information_product.price = data.price
      self.information_product.category = data.category
      self.information_product.marca = data.marca
    },
  },
  mounted(){
    $("body").resize()
  },
};
</script>
