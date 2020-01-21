<template>
<!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
        <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">INVENTARIO</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid" style="height:100px;">
        <div class="row" >
            <div class="col-md-12">
            <!-- Application buttons -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Lista de productos</h3>
              </div>
              <div class="card-body">
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                  <label class="btn bg-olive active" @click="changeComponent">
                    <input type="radio"  name="options" id="option1" autocomplete="off"><i class="fa fa-barcode"></i> Productos faltantes
                  </label>
                  <label class="btn bg-olive" @click="changeComponent(false)">
                    <input type="radio" name="options" id="option2" autocomplete="off"><i class="fa fa-barcode"></i>  Productos por vencer
                  </label>
                </div>
                
                  <el-divider></el-divider>
                <div v-if="missing">
                    <h3>Productos faltantes</h3>
                  <el-divider></el-divider>
                  <MissingProduct></MissingProduct>
                </div>
                <div v-else>
                    <h3>Productos vencidos y por proximos a vencer</h3>
                  <el-divider></el-divider>
                  <low-products></low-products>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!--Fin-Contenido-->
</template>

<script>
import moment from 'moment'
import MissingProduct from './MissingProduct'
import LowProducts from './LowProductStock'
export default {
  name: 'Default',
  components:{
    MissingProduct,
    LowProducts
  },
  data() {
      return {
        missing: true
      }
  },

  created() {
    let self = this
  },

  methods: {
    changeComponent(option = true){
      let self = this
      self.missing = option
    }
  },
   computed: {
    },
}
</script>
