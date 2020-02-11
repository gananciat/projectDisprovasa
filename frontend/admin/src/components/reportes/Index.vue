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
            <h1 class="m-0 text-dark">REPORTERÍA</h1>
          </div><!-- /.col -->
         <!-- <div class="col-sm-5">
                <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="javascript:void(0);">PEDIDOS Y FACTURACIÓN</a></li>
                <li class="breadcrumb-item active">NUEVA FACTURA</li>
                </ol>
            </div>-->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row" >
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header no-border">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">REPORTES ADMINISTRATIVOS</h3>
                  <a href="javascript:void(0);">Reportes</a>
                </div>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-green">
                      <div class="inner">
                        <h3>PRODUCTOS</h3>

                        <p>Pedidos por productos</p>
                      </div>
                      <div class="icon">
                        <i class="fa fa-barcode"></i>
                      </div>
                      <a href="#/products_ordered" class="small-box-footer">ir a <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                  </div>
                  <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-yellow">
                      <div class="inner">
                        <h3>PRECIO PRODUCTOS</h3>

                        <p>Precio por proveedores</p>
                      </div>
                      <div class="icon">
                        <i class="fa fa-money"></i>
                      </div>
                      <a href="#/products_ordered" class="small-box-footer">ir a <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                  </div>
                  <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                      <div class="inner">
                        <h3>COMPRAS Y VENTAS</h3>

                        <p>Comparación compras y ventas</p>
                      </div>
                      <div class="icon">
                        <i class="fa fa-file"></i>
                      </div>
                      <a href="#/products_ordered" class="small-box-footer">ir a <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                  </div>
                  <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-red">
                      <div class="inner">
                        <h3>DESEMBOLSOS</h3>

                        <p>Desembolsos</p>
                      </div>
                      <div class="icon">
                        <i class="fa fa-bank"></i>
                      </div>
                      <a href="#/products_ordered" class="small-box-footer">ir a <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.card -->
            <!-- /.card -->
          </div>
          <!-- /.col-md-6 -->
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
export default {
  name: 'Reporteria',
  data() {
      return {

      }
  },

  created() {
  },

  methods: {
    
  },
   computed: {
    },
}
</script>
