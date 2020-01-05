<template>
<div>

  <div class="content-wrapper" v-loading="loading">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Pedidos</h1> 
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
                  <h3 class="card-title">Lista de pedidos</h3>
                </div>
              </div>
              <div class="card-body">
                <el-tabs tab-position="left" @tab-click="handleClick" style="height: 200px;">
                  <el-tab-pane label="ALIMENTACION">
                    <h1>Pedidos de alimentación</h1>
                    &nbsp;
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
                            {{ data.item.date | moment('dddd DD MMMM YYYY') }}
                          </template>                          
                          <template v-slot:cell(option)="data">    
                              <router-link class="btn btn-info btn-sm" :to="'/information/school/'+data.item.id" v-tooltip="'mostrar información'"><i class="fa fa-eye"></i></router-link>                  
                              <button type="button" class="btn btn-danger btn-sm" @click="destroy(data.item)" v-tooltip="'eliminar'">
                                  <i class="fa fa-trash">
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
                  </el-tab-pane>
                  <el-tab-pane label="GRATUIDAD">
                    <h1>Pedidos de gratuidad</h1>
                    &nbsp;
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
                            {{ data.item.date | moment('dddd DD MMMM YYYY') }}
                          </template>                          
                          <template v-slot:cell(option)="data">    
                              <router-link class="btn btn-info btn-sm" :to="'/information/school/'+data.item.id" v-tooltip="'mostrar información'"><i class="fa fa-eye"></i></router-link>                  
                              <button type="button" class="btn btn-danger btn-sm" @click="destroy(data.item)" v-tooltip="'eliminar'">
                                  <i class="fa fa-trash">
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
                  </el-tab-pane>
                  <el-tab-pane label="UTILES">
                    <h1>Pedidos de utiles</h1>
                    &nbsp;
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
                            {{ data.item.date | moment('dddd DD MMMM YYYY') }}
                          </template>                          
                          <template v-slot:cell(option)="data">    
                              <router-link class="btn btn-info btn-sm" :to="'/information/school/'+data.item.id" v-tooltip="'mostrar información'"><i class="fa fa-eye"></i></router-link>                  
                              <button type="button" class="btn btn-danger btn-sm" @click="destroy(data.item)" v-tooltip="'eliminar'">
                                  <i class="fa fa-trash">
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
                  </el-tab-pane>
                </el-tabs>
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
import FormError from '../shared/FormError'
export default {
  name: "order",
  components: {
      FormError
  },
  data() {
    return {
      loading: false,
      items: [],
      fields: [
        { key: 'order', label: '#', sortable: true },
        { key: 'title', label: 'Título', sortable: true },
        { key: 'description', label: 'Descripción', sortable: true },
        { key: 'date', label: 'Fecha', sortable: true },
        { key: 'type_order', label: 'Tipo de Pedido', sortable: true },
        { key: 'option', label: 'Opciones', sortable: true },
      ],
      filter: null,
      currentPage: 1,
      perPage: 5,
      totalRows: 0,
      pageOptions: [ 5, 10, 25 ],
      showStringEmpty: 'no hay registros que mostrar',
    };
  },
  created() {
    let self = this;
    self.getAll('ALIMENTACION');
  },
  methods: {

    onFiltered (filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length
      this.currentPage = 1
    },

    handleClick(tab, event) {
      let self = this
      self.getAll(event.originalTarget.innerText)
    },

    getAll(type_order) {
      console.log(type_order)
      let self = this;
      self.loading = true;

      self.$store.state.services.orderService
        .get(type_order)
        .then(r => {
          self.loading = false; 
          self.items = r.data.data;
          self.totalRows = self.items.length
        })
        .catch(r => {});
    },

    //funcion para eliminar registro
    destroy(data){
      let self = this

      self.$swal({
        title: "¿Eliminar registro?",
        text: "Esta seguro de elminar "+data.name + '?',
        type: "warning",
        showCancelButton: true
      }).then((result) => { // <--
          if (result.value) { // <-- if confirmed
              self.loading = true
              self.$store.state.services.schoolService
                .destroy(data)
                .then(r => {
                  self.loading = false
                  if(r.response){
                        this.$toastr.error(r.response.data.error, 'error')
                        return
                    }
                  this.$toastr.success('registro eliminado con exito', 'exito')
                  self.getAll()
                  self.close()
                })
                .catch(r => {});
          }
      });
    },
  },
  mounted(){
    $("body").resize()
  },
};
</script>