<template>
<div>

  <div class="content-wrapper" v-loading="loading">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          </div>
        </div>
      </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <b-jumbotron header="Pedidos listos para transportar a las escuelas">
            <hr>
            <div class="row">
                <template v-for="(item, index) in items">
                    
                    <div v-bind:key="index" class="col-sm-12 col-md-6 col-lg-6">
                        <router-link v-b-tooltip title="subir producto al transporte" v-bind:key="index" :to="'/transport/check/'+item.id" >
                            <div class="info-box mb-3">
                                <span :class="box(item.type_order)"><i :class="icon(item.type_order)"></i></span>
                                <div class="info-box-content" style="color: black;">
                                    <span class="info-box-number">{{ item.order }}</span>
                                    <span class="info-box-text">{{ item.school.name }}</span>
                                    <span class="info-box-text">{{ item.code }}</span>
                                    <span class="info-box-text">{{ item.title }}</span>
                                </div>
                            </div>      
                        </router-link>                  
                    </div>

                </template>
            </div>            
        </b-jumbotron>
      </div>
    </div>
    <!-- /.content -->
  </div>
</div>
</template>

<script>
import moment from 'moment'
import FormError from '../shared/FormError'
export default {
  name: "transport",
  components: {
      FormError
  },
  data() {
    return {
      loading: false,
      items: [],
    };
  },
  created() {
      let self = this
      self.getOrdersComplete()
  },
  methods: {
      getOrdersComplete(){
            let self = this
            self.loading = true;

            self.$store.state.services.checkdeliverymanService
                .getAll()
                .then(r => {
                    self.loading = false; 
                    self.items = r.data.data;
                })
                .catch(r => {});          
      },

      box(type_order){
          let color = ""

          switch (type_order) {
                case "ALIMENTACION":
                    color = "info-box-icon bg-success"
                break;
                case "UTILES":
                    color = "info-box-icon bg-primary"
                break;
                case "GRATUIDAD":
                    color = "info-box-icon bg-info"
                break;
                case "VALIJA DIDACTICA":
                    color = "info-box-icon bg-default"
                break;
          }

          return color
      },

      icon(type_order){
          let icon = ""

          switch (type_order) {
                case "ALIMENTACION":
                    icon = "fa fa-apple"
                break;
                case "UTILES":
                    icon = "fa fa-file"
                break;
                case "GRATUIDAD":
                    icon = "fa fa-book"
                break;
                case "VALIJA DIDACTICA":
                    icon = "fa fa-book"
                break;
          }

          return icon          
      }
  },
  mounted(){
        $("body").resize()
  },  
};
</script>