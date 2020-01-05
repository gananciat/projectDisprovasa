<template>
<!--Contenido-->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Pedidos</h1>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
     </div>
    </div>

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
                <div class="row">
                    <div class="col-lg-3 col-6">
                    <!-- small card -->
                        <div class="small-box bg-info">
                            <div class="inner">
                            <h3>150</h3>

                            <p>Todos</p>
                            </div>
                            <div class="icon">
                            <i class="fa fa-shopping-cart"></i>
                            </div>
                            <a href="JavaScript:Void(0);" @click="showOption('T')" class="small-box-footer">
                            Ver <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                    <!-- small card -->
                        <div class="small-box bg-info">
                            <div class="inner">
                            <h3>15</h3>

                            <p>Nuevos pedidos</p>
                            </div>
                            <div class="icon">
                            <i class="fa fa-shopping-cart"></i>
                            </div>
                            <a href="JavaScript:Void(0);" @click="showOption('N')" class="small-box-footer">
                            Ver <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                    <!-- small card -->
                        <div class="small-box bg-info">
                            <div class="inner">
                            <h3>10</h3>

                            <p>Pedidos en proceso</p>
                            </div>
                            <div class="icon">
                            <i class="fa fa-shopping-cart"></i>
                            </div>
                            <a href="JavaScript:Void(0);" @click="showOption('P')" class="small-box-footer">
                            Ver <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                    <!-- small card -->
                        <div class="small-box bg-info">
                            <div class="inner">
                            <h3>10</h3>

                            <p>Pedidos completados</p>
                            </div>
                            <div class="icon">
                            <i class="fa fa-shopping-cart"></i>
                            </div>
                            <a href="JavaScript:Void(0);" @click="showOption('C')" class="small-box-footer">
                            Ver <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                    <!-- ./col -->
                </div>
                <el-divider></el-divider>
                <div class="row">
                    <template>
                    <div class="col-sm-12">
                        <div class="d-flex">
                        <h4 class="d-flex flex-column">
                            {{option_title}}
                        </h4>
                        </div>
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
                      <template v-slot:cell(school)="data">
                        {{ data.item.school.name }}
                      </template>
                      <template v-slot:cell(school)="data">
                        {{ data.item.total }}
                      </template>
                      <template v-slot:cell(option)="data">
                          <button type="button" class="btn btn-info btn-sm" v-tooltip="'ver'">
                              <i class="fa fa-eye">
                              </i>
                          </button>
                          <button type="button" class="btn btn-danger btn-sm" v-tooltip="'gestionar pedido'">
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
                </div>
              </div>
            </div>
          </div>
          <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </div>
   
    
    <!-- /.content -->
  </div>

  <!--Fin-Contenido-->
</template>

<script>
export default {
  name: 'IndexOrders',
   data() {
    return {
      loading: false,
      items: [],
      option_title: 'Mostrando todos los pedidos',
      fields: [
        { key: 'school', label: 'Escuela', sortable: true },
        { key: 'total', label: 'Total Pedido', sortable: true },
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
    let self = this
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
      self.$store.state.services.orderService
        .getAll()
        .then(r => {
          self.loading = false; 
          self.items = r.data.data;
          self.totalRows = self.items.length
        })
        .catch(r => {});
    },

    //mapear opcion para filtrado de pedidos
    showOption(option){
        let self = this
        switch (option) {
            case 'N':
                self.option_title = 'Mostrando nuevos pedidos'
                break;
            case 'P':
                self.option_title = 'Mostrando pedidos en proceso'
                break;
            case 'C':
                self.option_title = 'Mostrando pedidos completados'
                break;
            default:
                self.option_title = 'Mostrando todos los pedidos'
            }
    }
  }
}
</script>