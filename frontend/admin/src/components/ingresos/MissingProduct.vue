<template>
<div v-loading="loading">
  <div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Productos faltantes</h1> 
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
                  <h3 class="card-title">Lista de productos faltantes </h3>
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
                    <b-table responsive hover flex
                        bordered
                       :fields="fields" 
                       :items="items"
                       :filter = "filter"
                       :current-page="currentPage"
                       :per-page="perPage"
                       @filtered="onFiltered">
                      <!-- A virtual column -->
                      
                      <template v-slot:cell(product_name)="data">
                        <span>
                          <a @click="showDetails(data.item)" href="javascript:void(0);" class="text-info pull-left"> <i :class="data.detailsShowing ? 'fa fa-arrow-down' : 'fa fa-arrow-right'"></i></a>
                        </span>&nbsp;
                        {{ data.item.product.name }}
                      </template>
                      <template v-slot:cell(subtraction)="data">
                        {{data.item.subtraction}}
                      </template>
                      <template v-slot:cell(year)="data">
                        {{data.item.year}}
                      </template>

                      <template slot="row-details"  slot-scope="data">
                          <el-tabs type="border-card">
                            {{ data.item.product.name }}
                          </el-tabs>
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
import FormError from '../shared/FormError'
export default {
  name: "missing_product",
  components: {
      FormError
  },
  data() {
    return {
      loading: false,
      items: [],
      fields: [
        { key: 'product_name', label: 'Producto', sortable: true },
        { key: 'subtraction', label: 'Faltante', sortable: true },
        { key: 'year', label: 'Año', sortable: true },
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
    self.getAll()
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

      self.$store.state.services.quantifyService
        .getAll()
        .then(r => {
          self.loading = false;
          r.data.data = r.data.data.map(obj=>({ ...obj, _showDetails: false}))
          self.items = r.data.data
          self.totalRows = self.items.length
        })
        .catch(r => {});
    },

    showDetails(data){
      let self = this
      if(data._showDetails){
        data._showDetails = false
        return
      }
      self.items.map(a=>a._showDetails=false)
      data._showDetails = true
    },
  },



  computed:{
  }
};
</script>