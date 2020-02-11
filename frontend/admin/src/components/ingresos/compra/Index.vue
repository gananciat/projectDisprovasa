<template>
<div>
  <div class="content-wrapper" v-loading="loading">

    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">COMPRAS</h1> 
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header no-border">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">Lista de compras 
                    <b-button variant="success" size="sm" @click="$router.push(`/purchase_create`)"><i class="fa fa-plus"></i> nueva compra</b-button></h3>
                </div>
              </div>
              <div class="card-body">
                <div class="d-flex">
                  <p class="d-flex flex-column">
                  </p>
                </div>
                <!-- /.d-flex -->
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
                      <template v-slot:cell(no_prof)="data">
                        {{ data.item.no_prof }}
                      </template>
                      <template v-slot:cell(provider)="data">
                        {{data.item.provider.name}}
                      </template>
                      <template v-slot:cell(total)="data">
                        {{data.item.total | currency('Q ')}}
                      </template>
                      <template v-slot:cell(cancel)="data">
                          <span v-if="data.item.cancel" class="badge bg-danger"> Anulada</span>
                          <span v-else class="badge bg-success"> Aceptada</span>
                      </template>
                      <template v-slot:cell(option)="data">
                          <button type="button" @click="$router.push(`/purchase_view_info/`+data.item.id)" class="btn btn-info btn-sm" v-tooltip="'ver y editar información'">
                            <i class="fa fa-eye" >
                              </i>
                        </button>
                        <button type="button" v-if="!data.item.cancel" @click="update(data.item)" class="btn btn-danger btn-sm" v-tooltip="'anular compra'">
                            <i class="fa fa-ban">
                              </i>
                        </button>
                      </template>

                    </b-table>
                    <b-row>
                        <b-col md="12" class="my-1">
                           <label v-if="totalRows > 0">total: {{totalRows}} registros</label> 
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
            <!-- /.card -->
            <!-- /.card -->
          </div>
          <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
</div>
</template>

<script>
export default {
  name: "purcharse",
  components: {
  },
  data() {
    return {
      loading: false,
      items: [],
      fields: [
        { key: 'date', label: 'Fecha', sortable: true },
        { key: 'no_prof', label: 'Factura', sortable: true },
        { key: 'provider', label: 'Provider', sortable: true },
        { key: 'total', label: 'Total', sortable: true },
        { key: 'cancel', label: 'Estado', sortable: true },
        { key: 'option', label: 'Opciones', sortable: true },
      ],
      filter: null,
      currentPage: 1,
      perPage: 5,
      totalRows: 0,
      pageOptions: [ 5, 10, 25 ],
      showStringEmpty: 'no hay registros que mostrar'
    };
  },
  created() {
    let self = this;
    self.getAll();
  },

  methods: {
    onFiltered (filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length
      this.currentPage = 1
    },

    getAll() {
      let self = this;
      self.loading = true;

      self.$store.state.services.purchaseService
        .getAll()
        .then(r => {
          self.loading = false; 
          self.items = r.data.data;
          self.totalRows = self.items.length
        })
        .catch(r => {});
    },

    //funcion para eliminar registro
    update(data){
      let self = this

      self.$swal({
        title: "¿Anular compra?",
        text: "Esta seguro de anular "+data.no_prof + '?',
        type: "warning",
        showCancelButton: true
      }).then((result) => { // <--
          if (result.value) { // <-- if confirmed
              self.loading = true
              self.$store.state.services.purchaseService
                .update(data)
                .then(r => {
                  self.loading = false
                  if(r.response){
                        this.$toastr.error(r.response.data.error, 'error')
                        return
                    }
                  this.$toastr.success('compra ha sido anulada con exito', 'exito')
                  self.getAll()
                })
                .catch(r => {});
          }
      });
    },
  },

  computed:{
  }
};
</script>