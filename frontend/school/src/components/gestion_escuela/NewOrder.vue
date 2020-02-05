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
            <b-modal ref="cuadra_opc" hide-footer class="modal-backdrop" no-close-on-backdrop size="lg" title="Opciones para cuadrar">
              <div class="col-md-12 col-sm-12 col-12" v-if="Number(total) > 0">
                  <div class="table-responsive">
                      <table class="table table-striped hover bordered">
                          <thead>
                              <tr class="text-center" style="font-size: 16px;">
                                  <th style="vertical-align:middle;">Cantidad</th>
                                  <th style="vertical-align:middle;">Producto</th>                                                    
                                  <th style="vertical-align:middle;">Precio</th>                                                      
                                  <th style="vertical-align:middle;">Total</th>  
                              </tr>
                          </thead>
                          <tbody style="font-size: 11px;">
                              <template v-for="(row, index) in cuadrar_opciones">
                                  <tr v-bind:key="index">
                                      <td style="vertical-align:middle; text-align: center; font-weight: bold;">
                                          {{ row.cantidad }}
                                      </td> 
                                      <td style="vertical-align:middle; text-align: left; font-weight: bold;">
                                          {{ row.producto+' / '+row.marca }}
                                      </td>  
                                      <td style="vertical-align:middle; text-align: right; font-weight: bold;">
                                          {{ row.precio | currency('Q ',',',2,'.','front',true) }}
                                      </td>   
                                      <td style="vertical-align:middle; text-align: right; font-weight: bold;">
                                          {{ row.cantidad * row.precio | currency('Q ',',',2,'.','front',true) }}
                                      </td>                                  
                                  </tr>
                              </template>
                          </tbody>
                      </table>   
                  </div>                   
              </div>              
            </b-modal>
          </div>

          <div class="col-lg-12">
            
            <div class="card">
              <div class="card-header border-0">
                <h3 class="card-title">Aregar nuevo pedido de {{ llamar_informacion }}</h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12 col-sm-12">
                    <div class="card card-primary card-tabs">
                      <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                          <li class="nav-item">
                            <a :class="'nav-link '+nav_info" @click="inforTab" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Información del Menú</a>
                          </li>
                          <li class="nav-item" v-if="amount_available">
                            <a :class="'nav-link '+nav_ord" @click="generationOrder" id="custom-tabs-one-settings-tab" data-toggle="pill" href="#custom-tabs-one-settings" role="tab" aria-controls="custom-tabs-one-settings" aria-selected="false">Pedido</a>
                          </li>
                        </ul>
                      </div>
                      <div class="card-body">
<form> 
  <div class="tab-content" id="custom-tabs-one-tabContent">
    <div :class="'tab-pane fade '+tab_info" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
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
                :class="{'input':true,'has-errors': errors.has('menu.date')}"                
                placeholder="Seleccionar una fecha"
                format="dd/MM/yyyy"
                :align="'center'"
                @change="bloquear_fechas"
                :picker-options="pickerOptions"
                data-vv-scope="menu"
                value-format="yyyy-MM-dd">
              </el-date-picker>
            </div>
            <FormError :attribute_name="'menu.date'" :errors_form="errors"> </FormError>
          </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">            
          <div class="form-group">
            <label>Código</label>
            <multiselect v-model="form.code"
                @input="balance"
                v-validate="'required'" 
                data-vv-name="menu.code"
                data-vv-as="código"
                :options="codes" placeholder="seleccione un código"  
                :searchable="true"
                :allow-empty="false"
                :show-labels="false"
                data-vv-scope="menu"
                label="name" track-by="id">
                <span slot="noResult">No se encontro ningún registro</span>
                </multiselect>
                <FormError :attribute_name="'menu.code'" :errors_form="errors"> </FormError>
          </div>
        </div> 
        <div class="col-md-4 col-sm-12 text-right"> 
          <div class="form-group">
            <label>Monto disponible</label>
            <br>
            <h1>{{ disponibility | currency('Q ',',',2,'.','front',true) }}</h1>  
            <br>
            <small>{{ disbursement }}</small>          
          </div>
        </div>   
      </div>    
      <div class="row">
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
      </div>
    </div>

    <div :class="'tab-pane fade '+tab_ord" id="custom-tabs-one-settings" role="tabpanel" aria-labelledby="custom-tabs-one-settings-tab" v-if="amount_available">
      <div class="row" v-loading="loading_detail">
        <div class="col-md-8 col-sm-12">
          <div class="row">          
            <div class="col-md-12 col-sm-12">            
              <div class="form-group">
                <label>Producto</label>
                <multiselect v-model="product_id"
                    @input="information"
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
                :precision="0" :step="1" :min="1" :max="10000"
                data-vv-name="detail.quantity"
                data-vv-as="cantidad"
                v-validate="'required|between:1,10000'"
                data-vv-scope="detail"
                :class="{'input':true,'has-errors': errors.has('detail.quantity')}"></el-input-number> <br>
                <FormError :attribute_name="'detail.quantity'" :errors_form="errors"> </FormError>
              </div>
            </div>    
            <div class="col-md-4 col-sm-12 text-right">
              <div class="form-group">
                <label>Precio Unitario</label>
                <h1>{{ information_product.price | currency('Q ',',',2,'.','front',true) }}</h1>
              </div>
            </div>     
            <div class="col-md-4 col-sm-12 text-right">
              <div class="form-group">
                <label>Sub Total</label>
                <h1>{{ information_product.sub_total = quantity * information_product.price | currency('Q ',',',2,'.','front',true) }}</h1>
              </div>
            </div>   
            <div class="col-md-12 col-sm-12">
              <div class="form-group">
                <label>Observación</label>
                <textarea class="form-control" 
                cols="10" rows="1" 
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
            &nbsp;                            
            <div class="col-md-12 col-sm-12 text-right">
              <button type="button" class="btn btn-success btn-sm" v-b-tooltip.hover title="agregar" @click="addProductDetail">Agregar producto</button>
            </div>   
            &nbsp;   
          </div>
        </div>
        <div class="col-md-4 col-sm-12">
          <div class="row">
            <div class="col-md-12 col-sm-12 text-right">
              <p><label style="font-size:32px;">Pedido #</label> <label style="font-size:48px;">{{ no_reservation }}</label></p>
            </div>  
            <hr>  
            <div class="col-md-12 col-sm-12 text-right"> 
              <div class="form-group">
                <label>Monto disponible</label>
                <br>
                <h1>{{ disponibility | currency('Q ',',',2,'.','front',true) }}</h1>  
                <br>
                <small>{{ disbursement }}</small>            
              </div>
            </div>    
            &nbsp;  
            <div class="col-md-12 col-sm-12 text-right" v-if="Number(total) > 0">
              <button type="button" v-b-modal.modal-lg class="btn btn-info btn-md" v-b-tooltip.hover title="cuadrar" @click="cuadrar(title, disponibility)">Cuadrar</button>
              <br>
            </div>     
            &nbsp;        
            <div class="col-md-12 col-sm-12">
              <div class="position-relative p-5 bg-green">
                <div class="ribbon-wrapper ribbon-xl">
                  <div class="ribbon bg-primary text-xl">
                    Total
                  </div>
                </div>
                <p>En esta sección mostraremos el <br>
                monto total del pedido.</p>
                <div style="text-align: left; font-size: 32px; justify-content: center; align-items: center;">
                  <label>{{ total | currency('Q ',',',2,'.','front',true) }}</label>  
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
              <thead style="font-size: 16px; text-align:center;">
                  <tr>
                      <th>Cantidad</th>
                      <th>Producto</th>
                      <th>Precio</th>
                      <th>Sub Total</th>
                      <th></th>
                  </tr>
              </thead>
              <tbody v-if="form.detail_order.length >= 1">
                <template v-for="(item, index) in form.detail_order">
                  <tr v-bind:key="index" style="font-size: 14px;">
                      <td class="text-center" v-text="item.quantity"></td>
                      <td v-text="item.producto"></td>
                      <td class="text-right">{{ item.sale_price | currency('Q ',',',2,'.','front',true) }}</td>
                      <td class="text-right">{{ item.sub_total | currency('Q ',',',2,'.','front',true) }}</td>
                      <td class="text-center"> 
                          <button class="btn btn-danger btn-sm" @click="quitarProductDetail(index)">
                            Quitar
                          </button>
                      </td>                    
                  </tr>
                </template>
              </tbody> 
              <tfoot style="font-size: 18px;">
                <td class="text-right" colspan="3"><b>Total</b></td>
                <td class="text-right"><b>{{ total | currency('Q ',',',2,'.','front',true) }}</b></td>
              </tfoot>           
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-12 col-sm-12 text-right">
      <button type="button" v-if="!loading_detail && amount_available" class="btn btn-primary btn-sm" v-b-tooltip.hover title="guardar" @click="createOrEdit"><i class="fa fa-save"></i> Guardar</button>
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
import moment from 'moment'
import FormError from '../shared/FormError'
export default {
  name: "neworder",
  components: {
      FormError,
      moment
  },
  data() {
    return {
      loading: false,
      loading_detail: false,
      nav_info: 'active',
      tab_info: 'show active',
      nav_ord: '',
      tab_ord: '',
      no_reservation: '',
      calendario: [],
      codes: [],
      title: '',
      items: {},
      products: [],
      cuadrar_opciones: [],
      product_id: null,
      quantity: '',
      observation: '',
      total: 0,
      amount_available: false,
      disponibility: 0,
      disbursement: '',
      information_product: {
        category: '',
        marca: '',
        price: '',
        sub_total: '',
      },
      form: {
        id: null,
        code: null,
        order: '',
        title: '',
        description: '',
        date: new Date().setDate(new Date().getDate() - 2),
        schools_id: '',
        detail_order: []
      },

      pickerOptions: {
        disabledDate(time) {
          var d = new Date();
          return d.setDate(d.getDate() - 1) > time.getTime();
        },
      },

    };
  },
  created() {
    let self = this;
    self.form.schools_id = self.$store.state.school.schools_id
    self.getCodes()
    self.getCalendario()
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

    getCodes() {
      let self = this
      self.codes = []
      var school = self.$store.state.school
      school.school.code_high_school == null ? '' : self.codes.push({id: school.school.code_high_school, name: school.school.code_high_school})
      school.school.code_primary == null ? '' : self.codes.push({id: school.school.code_primary, name: school.school.code_primary})    
    },

    getCalendario(){
      let self = this
      self.loading = true

      self.$store.state.services.calendaryschoolService
        .get(self.$store.state.school.schools_id)
        .then(r => {
          self.calendario = r.data.data
          self.loading = false
        })
        .catch(r => {});   
    },

    bloquear_fechas(){
      let self = this
      if(moment(new Date(), 'YYYY-MM-DD').format('DD/MM/YYYY') == moment(self.form.date, 'YYYY-MM-DD').format('DD/MM/YYYY')) 
      {
          self.$swal({
            title: "ERROR",
            text: "LA FECHA QUE SELECCIONO TIENE QUE SER DISTINTA DE HOY",
            type: "error",
            showCancelButton: false
          }).then((result) => {
              self.form.date = ''
          }); 
          return         
      }
      else
      {
        self.calendario.forEach(function (item) {
          if(moment(item.date, 'YYYY-MM-DD').format('DD/MM/YYYY') == moment(self.form.date, 'YYYY-MM-DD').format('DD/MM/YYYY'))
          {
            self.$swal({
              title: "ERROR",
              text: "LA FECHA QUE SELECCIONO TIENE UNA ACTIVIDAD PROGRAMADA, "+item.title,
              type: "error",
              showCancelButton: false
            }).then((result) => {
                self.form.date = ''
            }); 
            return 
          }
        }); 
      }   
    },

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
            self.interceptar_error(r) == 0 ? '' : self.$toastr.success('número de reservación creado', 'exito') 
            self.no_reservation = r.data.data.correlative+'-'+r.data.data.year
            self.nav_ord = 'active';
            self.nav_ord = 'show active';
            self.nav_info = '';
            self.tab_info = '';            
          })
          .catch(r => {});
      }

    },

    inforTab(){
      let self = this
      self.nav_info = 'active';
      self.tab_info = 'show active';
      self.nav_ord = '';
      self.tab_ord = '';
    },

    //funcion, validar si se guarda o actualiza
    createOrEdit(){
      let self = this
      
      if(self.form.detail_order.length == 0){
        self.$toastr.error('No hay productos en el detalle del pedido.', 'error')
        return
      }

      self.$validator.validateAll('menu').then((result) => {
          if (result) {
            self.pasarMayusculas()
            self.create()
          } else {
            self.$toastr.error('Es necesario que ingrese información del Menú, por favor ir a la sección de "Información del Menú"', 'error')
          }
      });
    },

    //Limpiar formulario
    clearData(){
        let self = this
        var d = new Date();

        self.no_reservation = ''
        self.form.id = null
        self.form.order = null
        self.form.code = null
        self.form.title = null
        self.form.description = null
        self.form.date = d.setDate(d.getDate() - 2)
        self.form.detail_order = []
        self.total = 0

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

      self.$swal({
        title: "ADVERTENCIA",
        text: "¿ESTA SEGURO QUE DESEA GUARDAR EL PEDIDO # "+ self.no_reservation + "?",
        type: "info",
        showCancelButton: true
      }).then((result) => {
          if (result.value) {
            self.loading = true
            let data = self.form
            data.order = self.no_reservation
            data.code = self.form.code.id
            data.type_order = self.title            
            self.$store.state.services.orderService
              .create(data)
              .then(r => {
                self.loading = false
                if( self.interceptar_error(r) == 0) return
                self.$toastr.success('registro agregado con exito', 'exito') 
                self.$router.push('/school/management/order') 
              })
              .catch(r => {});                     
          }
      });
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
      let encontro = false
      self.loading_detail = true

      if(self.disponibility >= self.information_product.sub_total)
      {
        self.$validator.validateAll("detail").then((result) => {
            if (result) {
              self.form.detail_order.forEach(function(item) {
                if(item.products_id == self.product_id.id){
                  self.loading_detail = false
                  encontro = true
                  self.$swal({
                    title: "ADVERTENCIA",
                    text: "EL PRODUCTO "+ self.product_id.name + ", YA FUE AGREGADO. ¿DESEA INCREMENTAR LA CANTIDAD?",
                    type: "warning",
                    showCancelButton: true
                  }).then((result) => {
                      if (result.value) {

                        self.total = self.information_product.sub_total + self.total;
                        item.quantity = item.quantity + self.quantity
                        item.sub_total = self.information_product.sub_total + item.sub_total
                        self.disponibility = self.disponibility - self.information_product.sub_total

                        self.limpiarInputDetail()
                        return                      
                      }
                  });
                }
              })

              if(self.form.detail_order.length == 0 || encontro == false ){
                self.total = self.information_product.sub_total + self.total;
                self.form.detail_order.push({ quantity:self.quantity,
                                              sale_price:self.product_id.price,
                                              sub_total:self.information_product.sub_total,                                            
                                              observation:self.observation,
                                              products_id:self.product_id.id, 
                                              producto:self.product_id.name})
                self.$toastr.success('producto agregado al detalle del pedido.', 'Peiddo #'+self.no_reservation)  
                self.disponibility = self.disponibility - self.information_product.sub_total  
              
                self.limpiarInputDetail()
                self.loading_detail = false                
              }   
            } else {
              self.$toastr.warning('los datos ingresados no son correctos.', 'advertencia')
              self.loading_detail = false
            }
        });
      }
      else
      {
        self.loading_detail = false
        self.$swal({
          title: "ERROR",
          text: "EL MONTO DISPONIBLE DE Q "+ self.disponibility.toFixed(2) + " ES MENOR AL SUBTOTAL Q "+ self.information_product.sub_total.toFixed(2),
          type: "error",
          showCancelButton: false
        })        
      }
    },

    //Limpiar información detalle del pedido (inputs)
    limpiarInputDetail(){
      let self = this
      self.product_id = null,
      self.quantity = 1,
      self.observation = '',
      self.information_product.category = ''
      self.information_product.marca = ''
      self.information_product.price = ''
      self.information_product.sub_total = ''     
    },

    //Quitar número de teléfono de la escuela
    quitarProductDetail(index) {
      let self = this
      self.total = self.total - self.form.detail_order[index].sub_total
      self.disponibility = self.disponibility - self.form.detail_order[index].sub_total
      self.form.detail_order.splice(index, 1);
    },    

    //Mostrar información del producto seleccionado
    information(data){
      let self = this
      self.information_product.price = data.price
      self.information_product.category = data.category
      self.information_product.marca = data.marca
    },

    balance(){
      let self = this
      self.loading = true
      self.amount_available = false
      self.disponibility = 0
      self.$store.state.services.reservationService
        .getMoney(self.form.code.id, self.title)
        .then(r => {
          self.loading = false
          if( self.interceptar_error(r) == 0) return
          self.disponibility = r.data.data[0].balance - (r.data.data[0].subtraction_temporary - r.data.data[0].subtraction)
          self.disponibility = self.disponibility - self.total
          self.disbursement = r.data.data[0].disbursement.name
          self.amount_available = true
          self.form.detail_order = []
          self.total = 0          
        })
        .catch(r => {});
    },
    
    cuadrar(produt, price){
      let self = this
      self.loading = true

      self.$store.state.services.productService
        .getCuadrar(produt, price)
        .then(r => {
          self.loading = false
          self.cuadrar_opciones = []
          self.interceptar_error(r) == 0 ? '' : self.cuadrar_opciones = r.data
          
          if(self.cuadrar_opciones.length > 0)
            self.$refs['cuadra_opc'].show()

        })
        .catch(r => {});
    }
  },
  computed: {
    llamar_informacion(){
      let self = this
      let renombrar = '';
      let message = ''
      var d = new Date()
      self.nav_info = 'active';
      self.tab_info = 'show active';
      self.nav_ord = '';
      self.tab_ord = '';
      self.form.title = null
      self.form.description = null
      self.form.date = null
      self.form.code = null
      self.form.detail_order = []
      self.total = 0
      self.amount_available = false
      self.disponibility = 0
      self.disbursement = ''
      switch (self.$route.params.type_order) {
        case 'A':
          renombrar = 'alimentacion'
          message = 'alimentación'
          self.getProduct(renombrar)           
          break;
        case 'G':
          renombrar = 'gratuidad'
          message = 'gratuidad'
          self.getProduct(renombrar)           
          break;
        case 'U':
          renombrar = 'utiles'
          message = 'utiles'
          self.getProduct(renombrar)           
          break;  
        case 'V':
          renombrar = 'valija didactica'
          message = 'valijas didactica'
          self.getProduct('utiles')           
          break;                      
        default:
          self.$router.push('/school/management/order') 
          break;
      }
      self.title = renombrar
      return message    
    }
  },
  mounted(){
    $("body").resize()
  },
};
</script>
