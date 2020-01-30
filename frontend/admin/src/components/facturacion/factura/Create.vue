<template>
<!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
        <!-- Content Wrapper. Contains page content -->
    <div v-loading="loading">
        
  <div class="content-wrapper"  v-if="order !== null">
    <!-- Content Header (Page header) -->
    <!-- Modal para nuevo registro -->
        <b-modal ref="nuevo" :title="'factura no '+serie.serie+'-'+ formatCode(serie.actual_bill+1, String(serie.total).length)" size="xl" hide-footer class="modal-backdrop" no-close-on-backdrop>
          <form>
              <div class="row">
                <div class="form-group col-md-4">
                    <label>Fecha factura</label>
                    <input type="date" class="form-control" placeholder="fcha factura"
                    name="date"
                    v-model="form.date"
                    data-vv-as="fecha factura"
                    v-validate="'required'"
                :class="{'input':true,'has-errors': errors.has('date')}">
                <FormError :attribute_name="'date'" :errors_form="errors"> </FormError>
                </div>
              </div>
              <div class="">
                  <div class="">
              <!-- title row -->
              <div class="row">
                <div class="col-12">
                  <h4>
                    <i class="fa fa-globe"></i> DISPROVASA.
                    <small class="float-right">Fecha: {{form.date | moment('DD/MM/YYYY')}}<br />
                        <label>Factura:</label> <label class="text-danger">{{serie.serie+'-'+ formatCode(serie.actual_bill+1, String(serie.total).length)}}</label> 
                    </small>
                  </h4>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->
              <div class="row invoice-info">
                <div class="col-sm-12 invoice-col">
                  <address>
                    <strong>Distribuidora de productos varios.</strong><br>
                    795 Folsom Ave, Suite 600<br>
                    San Francisco, CA 94107<br>
                    Phone: (804) 123-5432<br>
                    Email: info@almasaeedstudio.com
                  </address>
                </div>
              </div>
              <div class="row">
                <div class="col-12 table-responsive">
                  <table class="table table-bordered">
                    <thead class="bg-primary disabled color-palette">
                    <tr>
                      <th>Cantidad</th>
                      <th>Descripcion</th>
                      <th>Precio unitario</th>
                      <th>Subtotal</th>
                    </tr>
                    </thead>
                    <tbody class="bg-light color-palette">
                    <tr v-for="(p, i) in form.details" :key="i">
                      <td>{{p.progress.purchased_amount}}</td>
                      <td>{{p.product.name}}
                          <div v-if="p.product.camouflage">
                              <label>Facturar como</label>
                              <input class="form-control input-sm" v-model="p.invoiced_as" 
                                :name="p.product.name"
                                :data-vv-as="'facturar como '+p.product.name | lowercase "
                                v-validate="'required|min:5|max:250'"
                                :class="{'input':true,'has-errors': errors.has(p.product.name)}">
                                <FormError :attribute_name="p.product.name" :errors_form="errors"> </FormError>
                          </div>
                      </td>
                      <td>{{p.sale_price | currency('Q ')}}</td>
                      <td>{{p.progress.purchased_amount * p.sale_price | currency('Q ')}}</td>
                    </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="row">
                  <div class="col-md-6"></div>
                <div class="col-md-6 col-lg-6 pull-right">
                  <p class="lead">Factura de {{form.date | moment('DD/MM/YYYY')}}</p>

                  <div class="table-responsive">
                    <table class="table">
                      <tbody><tr>
                        <th style="width:50%">Subtotal:</th>
                        <td>{{invoiceTotal | currency('Q ')}}</td>
                      </tr>
                      <tr>
                        <th>Iva ({{vat.value}}%)</th>
                        <td>{{form.total_iva | currency('Q ')}}</td>
                      </tr>
                      <tr>
                        <th>Total (iva incluido):</th>
                        <td>{{form.total - form.total_iva | currency('Q ')}}</td>
                      </tr>
                    </tbody></table>
                  </div>
                </div>
              </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12 text-right">
                  <button @click="close" type="button" class="btn btn-danger btn-sm"><i class="fa fa-undo"></i> Cancelar</button>
                  <button @click="invoiceGenerate" type="button" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Generar factura</button>
                </div>
              </div>
            </form>
        </b-modal>
        <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
            <div class="col-sm-7">
                <h1 class="m-0 text-dark">GESTIONAR FACTURAS PARA PEDIDO NO {{order.order}} CODIGO NO {{order.code}}</h1> 
            </div><!-- /.col -->
            <div class="col-sm-5">
                <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a @click="returnOrders()" href="javascript:void(0);">PEDIDOS Y FACTURACIÓN</a></li>
                <li class="breadcrumb-item active">NUEVA FACTURA</li>
                </ol>
            </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
        </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row justify-content-center" >
            <div class="col-md-10 col-md-offset-1">
                <div class="col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header ui-sortable-handle" style="cursor: move;">
                            
                            <img class="direct-chat-img" src="../../../assets/invoice.png" alt="message user image">
                            <h3 class="card-title">
                                &nbsp; {{items.length}} FACTURAS EN TOTAL
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <ul class="todo-list ui-sortable" data-widget="todo-list">
                                <li v-for="(item, i) in items" :key="i" v-b-tooltip title="ver y generar factura" @click="viewInvoice(item,i)">
                                    <!-- drag handle -->
                                    <span class="handle ui-sortable-handle">
                                        <i class="fa fa-file"></i>
                                    </span>
                                    <!-- checkbox -->
                                    <!-- todo text -->
                                    <span class="text"> Factura # {{i+1}} con {{item.length}} productos a facturar</span>
                                    <!-- Emphasis label -->
                                    <!-- General tools such as edit or delete-->
                                    <div class="tools title">
                                        <i class="fa fa-eye"></i>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>

    </div>
  <!--Fin-Contenido-->
</template>

<script>
import moment from 'moment'
import FormError from '../../shared/FormError'
export default {
  name: 'index_invoice',
  components: {
      FormError
  },
  data() {
      return {
          loading: false,
          order: null,
          vat: {},
          serie: {},
          is_orders: true,
          items: [],
          products: [],
          form: {
              date: null,
              invoice: '',
              order_id: '',
              serie_id: null,
              serie_name: '',
              vat_id: null,
              vat_value: null,
              total: null,
              total_iva: null,
              details: []
          }
      }
  },

  created() {
    let self = this
    self.get(self.$route.params.id)
  },

    methods: {
        //get order
        get(id){
            let self = this
            self.loading = true
            self.$store.state.services.orderService.getOrder(id)
            .then(r=>{
                self.loading = false
                self.order = r.data.data
                self.form.order_id = self.order.id
                self.form.date = self.order.date
                self.items = self.chunkArray(self.order.details,5)
                self.$notify.info({
                    title: 'información',
                    message: 'este pedido por el numero de productos que cuenta se factura en '+ self.items.length+' facturas',
                    offset: 95,
                    duration: 4000
                })
                self.getVat()
                self.getSerie()
            }).catch(e=>{
                
            })
        },

        create(){
            let self = this
            var data = self.form
            self.loading = true
            self.$store.state.services.invoiceService
            .create(data)
            .then(r => {
                self.loading = false
                if(r.response){
                    this.$toastr.error(r.response.data.error, 'error')
                    return
                }
                this.$toastr.success('registro agregado con exito', 'exito')
                self.close()
                self.get(self.order.id)
            })
            .catch(r => {});
        },

        //divider array en items segun factura
        chunkArray(items, chunk_size){
            let self = this
            var index = 0
            items = items.filter(x=>!x.progress.check)
            if(items.length == 0){
               self.$notify.info({
                    title: 'información',
                    message: 'pedido ya se ha facturado completo',
                }) 

                self.$router.push('/invoice_index')
                return
            }

            var arrayLength = items.length
            var tempArray = []
            for (index = 0; index < arrayLength; index += chunk_size) {
                var myChunk = items.slice(index, index+chunk_size)
                // Do something if you want with the group
                tempArray.push(myChunk)
            }

            return tempArray
        },

        //get vat actual
        getVat(){
            let self = this
            self.$store.state.services.vatService.getAll()
            .then(r=>{
                self.vat = r.data.data.find(x=>x.actual)
                self.form.vat_id = self.vat.id
                if(self.vat == undefined){
                    self.$toastr.error('no existe ningun valor de iva activo','error')
                }
            }).catch(e=>{
                
            })
        },

        //get serie actual
        getSerie(){
            let self = this
            self.$store.state.services.serieService.getAll()
            .then(r=>{
                self.serie = r.data.data.find(x=>x.active)
                self.form.invoice = self.serie.actual_bill+1
                self.form.serie_id = self.serie.id
                if(self.serie == undefined){
                    self.$toastr.error('no existe ninguna serie activa o bien se agoto el numero de facturas','error')
                }
            }).catch(e=>{

            })
        },

        //funcion para eliminar registro
        confirmCreate(data){
            let self = this

            self.$swal({
                title: "¿Generar factura?",
                text: "Esta seguro de generar factura "+self.formatCode(self.serie.actual_bill+1, String(self.serie.total).length) + '?',
                type: "warning",
                showCancelButton: true
            }).then((result) => { // <--
                if (result.value) { // <-- if confirmed
                    self.create()
                }
            });
        },

        //funcion, validar si se guarda o actualiza
        invoiceGenerate(){
            let self = this
            this.$validator.validateAll().then((result) => {
                if (result) {
                    self.confirmCreate()
                }
            });

        },

        viewInvoice(items,i){
            let self = this
            if(i !== 0){
                self.$toastr.error('por favor genere las facturas en orden mostrado, existen '+(i)+' facturas que generar antes','error')
                return
            }
            items = items.map(obj=>({ ...obj, invoiced_as: ''}))
            self.form.details = items
            this.$refs['nuevo'].show()
        },

        //cerrar modal limpiar registros
        close(){
            let self= this
            self.$refs['nuevo'].hide()
        },

        //formatear codigo para tarjeta de reponsabilidades
        formatCode(n, len = 4) {
            return (new Array(len + 1).join('0') + n).slice(-len)
        },

        //funcion para eliminar registro
        returnOrders(data){
            let self = this

            self.$swal({
                title: "¿regresear a pedidos?",
                text: "Esta seguro que desea regresar a pedidos aun quedan "+self.items.length + ' facturas por facturar?',
                type: "warning",
                showCancelButton: true
            }).then((result) => { // <--
                if (result.value) { // <-- if confirmed
                    self.$router.push('/invoice_index')
                }
            });
        },
    },

    computed: {
        invoiceTotal(){
            let self = this
            var total = self.form.details.reduce((a,b)=>{
                return a + (b.progress.purchased_amount * b.sale_price)
            },0)

            self.form.total_iva = (total * (self.vat.value/100)).toFixed(2)
            self.form.total = total

            return total
        }
    }
}
</script>
