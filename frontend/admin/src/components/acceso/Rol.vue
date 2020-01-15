<template>
<div>
  <div class="content-wrapper" v-loading="loading">

    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">ROLES</h1> 
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
                  <h3 class="card-title">Lista de roles </h3>
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
                    <b-table responsive hover small flex
                        bordered
                       :fields="fields" 
                       :items="items" head-variant="dark">
                      <!-- A virtual column -->
                      <template v-slot:cell(name)="data">
                        {{ data.item.name }}
                      </template>
                       <template v-slot:cell(type)="data">
                        <span v-if="data.item.administrative"> ADMINISTRATIVO</span>
                         <span v-else>ESCUELA</span>
                      </template>

                    </b-table>
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
  name: "rol",
  components: {
  },
  data() {
    return {
      loading: false,
      items: [],
      fields: [
        { key: 'name', label: 'Nombre', sortable: false },
        { key: 'type', label: 'Tipo', sortable: false },
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

      self.$store.state.services.rolService
        .getAll()
        .then(r => {
          self.loading = false; 
          self.items = r.data.data;
          self.totalRows = self.items.length
        })
        .catch(r => {});
    },
  },

  computed:{
  }
};
</script>

<style scoped>
  #myTable2 {
    font-size: 15px;
  }

  #myTable2 > thead {
     background-color: #3c8dbc !important;
     color: white;
  }
</style>