<template>
<!--Contenido-->
        <div class="card" v-loading="loading">
        <b-modal ref="print" :title="'imprimir factura '+form.invoice_name" size="lg" hide-footer class="modal-backdrop" no-close-on-backdrop>
          <print></print>
        </b-modal>

          <b-modal ref="nuevo" :title="'editar factura '+form.invoice_name" size="xl" hide-footer class="modal-backdrop" no-close-on-backdrop>
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
                <div class="row">
                  <div class="col-md-12 col-lg-12">
                    <div class="card">
              <div class="card-header">
                <h3 class="card-title">Editar nombres para facturar</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <table class="table table-sm table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Nombre producto</th>
                      <th>Facturar como</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(p,i) in form.details" :key="i">
                      <td>{{i+1}}</td>
                      <td>{{p.progress.product.name}}</td>
                      <td><div v-if="p.progress.product.camouflage">
                              <input class="form-control form-control-sm" v-model="p.invoiced_as" 
                                :name="p.progress.product.name"
                                :data-vv-as="'facturar como '+p.progress.product.name | lowercase "
                                v-validate="'required|min:5|max:250'"
                                :class="{'input':true,'has-errors': errors.has(p.progress.product.name)}">
                                <FormError :attribute_name="p.progress.product.name" :errors_form="errors"> </FormError>
                          </div></td>
                    </tr>
                    <tr>
                      <td colspan="3">
                      <p class="text-center text-bold text-primary" v-if="form.details.length === 0">ningun producto facturado con otro nombre</p>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
                  </div>
                </div>
              <div class="row">
                <div class="col-12 text-right">
                  <button @click="close" type="button" class="btn btn-danger btn-sm"><i class="fa fa-undo"></i> Cancelar</button>
                  <button @click="update" type="button" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Actualizar</button>
                </div>
              </div>
            </form>
          </b-modal>
              <div class="card-header no-border">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">FACTURAS EMITIDAS</h3>
                </div>
              </div>
              <div class="card-body">
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
                      <template v-slot:cell(invoice)="data">
                        {{ getFullInvoice(data.item) }}
                      </template>
                      <template v-slot:cell(order)="data">
                        # {{ data.item.order.order }}
                      </template>
                      <template v-slot:cell(date)="data">
                        {{data.item.date | moment('DD/MM/YYYY')}}
                      </template>
                      <template v-slot:cell(school)="data">
                        {{data.item.order.school.name}}
                      </template>
                       <template v-slot:cell(total)="data">
                        {{data.item.total | currency('Q ')}}
                      </template>
                       <template v-slot:cell(total_iva)="data">
                        {{data.item.total_iva | currency('Q ')}} ({{data.item.vat.value}}%)
                      </template>
                      <template v-slot:cell(status)="data">
                        <b-badge class="bg-red" v-if="data.item.cancel"> Anulada</b-badge>
                        <b-badge class="bg-green" v-else> Activa</b-badge>
                      </template>
                      <template v-slot:cell(option)="data">
                          <button @click="openPrint(data.item)" type="button" class="btn btn-info btn-sm" v-tooltip="'ver e imprimir'">
                              <i class="fa fa-print">
                              </i>
                          </button>
                          <button @click="edit(data.item)" v-if="!data.item.cancel" type="button" class="btn btn-primary btn-sm" v-tooltip="'editar'">
                              <i class="fa fa-pencil">
                              </i>
                          </button>
                          <button v-if="!data.item.cancel" type="button" @click="cancel(data.item)" class="btn btn-danger btn-sm" v-tooltip="'anular'">
                              <i class="fa fa-ban">
                              </i>
                          </button>
                      </template>

                    </b-table>
                    <b-row>
                        <b-col md="12" class="my-1">
                           <label v-if="totalRows > 0">total: {{totalRows}} pedidos por facturar</label> 
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
  <!--Fin-Contenido-->
</template>

<script>
    import moment from 'moment'
    import FormError from '../../shared/FormError'
    import Print from './Print'
    export default {
    name: 'index_invoice',
    components: {
        FormError,
        Print
    },
    data() {
        return {
            loading: false,
            vat: {},
            serie: {},
            items: [],
            fields: [
                { key: 'invoice', label: 'Factura', sortable: true },
                { key: 'date', label: 'Fecha emisión', sortable: true },
                { key: 'order', label: 'Pedido', sortable: true },
                { key: 'school', label: 'Escuela', sortable: true },
                { key: 'total', label: 'Monto facturado', sortable: true },
                { key: 'total_iva', label: 'Iva a cancelar', sortable: true },
                { key: 'status', label: 'Estado', sortable: true },
                { key: 'option', label: 'Opciones', sortable: true },
            ],
            filter: null,
            currentPage: 1,
            perPage: 5,
            totalRows: 0,
            pageOptions: [ 5, 10, 25 ],
            showStringEmpty: 'no hay pedidos pendientes de facturar',
            form:{
                id: null,
                date: '',
                invoice_name: '',
                details: []
            }
        }
    },

    created() {
        let self = this
        self.getAll()
    },

    methods: {
        onFiltered (filteredItems) {
        // Trigger pagination to update the number of buttons/pages due to filtering
        this.totalRows = filteredItems.length
        this.currentPage = 1
        },
        //get serie actual
        getAll(){
            let self = this
            self.loading = true
            self.$store.state.services.invoiceService.getAll()
            .then(r=>{
                self.loading = false
                self.items = r.data.data
                self.totalRows = self.items.length
            }).catch(e=>{

            })
        },

        //funcion para eliminar registro
        cancel(data){
            let self = this

            self.$swal({
                title: "anular factura?",
                text: "Esta seguro de anular factura "+self.getFullInvoice(data) + '?',
                type: "warning",
                showCancelButton: true
            }).then((result) => { // <--
                if (result.value) { // <-- if confirmed
                    self.loading = true
                    self.$store.state.services.invoiceService
                        .cancel(data.id)
                        .then(r => {
                        self.loading = false
                        if(r.response){
                            this.$toastr.error(r.response.data.error, 'error')
                            return
                        }
                        this.$toastr.success('factura anulada con exito', 'exito')
                        self.getAll()
                        })
                        .catch(r => {});
                }
            });
        },

        //update invice
        update(){
            let self = this
             this.$validator.validateAll().then((result) => {
                if (result) {
                  var data = self.form
                  self.loading = true
                  self.$store.state.services.invoiceService
                  .update(data)
                  .then(r => {
                      self.loading = false
                      if(r.response){
                          this.$toastr.error(r.response.data.error, 'error')
                          return
                      }
                      this.$toastr.success('factura actualizada con exito', 'exito')
                      self.close()
                      self.getAll()
                  })
                  .catch(r => {});
                }
            });
        },

        edit(item){
            let self = this
            this.$refs['nuevo'].show()
            self.loading = true
            self.$store.state.services.invoiceService.get(item.id)
            .then(r=>{
                self.loading = false
                self.form.details = r.data.data.products.filter(x=>x.progress.product.camouflage)
            }).catch(e=>{

            })
            self.form.id = item.id
            self.form.date = item.date
            self.form.invoice_name = item.serie.serie+'-'+self.formatCode(item.invoice,String(item.serie.total).length)
        },

        //cerrar modal limpiar registros
        close(){
            let self= this
            self.$refs['nuevo'].hide()
            self.$refs['print'].hide()
        },

        getFullInvoice(item){
            let self = this
            var invoice = self.formatCode(item.invoice,String(item.serie.total).length)
            return item.serie.serie+'-'+invoice
        },

        //formatear codigo para tarjeta de reponsabilidades
        formatCode(n, len = 4) {
            return (new Array(len + 1).join('0') + n).slice(-len)
        },

        openPrint(item){
          let self = this
          self.$refs['print'].show()
          self.form.invoice_name = item.serie.serie+'-'+self.formatCode(item.invoice,String(item.serie.total).length)

          self.$nextTick(()=>{
            events.$emit('get_invoice',item.id)
          })
        }
    },
}
</script>
