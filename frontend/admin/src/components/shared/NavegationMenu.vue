<template>

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#/" class="brand-link">
      <img src="../../assets/logo.jpeg" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">Sistema dispro</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img :src="getImage" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">
            <small>
              {{userName}}
              </small>
            </a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

          <li class="nav-item">
            <a href="#/" class="nav-link">
              <i class="nav-icon fa fa-home"></i>
              <p>
                Home
                <span class="right badge badge-danger"><i class="fa fa-home"></i></span>
              </p>
            </a>
          </li>
          <template>
            
          <li class="nav-item" v-for="item in getMenu" :key="item.text">
            <a href="#/" class="nav-link" v-if="item.childrens.length === 0">
              <i :class="'nav-icon fa fa-'+item.icon"></i>
              <p>
                {{item.text}}
              </p>
            </a>
            <a href="#" class="nav-link" v-if="item.childrens.length > 0">
              <i :class="'nav-icon fa fa-'+item.icon"></i>
              <p>
                {{item.text}}
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" v-for="child in item.childrens" :key="child.text">
              <li class="nav-item">
                <a :href="'#/'+child.path" class="nav-link">
                  <i :class="'fa fa-'+child.icon+' nav-icon'"></i>
                  <p>{{child.text}}</p>
                </a>
              </li>
            </ul>
          </li>
          </template>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside> 
</template>

<script>
export default {
  name: "NavegationMenu",
  data: () => ({

  }),

  methods: {
    redirect(path) {
      if(path === undefined) return;
      this.$router.push(path);
    }
  },

  mounted() {
    let self = this
    $("body").resize()
  },

  computed: {
    userName(){
      let self = this
      var user = self.$store.state.usuario
      if(!_.isEmpty(user)){
        return self.$store.state.usuario.people.name_one+' '+self.$store.state.usuario.people.last_name_one
      }
      return ''
    },

    getMenu(){
      let self = this
      return self.$store.state.menu
    },

    getImage(){
      let self = this
      return self.$store.state.base_url+'img/user_empty.jpg'
    }
  }
};
</script>