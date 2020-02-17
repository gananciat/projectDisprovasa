<template>
<div>

  <div class="content-wrapper" v-loading="loading">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Ideas de Menú</h1> 
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-12 col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Listado de Sugerencias</h3>
              </div>
              <div class="card-body">
                <div id="accordion">

                  <template v-for="(item, index) in items">
                    <div class="card card-primary" v-bind:key="index">
                      <div class="card-header">
                        <h4 class="card-title">
                          <a data-toggle="collapse" data-parent="#accordion" :href="'#'+item.id" class="collapsed" aria-expanded="false"> {{ item.title }} </a>
                        </h4>
                      </div>
                      <div :id="item.id" class="panel-collapse in collapse" style="">
                        <div class="card-body">
                          <p> {{ item.description }} </p>
                          <ul>

                            <template v-for="(detail, index_detail) in item.details">
                              <li v-bind:key="index_detail">{{ detail.product.name+' / MARCA: '+detail.product.presentation.name }}</li>
                            </template>
                            <router-link class="btn btn-success btn-md pull-right" v-b-tooltip.left v-b-tooltip.hover title="realizar pedido" :to="'/school/management/order/new/suggestion/'+item.id">Realizar pedido</router-link>

                          </ul>
                        </div>
                      </div>
                    </div>
                  </template>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
</template>

<script>
import FormError from '../shared/FormError'
export default {
  name: "order",
  components: {
      FormError,
  },
  data() {
    return {
      loading: false,
      items: [],    
    };
  },
  created() {
    let self = this;
    self.getAll()
  },

  methods: {
    getAll() {
      let self = this;
      self.loading = true;
      self.items = []
        
      self.$store.state.services.menusuggestionService
        .getAll()
        .then(r => {
            self.loading = false; 
            self.items = r.data.data;     
        })
        .catch(r => {});
    },
  },
  mounted(){
    $("body").resize()
  },
};
</script>