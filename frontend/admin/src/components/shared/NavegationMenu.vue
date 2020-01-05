<template>

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#/" class="brand-link">
      <img src="../../assets/logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">Sistema dispro</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="../../assets/logo.png" class="img-circle elevation-2" alt="User Image">
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
          <template>
            
          <li class="nav-item" v-for="item in items" :key="item.text">
            <a href="#/" class="nav-link" v-if="item.children.length === 0">
              <i :class="'nav-icon fa fa-'+item.icon"></i>
              <p>
                {{item.text}}
              </p>
            </a>
            <a href="#" class="nav-link" v-if="item.children.length > 0">
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
          </template>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside> 
<!-- <aside class="main-sidebar">
        <section class="sidebar">      
          <ul class="sidebar-menu">
            <li class="header"> Titulo Menu</li>
            <li>
              <a href="#">
                <i class="fa fa-tasks"></i> <span>Escritorio</span>
              </a>
            </li>  
            <template v-for="item, i in items">
                <li class="treeview">
                  <a href="#">
                    <i class="fa fa-laptop"></i>
                    <span>{{item.text}}</span>
                    <i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">
                   <template v-for="child, c in item.children" :index="(i.toString() + c)">
                    <router-link tag="li" :to="''+child.path" exact>
                            <a><i :class="'fa fa-' + child.icon"></i>{{child.text}}</a>
                        </router-link>
                   </template>
                   </ul>

                </li>
            </template>

            <li> 
              <a href="#">
                <i class="fa fa-plus-square"></i> <span>Ayuda</span>
                <small class="label pull-right bg-red">PDF</small>
              </a>
            </li>
            <li>
              <a href="#">
                <i class="fa fa-info-circle"></i> <span>Acerca De...</span>
                <small class="label pull-right bg-yellow">IT</small>
              </a>
            </li>
                        
          </ul>
        </section>
      </aside>-->
</template>

<script>
export default {
  name: "NavegationMenu",
  data: () => ({
    items: [
      {
        icon: "cog",
        text: "Administración",
        children: [
          { icon: "circle-o", text: "Categorías", path: "/category" },
          { icon: "circle-o", text: "Companias", path: "/company" },
          { icon: "circle-o", text: "Marcas", path: "/presentation" },
          { icon: "circle-o", text: "Productos", path: "/product" },
          { icon: "circle-o", text: "Escuelas", path: "/school" },
        ]
      },
      {
        icon: "cog",
        text: "Pedidos",
        children: [
          { icon: "circle-o", text: "Pedido realizados", path: "/school/management/order" },
          { icon: "circle-o", text: "Pedido de alimentación", path: "/school/1/management/order/new/alimentacion/A" },
          { icon: "circle-o", text: "Pedido de gratuidad", path: "/school/1/management/order/new/gratuidad/G" },
          { icon: "circle-o", text: "Pedido de utiles", path: "/school/1/management/order/new/utiles/U" },
        ]
      },      
      {
        icon: "user",
        text: "Acceso",
        children: [
          { icon: "circle-o", text: "Usuarios", path: "/" },
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
      return self.$store.state.usuario.email
    }
  }
};
</script>