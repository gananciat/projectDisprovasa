<template>
    <div class="wrapper">
      <navHeader v-if="isLogin"></navHeader>
       <navegationmenu v-show="isLogin"></navegationmenu>
        <router-view></router-view>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->

    <fab v-if="isLogin"
      :position-type="'fixed'"
      :ripple-color="'light'"
      :icon-size="'small'"
      :position="position"
      :bg-color="'#dc3545'"
      :main-icon="'book'"
      :actions="fabActions"
      @a="alimentacion"
      @g="gratuidad"
      @u="utiles"
      @v="valijas"
    ></fab>

    <!-- Main Footer -->
    <footer class="main-footer" v-if="isLogin">
      <!-- To the right -->
      <div class="float-right d-none d-sm-block-down">
        
      </div>
      <!-- Default to the left -->
      <strong>Copyright &copy; 2020 <a >Sistema Proalsa - Escuela</a>.</strong> All rights reserved.
    </footer>
    </div>
</template>

<script>
import navHeader from "@/components/shared/NavHeader";
import navegationmenu from "@/components/shared/NavegationMenu";
import fab from 'vue-fab'

export default {
  components: {
    navHeader,
    navegationmenu,
    fab
  },
  props: {
    source: String
  },
  data(){
    return {
      position: 'down-right',
      fabActions: [
          {
              name: 'a',
              icon: 'kitchen',
              tooltip: 'Alimentación',
              color: '#28a745'
          },
          {
              name: 'g',
              icon: 'sports_hockey',
              tooltip: 'Gratuidad',
              color: '#17a2b8'
          },
          {
              name: 'u',
              icon: 'school',
              tooltip: 'Utiles',
              color: '#007bff'
          },
          {
              name: 'v',
              icon: 'book',
              tooltip: 'Valija',
              color: '#ffc107'
          }
      ]
    }
  },
  methods: {
    alimentacion(){
      this.$router.push('/school/0/management/order/new/alimentacion/A') 
    },    
    gratuidad(){
      this.$router.push('/school/0/management/order/new/gratuidad/G') 
    },    
    utiles(){
      this.$router.push('/school/0/management/order/new/utiles/U') 
    },    
    valijas(){
      this.$router.push('/school/0/management/order/new/valijas/V') 
    }
  },
    computed: {
    
    isLogin(){
      let self = this
      return self.$store.state.is_login
    }
  }
};
</script>
