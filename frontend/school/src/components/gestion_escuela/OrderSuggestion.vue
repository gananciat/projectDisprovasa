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
            <h3> {{ form.title }} </h3>
          </div>
        </div>  
        <div class="col-md-12 col-sm-12">
          <div class="form-group">
            <label>Descripción</label>
            <p> {{ form.description }} </p>
          </div>
        </div>                
      </div>
    </div>

    <div :class="'tab-pane fade '+tab_ord" id="custom-tabs-one-settings" role="tabpanel" aria-labelledby="custom-tabs-one-settings-tab" v-if="amount_available">
      <div class="row" v-loading="loading_detail">
        <div class="col-md-12 col-sm-12">
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
                  <label>{{ totalMount | currency('Q ',',',2,'.','front',true) }}</label>  
                </div>             
              </div>       
            </div>
          </div>
        </div>
        <hr>
        &nbsp;
        &nbsp;
        <div class="col-md-12 col-sm-12">
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm">
              <thead style="font-size: 16px; text-align:center;">
                  <tr>
                      <th>Cantidad</th>
                      <th>Producto</th>
                      <th>Descripción</th>
                      <th>Precio</th>
                      <th>Sub Total</th>
                  </tr>
              </thead>
              <tbody v-if="form.detail_order.length >= 1">
                <template v-for="(item, index) in form.detail_order">
                  <tr v-bind:key="index" style="font-size: 14px;">
                      <td class="text-center" style="vertical-align:middle; ">
                          <div class="form-group">
                              <el-input-number v-model="item.quantity" size="mini" 
                              :precision="0" :step="1" :min="0" :max="10000"
                              data-vv-name="'edit.quantity'"
                              data-vv-as="cantidad"
                              v-validate="'required|between:1,10000'"
                              data-vv-scope="edit"
                              :class="{'input':true,'has-errors': errors.has('edit.quantity')}"></el-input-number> <br>
                              <FormError :attribute_name="'edit.quantity'" :errors_form="errors"> </FormError>
                          </div>                        
                      </td>
                      <td style="vertical-align:middle; " v-text="item.producto"></td>
                      <td style="vertical-align:middle; " class="text-center">
                        <div class="form-group">
                            <textarea class="form-control" 
                            rows="3" 
                            placeholder="observación del producto"
                            name="observation"
                            v-model="item.observation"
                            data-vv-as="observación del producto"
                            v-validate="'min:5|max:125'"
                            data-vv-scope="edit"
                            :class="{'input':true,'has-errors': errors.has('edit.observation')}">
                            </textarea>
                            <FormError :attribute_name="'edit.observation'" :errors_form="errors"> </FormError>
                        </div>                        
                      </td>
                      <td style="vertical-align:middle; " class="text-right">{{ item.sale_price | currency('Q ',',',2,'.','front',true) }}</td>
                      <td style="vertical-align:middle; " class="text-right">{{ item.sub_total = item.quantity*item.sale_price | currency('Q ',',',2,'.','front',true) }}</td>                  
                  </tr>
                </template>
              </tbody> 
              <tfoot style="font-size: 18px;">
                <td class="text-right" colspan="4"><b>Total</b></td>
                <td class="text-right"><b>{{ total | currency('Q ',',',2,'.','front',true) }}</b></td>
              </tfoot>           
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-12 col-sm-12 text-right">
      <button type="button" v-if="!loading_detail && amount_available" class="btn btn-primary btn-sm" v-b-tooltip.left v-b-tooltip.hover title="guardar" @click="createOrEdit"><i class="fa fa-save"></i> Guardar</button>
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
      product_id: null,
      quantity: '',
      observation: '',
      total: 0,
      amount_available: false,
      disponibility: 0,
      disbursement: '',
      form: {
        id: null,
        code: null,
        order: '',
        title: '',
        description: '',
        date: '',
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
    let self = this
    let renombrar = '';
    let message = ''
    message = 'alimentación'
    self.title = message
    self.form.schools_id = self.$store.state.school.schools_id
    self.getMenu()
  },

  methods: {
    //Clasificar error
    interceptar_error(r){
      let self = this
      let error = 1;
      self.no_reservation == ''

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

    getMenu(){
      let self = this;
      self.loading = true;
        
      self.$store.state.services.menusuggestionService
        .get(self.$route.params.id)
        .then(r => {
            self.loading = false; 
            self.items = r.data.data[0]
            self.form.title = self.items.title
            self.form.description = self.items.description
            self.getCodes()
            self.getCalendario()            
        })
        .catch(r => {});
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
        self.form.detail_order = []
        self.loading = true
        let data = ''        
        self.$store.state.services.reservationService
          .create(data)
          .then(r => {
            self.loading = false
            self.interceptar_error(r) == 0 ? '' : self.$toastr.success('número de reservación creado', 'exito') 
            self.no_reservation = r.data.data.correlative+'-'+r.data.data.year
            self.items.details.forEach(function (item){
              self.form.detail_order.push({ quantity: 0,
                                            sale_price: item.product.prices[0].price,
                                            sub_total: 0,                                            
                                            observation: '',
                                            products_id: item.product.id, 
                                            producto: item.product.name+' - '+item.product.presentation.name})
            })
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
      
      self.$validator.validateAll('edit').then((result) => {
          if (result) {
            self.$validator.validateAll('menu').then((result2) => {
                if (result2) {
                  self.pasarMayusculas()
                  self.create()
                } else {
                  self.$toastr.error('Es necesario que ingrese información del Menú, por favor ir a la sección de "Información del Menú"', 'error')
                }
            });
          } else {
            self.$toastr.error('Verificar las cantidades del detalle del pedido."', 'error')
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

    //funcion para guardar registro
    create(){
      let self = this
      self.loading_detail = true
      if(self.disponibility >= self.total)
      {
        self.loading_detail = false
        self.$swal({
          title: "ADVERTENCIA",
          text: "¿ESTA SEGURO QUE DESEA GUARDAR EL PEDIDO # "+ self.no_reservation + "?",
          type: "info",
          showCancelButton: true
        }).then((result) => {
            if (result.value) {
              self.loading_detail = true
              let data = self.form
              data.order = self.no_reservation
              data.code = self.form.code.id
              data.type_order = 'alimentacion'
              self.$store.state.services.orderService
                .create(data)
                .then(r => {
                  self.loading_detail = false
                  if( self.interceptar_error(r) == 0) return
                  self.$toastr.success('registro agregado con exito', 'exito') 
                  self.$router.push('/school/management/order') 
                })
                .catch(r => {});                     
            }
        });
      }
      else
      {
        self.loading_detail = false
        self.$swal({
          title: "ERROR",
          text: "EL MONTO DISPONIBLE DE Q "+ self.disponibility.toFixed(2) + " ES MENOR AL TOTAL Q "+ self.total.toFixed(2),
          type: "error",
          showCancelButton: false
        })        
      }
    },

    balance(){
      let self = this
      self.loading = true
      self.amount_available = false
      self.disponibility = 0
      self.$store.state.services.reservationService
        .getMoney(self.form.code.id, 'alimentacion')
        .then(r => {
          self.loading = false
          if( self.interceptar_error(r) == 0) return
          self.disponibility = r.data.data[0].balance - r.data.data[0].subtraction_temporary
          self.disponibility = self.disponibility - self.total
          self.disbursement = r.data.data[0].disbursement.name
          self.amount_available = true
          self.form.detail_order = []
          self.total = 0          
        })
        .catch(r => {});
    }
  },
  computed: {
    totalMount(){
      let self = this
      self.total = 0

      self.form.detail_order.forEach(function (item){
        self.total = self.total + item.sub_total
      })

      return self.total
    }
  },
  mounted(){
    $("body").resize()
  },
};
</script>
