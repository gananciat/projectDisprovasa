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

    <!-- Main Footer -->
    <footer class="main-footer" v-if="isLogin">
      <!-- To the right -->
      <div class="float-right d-none d-sm-block-down">
        
      </div>
      <!-- Default to the left -->
      <strong>Copyright &copy; 2020 <a >sistema disprovasa</a>.</strong> All rights reserved.
    </footer>
    </div>
</template>

<script>
import navHeader from "@/components/shared/NavHeader";
import navegationmenu from "@/components/shared/NavegationMenu";

export default {
  components: {
    navHeader,
    navegationmenu
  },
  props: {
    source: String
  },
  data(){
    return {

    }
  },
  methods: {

  },
    computed: {
    
    isLogin(){
      let self = this
      return self.$store.state.is_login
    }
  }
};
</script>

<style>
.highcharts-credits {
  display: none !important;
}
</style>
