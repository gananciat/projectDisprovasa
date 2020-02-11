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
            <div class="col-md-12 col-sm-12" v-if="viewFormRepeat">
                <div class="card border-info">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fa fa-list-ol"></i> Formulario para repetir pedido.                              
                        </h3>
                    </div>
                    <div class="card-body">
<form>
    <div class="row" style="padding: 2rem; margin: 2rem;">
        <div class="col-md-4 col-sm-4">
          <div class="form-group">
            <label>Fecha para la entrega del pedido</label>
            <div class="block">
              <el-date-picker
                v-model="form_repeat.date"
                type="date"
                data-vv-name="date"
                data-vv-as="fecha"
                v-validate="'required|date_format:yyyy-MM-dd'"
                :class="{'input':true,'has-errors': errors.has('repeat.date')}"                
                placeholder="Seleccionar una fecha"
                format="dd/MM/yyyy"
                :align="'center'"
                @change="gh"
                :picker-options="pickerOptionsRepeat"
                data-vv-scope="repeat"
                value-format="yyyy-MM-dd">
              </el-date-picker>
            </div>
            <FormError :attribute_name="'repeat.date'" :errors_form="errors"> </FormError>
          </div>
        </div>
        <div class="col-md-12 col-sm-12 text-right">
            <button type="button" class="btn btn-danger btn-sm" @click="cerrarRepeat"><i class="fa fa-undo"></i> Cancelar</button>
            <button type="button" class="btn btn-primary btn-sm" @click="creatRepeat"><i class="fa fa-save"></i> Guardar</button>
        </div>        
    </div>
</form>                           
                    </div>
                </div>
            </div>

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

            <div class="col-md-12 col-sm-12">
                <div class="card border-info">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fa fa-list-ol"></i> Pedido # {{ items.order }}                               
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 col-sm-12">
                                <dl class="dl-horizontal" v-if="Object.keys(items).length !== 0">
                                    <dt>Menú</dt>
                                    <dd>{{ items.title }}</dd>
                                    <dt>Descripción</dt>
                                    <dd>{{ items.description }}</dd>
                                    <dt>Tipo de pedido</dt>
                                    <dd>{{ items.type_order }}</dd>
                                    <dt>Fecha de entrega</dt>
                                    <dd>{{ items.date | moment('dddd DD MMMM YYYY') }}</dd>
                                    <dt>CUI que registro el pedido</dt>
                                    <dd>{{ items.person.cui }}</dd>
                                    <dt>Persona que registro el pedido</dt>
                                    <dd>{{ concat_name(items.person.name_one,items.person.name_two,items.person.last_name_one,items.person.last_name_two) }}</dd>
                                    <dt>Fecha de creación</dt>
                                    <dd>{{ items.created_at | moment('dddd DD MMMM YYYY h:mm:ss a') }}</dd>
                                </dl>                                
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div style="text-align: center; font-weight: bold; padding: 2rem; margin: 2rem;" v-if="Object.keys(items).length !== 0">
                                    <h1>Progreso del pedido</h1>
                                    <h2>{{ ((items.detail_complete * 100)/items.detail_total).toFixed(2) }} %</h2>
                                    <el-progress type="circle" show-text v-if="items.detail_complete == 0" :percentage="(items.detail_complete * 100)/items.detail_total" status="exception"></el-progress>
                                    <el-progress type="circle" show-text v-if="items.detail_complete != items.detail_total && items.detail_complete != 0" :percentage="(items.detail_complete * 100)/items.detail_total" status="warning"></el-progress>
                                    <el-progress type="circle" show-text v-if="items.detail_complete == items.detail_total" :percentage="(items.detail_complete * 100)/items.detail_total" status="success"></el-progress>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12 text-right">
                                <dl class="dl-horizontal" v-if="Object.keys(items).length !== 0">
                                    <dt>{{ titleCase(items.disbursement) }}</dt>
                                    <dt>Monto Aprobado</dt>
                                    <dd>{{ items.balance | currency('Q',',',2,'.','front',true) }}</dd>
                                    <dt>Monto Total Pedido</dt>
                                    <dd>{{ items.subtraction_temporary | currency('Q',',',2,'.','front',true) }}</dd>
                                    <dt>Monto Total de Reembolso</dt>
                                    <dd>{{ items.subtraction | currency('Q',',',2,'.','front',true) }}</dd>
                                    <dt>Disponible Total</dt>
                                    <dd>{{ items.balance - (items.subtraction_temporary - items.subtraction) | currency('Q',',',2,'.','front',true) }}</dd>
                                    <hr>
                                    <dt>Monto del Pedido # {{ items.order }}</dt>
                                    <dd>{{ items.total | currency('Q',',',2,'.','front',true) }}</dd>
                                    <dt>Monto del Reembolso # {{ items.order }}</dt>
                                    <dd>{{ items.refund | currency('Q',',',2,'.','front',true) }}</dd>
                                    <dt>Disponible hasta este pedido</dt>
                                    <dd>{{ items.balance - (items.subtraction_temporary - items.subtraction - (items.total - items.refund)) | currency('Q',',',2,'.','front',true) }}</dd>
                                    <dt>
                                        <br><br>
                                        <button type="button" class="btn btn-info btn-md pull-right" v-if="items.complete" v-b-tooltip.hover v-b-tooltip.rightbottom title="repertir pedido" @click="repeatOrder(items)">
                                            <i class="fa fa-history">
                                            </i>
                                        </button>                                         
                                    </dt>
                                </dl>                                
                            </div>                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-sm-12" v-if="!items.complete">
                <div class="card text-center border-primary">
                    <div class="card-header bg-primary text-white">
                        <div>Formulario para agregar pedido al detalle</div>
                    </div>
                    <div class="card-body">
<form>
    <div class="row"> 
        <div class="col-md-8 col-sm-12">
            <div class="row">          
                <div class="col-md-12 col-sm-12">            
                    <div class="form-group">
                        <label>Producto</label>
                        <multiselect v-model="form.products_id"
                            @input="information"
                            v-validate="'required'" 
                            data-vv-name="insert.products_id"
                            data-vv-as="producto"
                            :options="products" placeholder="seleccione producto"  
                            :searchable="true"
                            :allow-empty="false"
                            :show-labels="false"
                            data-vv-scope="insert"
                            label="name" track-by="id">
                            <span slot="noResult">No se encontro ningún registro</span>
                            </multiselect>
                            <FormError :attribute_name="'insert.products_id'" :errors_form="errors"> </FormError>
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
                        <el-input-number v-model="form.quantity" 
                        :precision="0" :step="1" :min="1" :max="10000"
                        data-vv-name="insert.quantity"
                        data-vv-as="cantidad"
                        v-validate="'required|between:1,10000'"
                        data-vv-scope="insert"
                        :class="{'input':true,'has-errors': errors.has('insert.quantity')}"></el-input-number> <br>
                        <FormError :attribute_name="'insert.quantity'" :errors_form="errors"> </FormError>
                    </div>
                </div>    
                <div class="col-md-4 col-sm-12 text-right">
                    <div class="form-group">
                        <label>Precio Unitario</label>
                        <h1>{{ form.sale_price | currency('Q',',',2,'.','front',true) }}</h1>
                    </div>
                </div>     
                <div class="col-md-4 col-sm-12 text-right">
                    <div class="form-group">
                        <label>Sub Total</label>
                        <h1>{{ information_product.sub_total = form.quantity * form.sale_price | currency('Q',',',2,'.','front',true) }}</h1>
                    </div>
                </div>   
                <div class="col-md-12 col-sm-12">
                    <div class="form-group">
                        <label>Observación</label>
                        <textarea class="form-control" 
                        cols="10" rows="1" 
                        placeholder="observación del producto"
                        name="observation"
                        v-model="form.observation"
                        data-vv-as="observación del producto"
                        v-validate="'min:5|max:1000'"
                        data-vv-scope="insert"
                        :class="{'input':true,'has-errors': errors.has('insert.observation')}">
                        </textarea>
                        <FormError :attribute_name="'insert.observation'" :errors_form="errors"> </FormError>
                    </div>
                </div>                               
                <div class="col-md-12 col-sm-12 text-right">
                    <button type="button" class="btn btn-danger btn-md" v-b-tooltip.hover title="cancelar" @click="clearData"><i class="fa fa-undo"></i> Cancelar</button>
                    <button type="button" class="btn btn-success btn-md" v-b-tooltip.hover title="agregar" @click="create">Agregar producto</button>
                </div>      
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="row">
                <div class="col-md-12 col-sm-12 text-right">
                    <p><label style="font-size:32px;">Pedido #</label> <label style="font-size:48px;">{{ items.order }}</label></p>
                </div>  
                <hr>   
                <div class="col-md-12 col-sm-12 text-right"> 
                    <div class="form-group">
                        <label>Monto disponible</label>
                        <br>
                        <h1>{{ disponibility | currency('Q ',',',2,'.','front',true) }}</h1>            
                    </div>
                </div>   
                &nbsp;  
                <div class="col-md-12 col-sm-12 text-right" v-if="Number(total) > 0">
                    <button type="button" v-b-modal.modal-lg class="btn btn-info btn-md" v-b-tooltip.hover title="cuadrar" @click="cuadrar(items.type_order, disponibility)">Cuadrar</button>
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
                            <label>{{ total_insert = total + information_product.sub_total | currency('Q',',',2,'.','front',true) }}</label>
                        </div>             
                    </div>       
                </div>
            </div>
        </div>
    </div>
</form>
                    </div>
                </div>
            </div>
            <hr>
            &nbsp;

            <div class="col-lg-12">           
                <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">Detalle del pedido #</h3>
                </div>
                    <div class="card-body">
                        <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-striped hover bordered">
                                    <thead>
                                        <tr class="text-center">
                                            <th>#</th>
                                            <th>Cantidad</th>
                                            <th>Producto</th>
                                            <th>Observación</th>
                                            <th>Progreso</th>
                                            <th>Estado</th>
                                            <th>Precio Unitario</th>
                                            <th>Sub Total</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-for="(item,index) in items.details">
                                            <tr v-bind:key="index">
                                                <td style="vertical-align:middle; font-size: 14px; text-align: center; font-weight: bold;">{{ index+1 }}</td>
                                                <td style="vertical-align:middle; font-size: 18px; text-align: center; font-weight: bold;">
                                                    <p v-if="item.deliver">
                                                        {{ Number(item.quantity).toFixed(0) }}
                                                    </p>                                                    
                                                    <div class="form-group" v-if="!item.deliver">
                                                        <el-input-number v-model="item.quantity" size="mini" 
                                                        :precision="0" :step="1" :min="item.progress.purchased_amount == 0 ? Number(item.progress.purchased_amount+1) : Number(item.progress.purchased_amount)" :max="10000"
                                                        data-vv-name="edit.quantity"
                                                        data-vv-as="cantidad"
                                                        v-validate="'required|between:1,10000'"
                                                        data-vv-scope="edit"
                                                        :class="{'input':true,'has-errors': errors.has('edit.quantity')}"></el-input-number> <br>
                                                        <FormError :attribute_name="'edit.quantity'" :errors_form="errors"> </FormError>
                                                    </div>                                                                
                                                </td>
                                                <td style="vertical-align:middle;">{{ item.product.name+' / '+ item.product.presentation.name }}</td>
                                                <td style="vertical-align:middle;">
                                                    <p v-if="item.deliver">
                                                        {{ item.observation }}
                                                    </p>
                                                    <div class="form-group" v-if="!item.deliver">
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
                                                <td style="vertical-align:middle; text-align: center;">
                                                    <small>{{ `${Number((item.progress.purchased_amount/item.quantity) * 100).toFixed(2)}%` }}</small>
                                                    <div class="progress progress-sm" v-b-tooltip.hover :title="'C '+Number(item.progress.purchased_amount).toFixed(0)+'/'+' P '+Number(item.quantity).toFixed(0)">
                                                    <div class="progress-bar bg-green" role="progressbar" :aria-volumenow="item.progress.purchased_amount" aria-volumemin="0" :aria-volumemax="item.quantity" :style="'width:'+((item.progress.purchased_amount/item.quantity) * 100)+'%'"></div>
                                                    </div>
                                                </td>
                                                <td style="vertical-align:middle; text-align: center;">
                                                    <span v-if="item.progress.purchased_amount == 0" class="badge badge-danger">PEDIDO</span>
                                                    <span v-if="item.progress.purchased_amount != 0 && item.progress.purchased_amount != item.quantity" class="badge badge-warning">EN PROCESO</span>
                                                    <span v-if="item.progress.purchased_amount == item.quantity" class="badge badge-success">COMPLETADO</span>
                                                </td>
                                                <td style="vertical-align:middle; font-size: 12px; text-align: right; font-weight: bold;">{{ item.sale_price | currency('Q',',',2,'.','front',true) }}</td>
                                                <td style="vertical-align:middle; font-size: 12px; text-align: right; font-weight: bold;">
                                                    <p v-b-tooltip.hover :title="'R '+Number(item.refund).toFixed(2)+'/'+' S '+Number(item.subtotal).toFixed(2)">
                                                        {{ item.subtotal = item.quantity * item.sale_price | currency('Q',',',2,'.','front',true) }}
                                                    </p>
                                                </td>
                                                <td style="vertical-align:middle; text-align: center;">
                                                    <button type="button" v-if="item.deliver" class="btn btn-success btn-sm" v-b-tooltip.hover title="listo"><i class="fa fa-check"></i></button>
                                                    <button type="button" v-if="!item.deliver" class="btn btn-info btn-sm" v-b-tooltip.hover title="editar" @click="update(item)"><i class="fa fa-pencil"></i></button>
                                                    <button type="button" v-if="item.progress.purchased_amount == 0" class="btn btn-danger btn-sm" v-b-tooltip.hover title="eliminar" @click="destroy(item)"><i class="fa fa-trash"></i></button>
                                                </td>
                                            </tr>                       
                                        </template>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td style="vertical-align:middle; font-size: 20px; text-align: right; font-weight: bold;" colspan="7">Total</td>
                                            <td style="vertical-align:middle; font-size: 28px; text-align: right; font-weight: bold;" colspan="2">{{ total | currency('Q',',',2,'.','front',true) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>   
                            </div>                
                        </div>                
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12" v-if="items.complete">
                <div class="callout callout-warning" style="width: 100%;">
                    <h1>Detalle de la gestión del pedido # {{ items.order }}</h1>
                    <p style="font-size: 28px; text-align: right; font-weight: bold;">
                        Monto total del pedido {{ total | currency('Q',',',2,'.','front',true) }}
                        <br>
                        Monto total del reembolso {{ items.refund | currency('Q',',',2,'.','front',true) }}
                    </p>
                    <hr>
                    <p style="font-size: 28px; text-align: right; font-weight: bold;">
                        Monto total facturado {{items.total - items.refund | currency('Q',',',2,'.','front',true) }}
                    </p>
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
  name: "detailorder",
  components: {
      FormError,
      moment
  },
  data() {
    return {
      loading: false,
      loading_detail: false,
      viewFormRepeat: false,
      items: {},
      calendario: [],
      products: [],
      cuadrar_opciones: [],
      total_insert: 0,
      disponibility: 0,
      temporary: 0,
      guard_total: 0,
      information_product: {
        category: '',
        marca: '',
        sub_total: 0,
      },
      form: {
        id: null,
        products_id: null,
        quantity: 1,
        observation: '',
        sale_price: '',
        orders_id: null
      },
      form_repeat: {
          date: '',
          orders_id: ''
      },
      pickerOptionsRepeat: {
        disabledDate(time) {
          var d = new Date();
          return d.setDate(d.getDate() - 1) > time.getTime();
        },
      },      
    };
  },
  created() {
    let self = this;
    self.form.orders_id = self.$route.params.id
    self.getAll(self.form.orders_id)
  },

  methods: {
    //Clasificar error
    interceptar_error(r){
      let self = this

        if(r.response){
            if(r.response.status === 422){
                this.$toastr.info(r.response.data.error, 'Mensaje')
                return 0
            }

            if(r.response.status != 201 && r.response.status != 422){
                for (let value of Object.values(r.response.data)) {
                    self.$toastr.error(value, 'Mensaje')
                }
                return 0
            }
        }
    },

    getAll(id){
      let self = this;
      self.loading = true;

      self.$store.state.services.detailorderService
        .get(id)
        .then(r => {
            self.items = r.data.data[0];
            self.disponibility = self.items.balance - (self.items.subtraction_temporary - self.items.subtraction)
            self.disponibility = Number(self.disponibility).toFixed(2)
            self.temporary = self.items.subtraction_temporary - self.items.subtraction
            self.guard_total = self.items.total
            self.getProduct(r.data.data[0].type_order)
            self.loading = false;           
        })
        .catch(r => {});        
    },

    //Funcion para concatenar el nombre
    concat_name(nombre_uno, nombre_dos, apellido_uno, apellido_dos){
        let concat = ''
        concat = nombre_uno + ' '
        
        if(nombre_dos != null)
            concat = concat + nombre_dos + ' '

        concat = concat + apellido_uno + ' '
        
        if(apellido_dos != null)
            concat = concat + apellido_dos + ' '

        return concat
    },

    //funcion para guardar registro
    create(){
      let self = this
      let sumar = parseFloat(self.guard_total) + parseFloat(self.disponibility)
      let resta = parseFloat(sumar) - parseFloat(self.total_insert)

        if(resta > -1)
        {
            self.$validator.validateAll('insert').then((result) => {
                if (result) {
                    self.pasarMayusculas()
                    let data = self.form

                    self.$swal({
                        title: "VERIFICAR",
                        text: "¿ESTA SEGURO QUE DESEA AGREGAR EL PRODUCTO AL PEDIDO # "+ self.items.order + "?",
                        type: "info",
                        showCancelButton: true
                    }).then((result) => {
                        if (result.value) {
                            self.loading = true
                            data.products_id = self.form.products_id.id
                            self.$store.state.services.detailorderService
                            .create(data)
                            .then(r => {
                                self.loading = false
                                if( self.interceptar_error(r) == 0) return
                                self.$toastr.success('registro agregado con exito', 'exito') 
                                self.getAll(self.form.orders_id)
                                self.clearData()
                            }).catch(r => {});                     
                        }
                    });                
                }
            });
        }
        else
        {
            self.loading = false
            self.$swal({
                title: "ERROR",
                text: "EL MONTO DISPONIBLE DE Q "+ self.disponibility.toFixed(2) + " ES MENOR AL SUBTOTAL Q "+ (self.form.quantity * self.form.sale_price).toFixed(2),
                type: "error",
                showCancelButton: false
            })  
        }
    },    

    //funcion para actualizar registro
    update(item){
      let self = this
      let sumar = parseFloat(self.guard_total) + parseFloat(self.disponibility)
      let resta = parseFloat(sumar) - parseFloat(self.total_insert)
 
        if(resta > -1)
        {
            self.$validator.validateAll('edit').then((result) => {
                if (result) {
                    let data = {}
                    data.id = item.id
                    data.quantity = item.quantity
                    data.observation = item.observation

                    self.$swal({
                        title: "VERIFICAR",
                        text: "¿ESTA SEGURO QUE DESEA MODIFICAR EL PRODUCTO DEL PEDIDO # "+ self.items.order + "?",
                        type: "info",
                        showCancelButton: true
                    }).then((result) => {
                        if (result.value) {
                            self.loading = true
                            self.$store.state.services.detailorderService
                                .update(data)
                                .then(r => {
                                    self.loading = false
                                    if( self.interceptar_error(r) == 0) return
                                    self.$toastr.success('registro actualizado con exito', 'exito') 
                                    self.getAll(self.form.orders_id)
                                    self.clearData()
                                })
                                .catch(r => {});                    
                        }
                    });                
                }
            });
        }
        else
        {
            self.loading = false
            self.$swal({
                title: "ERROR",
                text: "EL MONTO DISPONIBLE DE Q "+ self.disponibility.toFixed(2) + " ES MENOR AL SUBTOTAL Q "+ (item.quantity * item.sale_price).toFixed(2),
                type: "error",
                showCancelButton: false
            })  
        }        
    },

    //funcion para eliminar registro
    destroy(data){
      let self = this

      self.$swal({
        title: "¿ELIMINAR REGISTRO?",
        text: "¿ESTA SEGURO QUE DESEA ELIMINAR EL PRODUCTO DEL PEDIDO # "+ self.items.order + "?",
        type: "warning",
        showCancelButton: true
      }).then((result) => {
          if (result.value) {
              self.$store.state.services.detailorderService
                .destroy(data)
                .then(r => {
                    self.loading = true
                    self.loading = false
                    if( self.interceptar_error(r) == 0) return
                    self.$toastr.success('registro eliminado con exito', 'exito') 
                    self.getAll(self.form.orders_id)
                    self.clearData()
                })
                .catch(r => {});
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

    //Limpiar formulario
    clearData(){
        let self = this
        self.loading = true
        self.limpiarInputDetail()
        self.form.id = null
        self.form.products_id = null
        self.form.quantity = 1
        self.form.observation = ''
        self.form.sale_price = ''

        self.$validator.reset()
        self.$validator.reset()
        self.loading = false
    },   

    //Limpiar información detalle del pedido (inputs)
    limpiarInputDetail(){
      let self = this
      self.information_product.category = ''
      self.information_product.marca = ''
      self.information_product.sub_total = 0     
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

    //Mostrar información del producto seleccionado
    information(data){
      let self = this
      let encontro = false
      self.loading = true

        self.items.details.forEach(function (item) {
            if(item.products_id == data.id)
            {
                encontro = true
                self.loading = false
                self.$swal({
                title: "ADVERTENCIA",
                text: "EL PRODUCTO "+ data.name + ", YA FUE AGREGADO.",
                type: "warning",
                showCancelButton: false
                })    
                self.form.products_id = null           
                return
            }
        });

        if(encontro == false)
        {   
            self.form.sale_price = data.price
            self.information_product.category = data.category
            self.information_product.marca = data.marca
            self.loading = false
        }
    },

    titleCase(str) {
        var splitStr = str.toLowerCase().split(' ');
            for (var i = 0; i < splitStr.length; i++) {
                // You do not need to check if i is larger than splitStr length, as your for does that for you
                // Assign it back to the array
                splitStr[i] = splitStr[i].charAt(0).toUpperCase() + splitStr[i].substring(1);     
            }
            // Directly return the joined string
        return splitStr.join(' '); 
    },
    
    //repetir pedido, comprar si tiene dinero suficiente para realizar el pedido de nuevo
    repeatOrder(data){
      let self = this;
      self.loading = true;
      self.getCalendario()

      self.$store.state.services.repeatorderService
        .get(data.id)
        .then(r => {
          self.loading = false; 
          if( self.interceptar_error(r) == 0) return
          self.$swal({
            title: "VERIFICAR",
            text: "¿ESTA SEGURO QUE QUIERE REPERTIR LA ORDEN #"+data.order+" ?",
            type: "info",
            showCancelButton: true
          }).then((result) => {
              if (result.value) {
                self.viewFormRepeat = true
                self.form_repeat.date = ''
                self.form_repeat.orders_id = data.id
              }
          });           
        })
        .catch(r => {});
    },
    
    cerrarRepeat(){
        let self = this
        self.viewFormRepeat = false
        self.form_repeat.date = ''
        self.form_repeat.orders_id = null
    },

    creatRepeat(){
      let self = this;

        self.$validator.validateAll('repeat').then((result) => {
            if (result) {
                self.$swal({
                    title: "VERIFICAR",
                    text: "¿ESTA SEGURO QUE QUIERE GUARDAR LA INFORMACION?",
                    type: "success",
                    showCancelButton: true
                }).then((result) => {
                    self.loading = true;                 
                    if (result.value) {
                        let data = self.form_repeat
                        self.$store.state.services.repeatorderService
                            .create(data)
                            .then(r => {
                                self.loading = false;
                                if( self.interceptar_error(r) == 0) return 
                                self.$toastr.success('el pedido #'+self.items.order+' fue repetido, el nuevo pedido es el #'+r.data.data.order, 'exito')
                                self.cerrarRepeat()          
                            })
                            .catch(r => {});
                    }
                }); 
            }
        })
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

    gh(){
      let self = this
      if(moment(new Date(), 'YYYY-MM-DD').format('DD/MM/YYYY') == moment(self.form_repeat.date, 'YYYY-MM-DD').format('DD/MM/YYYY')) 
      {
          self.$swal({
            title: "ERROR",
            text: "LA FECHA QUE SELECCIONO TIENE QUE SER DISTINTA DE HOY",
            type: "error",
            showCancelButton: false
          }).then((result) => {
              self.form_repeat.date = ''
          }); 
          return         
      }
      else
      {
        self.calendario.forEach(function (item) {
            if(moment(item.date, 'YYYY-MM-DD').format('DD/MM/YYYY') == moment(self.form_repeat.date, 'YYYY-MM-DD').format('DD/MM/YYYY'))
            {
            self.$swal({
                title: "ERROR",
                text: "LA FECHA QUE SELECCIONO TIENE UNA ACTIVIDAD PROGRAMADA, "+item.title,
                type: "error",
                showCancelButton: false
            }).then((result) => {
                self.form_repeat.date = ''
            }); 
            return 
            }
        });
      }    
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
    total(){
        let self = this
        let total = 0
        if(Object.keys(self.items).length !== 0){
            self.items.details.forEach(function (item) {
                total = total + item.subtotal
            });
        }
        self.total_insert = total
        return total
    },
  },
  mounted(){
    $("body").resize()
  },
};
</script>
