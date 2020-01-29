<template>
<!--Contenido-->
        <div class="card" v-loading="loading">

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
                        {{data.item.date}}
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
                          <button type="button" class="btn btn-info btn-sm">
                              <i class="fa fa-print">
                              </i>
                          </button>
                          <button @click="edit(data.item)" v-if="!data.item.cancel" type="button" class="btn btn-primary btn-sm">
                              <i class="fa fa-pencil">
                              </i>
                          </button>
                          <button v-if="!data.item.cancel" type="button" @click="cancel(data.item)" class="btn btn-danger btn-sm">
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
    export default {
    name: 'index_invoice',
    components: {
        FormError
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
        },

        edit(item){
            let self = this
            this.$refs['nuevo'].show()
            self.form.date = item.date
            self.form.invoice_name = item.serie.serie+'-'+self.formatCode(item.invoice,String(item.serie.total).length)
        },

        //cerrar modal limpiar registros
        close(){
            let self= this
            self.$refs['nuevo'].hide()
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
    },
}
</script>
