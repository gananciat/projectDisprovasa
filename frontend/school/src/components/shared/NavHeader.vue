<template>
  <div>
  <nav class="main-header navbar navbar-expand text-sm navbar-dark navbar-primary">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#/" class="nav-link"><h5>{{ getSchoolName }}</h5></a>
      </li>
    </ul>

    <!-- SEARCH FORM 
    <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fa fa-search"></i>
          </button>
        </div>
      </div>
    </form>-->

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a @click="logout" class="nav-link" href="#"><i
            class="fa fa-sign-out"></i></a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar --> 
  </div>

</template>

<script>
export default {
  name: 'NavHeader',
  data(){
    return {
      loading: false,
    }
  },
  created(){
  },
  methods:{
      logout(){
      let self = this
      self.loading = true
      self.$store.state.services.loginService
        .logout()
        .then(r => {
          self.$store.dispatch("logout")
          self.$router.push('/login')
          self.loading = false
        }).catch(e => {
      })
    }
  },
  computed: {
    getSchoolName(){
      let self = this
      return self.$store.state.school_name
    }
  }
}
</script>

<style>
.navbar-dark .navbar-nav .nav-link {
    color: #f8f9fa;
}
</style>
