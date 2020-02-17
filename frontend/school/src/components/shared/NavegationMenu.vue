<template>

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Sidebar -->
    <div class="sidebar text-center">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image"><img v-bind:src="getLogo" class="img-circle elevation-2" alt="Logo"></div>
        <div class="info" style="font-size: 12px; color:white;">{{userName}}</div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2 text-left">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
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
          <li class="nav-item" v-for="item in items" :key="item.text">
            <a href="#" class="nav-link">
              <i :class="'nav-icon fa fa-'+item.icon"></i>
              <p>
                {{item.text}}
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" v-for="child in item.children" :key="child.text">
              <li class="nav-item">
                <a :href="'#'+child.path" class="nav-link">
                  <i :class="'fa fa-'+child.icon+' nav-icon'"></i>
                  <p>{{child.text}}</p>
                </a>
              </li>
            </ul>
          </li>
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
    id: '',
    items: [
      {
        icon: "cog",
        text: "Pedidos",
        children: [
          { icon: "circle-o", text: "Pedido realizados", path: "/school/management/order" },
          { icon: "circle-o", text: "Pedido de alimentación", path: "/school/0/management/order/new/alimentacion/A" },
          { icon: "circle-o", text: "Pedido de gratuidad", path: "/school/0/management/order/new/gratuidad/G" },
          { icon: "circle-o", text: "Pedido de utiles", path: "/school/0/management/order/new/utiles/U" },
          { icon: "circle-o", text: "Pedido de valijas", path: "/school/0/management/order/new/valijas/V" },
          { icon: "circle-o", text: "Recibir pedido", path: "/school/management/receive/order" },
        ]
      }, 
      {
        icon: "cog",
        text: "Configuración",
        children: [
          { icon: "circle-o", text: "Calendario", path: "/school/management/calendar" },
          { icon: "circle-o", text: "Sugerencia de Menú", path: "/school/menu/suggestion" },
        ]
      },     
    ]
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
      self.id = self.$store.state.school.schools_id
      return self.$store.state.people.name_one+' '+self.$store.state.people.last_name_one
    },

    getLogo(){
      let self = this
      var school = self.$store.state.school
      if(!_.isEmpty(school)){
        if(school.school.logo != null)
          return self.$store.state.base_url+school.school.logo
      }
      return self.$store.state.base_url+'img/logo_empty.png'
    }
  }
};
</script>

<style scoped>
  .brand-circle {
      border-radius: 10%
  }
</style>