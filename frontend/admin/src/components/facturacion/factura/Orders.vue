<template>
<!--Contenido-->
        <div class="card" v-loading="loading">
              <div class="card-header no-border">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">PEDIDOS PENDIENTES DE FACTURAR</h3>
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
                      <template v-slot:cell(date)="data">
                        {{ data.item.date | moment('DD/MM/YYYY') }}
                      </template>
                      <template v-slot:cell(order)="data">
                        {{ data.item.order }}
                      </template>
                      <template v-slot:cell(code)="data">
                        {{data.item.code}}
                      </template>
                      <template v-slot:cell(type_order)="data">
                        {{data.item.type_order}}
                      </template>
                      <template v-slot:cell(school)="data">
                        {{data.item.school.name}}
                      </template>
                      <template v-slot:cell(option)="data">
                          <button type="button" class="btn btn-success btn-sm" @click="$router.push('/invoice_create/'+data.item.id)" v-tooltip="'facturar'">
                              <i class="fa fa-file">
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
export default {
  name: 'index_invoice',
  data() {
    return {
        loading: false,
        vat: {},
        serie: {},
        items: [],
        fields: [
            { key: 'date', label: 'Fecha entrega', sortable: true },
            { key: 'order', label: 'Numero de orden', sortable: true },
            { key: 'code', label: 'Codigo', sortable: true },
            { key: 'type_order', label: 'Tipo', sortable: true },
            { key: 'school', label: 'Escuela', sortable: true },
            { key: 'option', label: 'Opciones', sortable: true },
        ],
        filter: null,
        currentPage: 1,
        perPage: 5,
        totalRows: 0,
        pageOptions: [ 5, 10, 25 ],
        showStringEmpty: 'no hay pedidos pendientes de facturar',
    }
  },

  created() {
    let self = this
    self.getOrders()
  },

  methods: {
    onFiltered (filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length
      this.currentPage = 1
    },
    //get serie actual
    getOrders(){
        let self = this
        self.loading = true
        self.$store.state.services.orderService.getAllByInvoices()
        .then(r=>{
            self.loading = false
            self.items = r.data.data
            self.totalRows = self.items.length
        }).catch(e=>{

        })
    }
    },
}
</script>
