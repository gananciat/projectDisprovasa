<template>
<div v-loading="loading">

      <div>
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
          {{ data.item.product.name }}
        </template>
        <template v-slot:cell(date)="data">
          {{data.item.date | moment('DD/MM/YYYY')}}
        </template>
        <template v-slot:cell(status)="data">
          <b-badge class="bg-red" v-if="data.item.expiration">VENCIDO</b-badge>
          <b-badge class="bg-yellow" v-else>PROXIMO A VENCER</b-badge>
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
            <!-- /.card -->
            <!-- /.card -->
  </div>
</template>

<script>
import moment from 'moment'
import FormError from '../../shared/FormError'
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
        { key: 'date', label: 'Fecha expiración', sortable: true },
        { key: 'status', label: 'Estado', sortable: true },
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

      self.$store.state.services.productExpirationService
        .getAll()
        .then(r => {
          self.loading = false
          self.items = r.data.data
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

</style>