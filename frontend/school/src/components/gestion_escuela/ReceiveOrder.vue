<template>
<div>

  <div class="content-wrapper" v-loading="loading">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Pedidos</h1> 
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header no-border">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">Lista de pedidos para recibir</h3>                
                </div>
              </div>
              <div class="card-body">
                <el-tabs tab-position="left" @tab-click="handleClick">
                  <el-tab-pane label="ALIMENTACION">
                    <h1>Pedidos de alimentación</h1>
                    &nbsp;
                    <template>
                        <div class="col-sm-12">
                          <b-row>
                          <b-col md="4" class="my-1 form-inline">
                            <label>mostrando: </label>
                                <b-form-select :options="pageOptions" v-model="perPage" />
                              <label>entradas </label>
                          </b-col>
                          <b-col  class="my-1 form-group pull-right">
                              <b-input-group>
                                <b-form-input v-model="filter" placeholder="buscar" />
                              </b-input-group>
                          </b-col>
                        </b-row>
                        <b-table responsive hover small flex
                          bordered
                          :fields="fields" 
                          :items="items"
                          :filter = "filter"
                          :current-page="currentPage"
                          :per-page="perPage"
                          @filtered="onFiltered">
                          <!-- A virtual column -->

                          <template v-slot:row-details="data">
                            <div class="row">
                              <div class="col-sm-3 text-center align-items-center">
                                <h3><b>Total del Pedido </b> {{ data.item.total | currency('Q',',',2,'.','front',true) }}</h3>  
                              </div>
                              <div class="col-sm-1 text-center align-items-center" style="font-size: 18px;">-</div>
                              <div class="col-sm-3 text-center align-items-center">
                                <h3><b>Total del Reembolso </b> {{ data.item.refund | currency('Q',',',2,'.','front',true) }}</h3>  
                              </div>
                              <div class="col-sm-1 text-center align-items-center" style="font-size: 18px;">=</div>
                              <div class="col-sm-3 text-center align-items-center">
                                <h3><b>Total Facturado </b> {{ data.item.total - data.item.refund | currency('Q',',',2,'.','front',true) }}</h3>  
                              </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-12"  v-loading="loading_check">
                                    <div class="table-responsive">
                                        <table class="table table-striped hover bordered">
                                            <thead style="background: #28a745;">
                                                <tr class="text-center" style="font-size: 16px;">
                                                    <th style="vertical-align:middle;" rowspan="2">#</th>
                                                    <th style="vertical-align:middle;" rowspan="2">Producto</th>                                                    
                                                    <th style="vertical-align:middle;" colspan="3">Pedido</th>                                                      
                                                    <th style="vertical-align:middle;" colspan="3">Entregado</th>  
                                                </tr>
                                                <tr class="text-center" style="font-size: 16px;">
                                                    <th>Cantidad</th>
                                                    <th>Precio</th>
                                                    <th>Sub</th>
                                                    <th>Cantidad</th>
                                                    <th>Precio</th>
                                                    <th>Sub</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody style="background: #17a2b8; font-size: 11px;">
                                                <template v-for="(row, index) in data.item.details">
                                                    <tr v-bind:key="index">
                                                        <td style="vertical-align:middle; text-align: center; font-weight: bold;">
                                                            {{ index+1 }}
                                                        </td>                                    
                                                        <td style="vertical-align:middle; text-align: left; font-weight: bold;">
                                                            <span>{{ row.product.name+' / '+row.product.presentation.name }}</span>    
                                                        </td> 
                                                        <td style="vertical-align:middle; text-align: center; font-weight: bold;">
                                                            {{ Number(row.progress.original_quantity) }}
                                                        </td> 
                                                        <td style="vertical-align:middle; text-align: right; font-weight: bold;">
                                                            {{ row.sale_price | currency('Q',',',2,'.','front',true) }}
                                                        </td>  
                                                        <td style="vertical-align:middle; text-align: right; font-weight: bold;">
                                                            {{ row.subtotal | currency('Q',',',2,'.','front',true) }}
                                                        </td> 
                                                        <td style="vertical-align:middle; text-align: center; font-weight: bold;">
                                                            {{ Number(row.progress.purchased_amount) }}
                                                        </td>  
                                                        <td style="vertical-align:middle; text-align: right; font-weight: bold;">
                                                            {{ row.sale_price | currency('Q',',',2,'.','front',true) }}
                                                        </td>  
                                                        <td style="vertical-align:middle; text-align: right; font-weight: bold;">
                                                            {{ row.subtotal - row.refund | currency('Q',',',2,'.','front',true) }}
                                                        </td> 
                                                        <td style="vertical-align:middle; text-align: center; font-weight: bold;">       
                                                            <button v-if="!row.aware && !data.item.aware" type="button" @click="check(row)" class="btn btn-success btn-sm" v-b-tooltip.left v-b-tooltip.hover title="check"><i class="fa fa-check"></i></button>
                                                            <button v-if="row.aware && !data.item.aware" type="button" @click="destroy(row,index+1)" class="btn btn-danger btn-sm" v-b-tooltip.left v-b-tooltip.hover title="eliminar"><i class="fa fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                            <tfoot style="background: #28a745;">
                                                <tr class="text-right" style="font-size: 16px;">
                                                    <th style="vertical-align:middle;" colspan="2">Totales</th> 
                                                    <th style="vertical-align:middle;" colspan="3">{{ data.item.total | currency('Q',',',2,'.','front',true) }}</th> 
                                                    <th style="vertical-align:middle;" colspan="3">{{ data.item.total - data.item.refund | currency('Q',',',2,'.','front',true) }}</th> 
                                                </tr>
                                            </tfoot>
                                        </table>   
                                    </div>                   
                                </div>
                            </div>
                          </template>

                          <template v-slot:cell(order)="data">
                            <div class="col-sm-12 text-center">
                              <b-button size="md" @click="data.toggleDetails" :class="data.item.aware == true ?  'mr-2 btn-success' : 'mr-2 btn-danger'">
                                {{ data.item.order }}
                              </b-button>
                            </div>
                          </template> 

                          <template v-slot:cell(date)="data">
                            <div class="text-center">{{ data.item.date | moment('dddd DD MMMM YYYY') }}</div>
                          </template>     
                          <template v-slot:cell(total)="data">
                            <div class="text-right" style="font-size: 18px;">{{ data.item.total - data.item.refund | currency('Q',',',2,'.','front',true) }}</div>
                          </template>              
                          <template v-slot:cell(anio)="data">
                            <div class="text-center">{{ data.item.date | moment('YYYY') }}</div> 
                          </template>    

                        </b-table>
                        <b-row>
                            <b-col md="12" class="my-1">
                              <label v-if="totalRows > 0">total: {{totalRows}} registros</label> 
                              <div class="text-center">
                                  <label v-if="totalRows === 0">No hay registros que mostrar</label> 
                              </div>   
                            </b-col>
                            <div class="pull-right col-md-12">
                              <div class="mt-3" v-if="totalRows > 0">
                                  <b-pagination 
                                    v-model="currentPage" 
                                    :per-page="perPage" 
                                    :total-rows="totalRows" 
                                    align="right"
                                    first-text="⏮"
                                    prev-text="⏪"
                                    next-text="⏩"
                                    last-text="⏭">
                                  </b-pagination>
                                </div>
                            </div>
                        </b-row>
                        </div>
                      </template>                    
                  </el-tab-pane>
                  <el-tab-pane label="GRATUIDAD">
                    <h1>Pedidos de gratuidad</h1>
                    &nbsp; 
                    <template>
                        <div class="col-sm-12">
                          <b-row>
                          <b-col md="4" class="my-1 form-inline">
                            <label>mostrando: </label>
                                <b-form-select :options="pageOptions" v-model="perPage" />
                              <label>entradas </label>
                          </b-col>
                          <b-col  class="my-1 form-group pull-right">
                              <b-input-group>
                                <b-form-input v-model="filter" placeholder="buscar" />
                              </b-input-group>
                          </b-col>
                        </b-row>
                        <b-table responsive hover small flex
                          bordered
                          :fields="fields" 
                          :items="items"
                          :filter = "filter"
                          :current-page="currentPage"
                          :per-page="perPage"
                          @filtered="onFiltered">
                          <!-- A virtual column -->

                          <template v-slot:row-details="data">
                            <div class="row">
                              <div class="col-sm-3 text-center align-items-center">
                                <h3><b>Total del Pedido </b> {{ data.item.total | currency('Q',',',2,'.','front',true) }}</h3>  
                              </div>
                              <div class="col-sm-1 text-center align-items-center" style="font-size: 18px;">-</div>
                              <div class="col-sm-3 text-center align-items-center">
                                <h3><b>Total del Reembolso </b> {{ data.item.refund | currency('Q',',',2,'.','front',true) }}</h3>  
                              </div>
                              <div class="col-sm-1 text-center align-items-center" style="font-size: 18px;">=</div>
                              <div class="col-sm-3 text-center align-items-center">
                                <h3><b>Total Facturado </b> {{ data.item.total - data.item.refund | currency('Q',',',2,'.','front',true) }}</h3>  
                              </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-12"  v-loading="loading_check">
                                    <div class="table-responsive">
                                        <table class="table table-striped hover bordered">
                                            <thead style="background: #28a745;">
                                                <tr class="text-center" style="font-size: 16px;">
                                                    <th style="vertical-align:middle;" rowspan="2">#</th>
                                                    <th style="vertical-align:middle;" rowspan="2">Producto</th>                                                    
                                                    <th style="vertical-align:middle;" colspan="3">Pedido</th>                                                      
                                                    <th style="vertical-align:middle;" colspan="3">Entregado</th>  
                                                </tr>
                                                <tr class="text-center" style="font-size: 16px;">
                                                    <th>Cantidad</th>
                                                    <th>Precio</th>
                                                    <th>Sub</th>
                                                    <th>Cantidad</th>
                                                    <th>Precio</th>
                                                    <th>Sub</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody style="background: #17a2b8; font-size: 11px;">
                                                <template v-for="(row, index) in data.item.details">
                                                    <tr v-bind:key="index">
                                                        <td style="vertical-align:middle; text-align: center; font-weight: bold;">
                                                            {{ index+1 }}
                                                        </td>                                    
                                                        <td style="vertical-align:middle; text-align: left; font-weight: bold;">
                                                            <span>{{ row.product.name+' / '+row.product.presentation.name }}</span>    
                                                        </td> 
                                                        <td style="vertical-align:middle; text-align: center; font-weight: bold;">
                                                            {{ Number(row.progress.original_quantity) }}
                                                        </td> 
                                                        <td style="vertical-align:middle; text-align: right; font-weight: bold;">
                                                            {{ row.sale_price | currency('Q',',',2,'.','front',true) }}
                                                        </td>  
                                                        <td style="vertical-align:middle; text-align: right; font-weight: bold;">
                                                            {{ row.subtotal | currency('Q',',',2,'.','front',true) }}
                                                        </td> 
                                                        <td style="vertical-align:middle; text-align: center; font-weight: bold;">
                                                            {{ Number(row.progress.purchased_amount) }}
                                                        </td>  
                                                        <td style="vertical-align:middle; text-align: right; font-weight: bold;">
                                                            {{ row.sale_price | currency('Q',',',2,'.','front',true) }}
                                                        </td>  
                                                        <td style="vertical-align:middle; text-align: right; font-weight: bold;">
                                                            {{ row.subtotal - row.refund | currency('Q',',',2,'.','front',true) }}
                                                        </td> 
                                                        <td style="vertical-align:middle; text-align: center; font-weight: bold;">       
                                                            <button v-if="!row.aware && !data.item.aware" type="button" @click="check(row)" class="btn btn-success btn-sm" v-b-tooltip.left v-b-tooltip.hover title="check"><i class="fa fa-check"></i></button>
                                                            <button v-if="row.aware && !data.item.aware" type="button" @click="destroy(row,index+1)" class="btn btn-danger btn-sm" v-b-tooltip.left v-b-tooltip.hover title="eliminar"><i class="fa fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                            <tfoot style="background: #28a745;">
                                                <tr class="text-right" style="font-size: 16px;">
                                                    <th style="vertical-align:middle;" colspan="2">Totales</th> 
                                                    <th style="vertical-align:middle;" colspan="3">{{ data.item.total | currency('Q',',',2,'.','front',true) }}</th> 
                                                    <th style="vertical-align:middle;" colspan="3">{{ data.item.total - data.item.refund | currency('Q',',',2,'.','front',true) }}</th> 
                                                </tr>
                                            </tfoot>
                                        </table>   
                                    </div>                   
                                </div>
                            </div>
                          </template>

                          <template v-slot:cell(order)="data">
                            <div class="col-sm-12 text-center">
                              <b-button size="md" @click="data.toggleDetails" :class="data.item.aware == true ?  'mr-2 btn-success' : 'mr-2 btn-danger'">
                                {{ data.item.order }}
                              </b-button>
                            </div>
                          </template> 

                          <template v-slot:cell(date)="data">
                            <div class="text-center">{{ data.item.date | moment('dddd DD MMMM YYYY') }}</div>
                          </template>     
                          <template v-slot:cell(total)="data">
                            <div class="text-right" style="font-size: 18px;">{{ data.item.total - data.item.refund | currency('Q',',',2,'.','front',true) }}</div>
                          </template>              
                          <template v-slot:cell(anio)="data">
                            <div class="text-center">{{ data.item.date | moment('YYYY') }}</div> 
                          </template>    

                        </b-table>
                        <b-row>
                            <b-col md="12" class="my-1">
                              <label v-if="totalRows > 0">total: {{totalRows}} registros</label> 
                              <div class="text-center">
                                  <label v-if="totalRows === 0">No hay registros que mostrar</label> 
                              </div>   
                            </b-col>
                            <div class="pull-right col-md-12">
                              <div class="mt-3" v-if="totalRows > 0">
                                  <b-pagination 
                                    v-model="currentPage" 
                                    :per-page="perPage" 
                                    :total-rows="totalRows" 
                                    align="right"
                                    first-text="⏮"
                                    prev-text="⏪"
                                    next-text="⏩"
                                    last-text="⏭">
                                  </b-pagination>
                                </div>
                            </div>
                        </b-row>
                        </div>
                      </template>                      
                  </el-tab-pane>
                  <el-tab-pane label="UTILES">
                    <h1>Pedidos de utiles</h1>
                    &nbsp;  
                    <template>
                        <div class="col-sm-12">
                          <b-row>
                          <b-col md="4" class="my-1 form-inline">
                            <label>mostrando: </label>
                                <b-form-select :options="pageOptions" v-model="perPage" />
                              <label>entradas </label>
                          </b-col>
                          <b-col  class="my-1 form-group pull-right">
                              <b-input-group>
                                <b-form-input v-model="filter" placeholder="buscar" />
                              </b-input-group>
                          </b-col>
                        </b-row>
                        <b-table responsive hover small flex
                          bordered
                          :fields="fields" 
                          :items="items"
                          :filter = "filter"
                          :current-page="currentPage"
                          :per-page="perPage"
                          @filtered="onFiltered">
                          <!-- A virtual column -->

                          <template v-slot:row-details="data">
                            <div class="row">
                              <div class="col-sm-3 text-center align-items-center">
                                <h3><b>Total del Pedido </b> {{ data.item.total | currency('Q',',',2,'.','front',true) }}</h3>  
                              </div>
                              <div class="col-sm-1 text-center align-items-center" style="font-size: 18px;">-</div>
                              <div class="col-sm-3 text-center align-items-center">
                                <h3><b>Total del Reembolso </b> {{ data.item.refund | currency('Q',',',2,'.','front',true) }}</h3>  
                              </div>
                              <div class="col-sm-1 text-center align-items-center" style="font-size: 18px;">=</div>
                              <div class="col-sm-3 text-center align-items-center">
                                <h3><b>Total Facturado </b> {{ data.item.total - data.item.refund | currency('Q',',',2,'.','front',true) }}</h3>  
                              </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-12"  v-loading="loading_check">
                                    <div class="table-responsive">
                                        <table class="table table-striped hover bordered">
                                            <thead style="background: #28a745;">
                                                <tr class="text-center" style="font-size: 16px;">
                                                    <th style="vertical-align:middle;" rowspan="2">#</th>
                                                    <th style="vertical-align:middle;" rowspan="2">Producto</th>                                                    
                                                    <th style="vertical-align:middle;" colspan="3">Pedido</th>                                                      
                                                    <th style="vertical-align:middle;" colspan="3">Entregado</th>  
                                                </tr>
                                                <tr class="text-center" style="font-size: 16px;">
                                                    <th>Cantidad</th>
                                                    <th>Precio</th>
                                                    <th>Sub</th>
                                                    <th>Cantidad</th>
                                                    <th>Precio</th>
                                                    <th>Sub</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody style="background: #17a2b8; font-size: 11px;">
                                                <template v-for="(row, index) in data.item.details">
                                                    <tr v-bind:key="index">
                                                        <td style="vertical-align:middle; text-align: center; font-weight: bold;">
                                                            {{ index+1 }}
                                                        </td>                                    
                                                        <td style="vertical-align:middle; text-align: left; font-weight: bold;">
                                                            <span>{{ row.product.name+' / '+row.product.presentation.name }}</span>    
                                                        </td> 
                                                        <td style="vertical-align:middle; text-align: center; font-weight: bold;">
                                                            {{ Number(row.progress.original_quantity) }}
                                                        </td> 
                                                        <td style="vertical-align:middle; text-align: right; font-weight: bold;">
                                                            {{ row.sale_price | currency('Q',',',2,'.','front',true) }}
                                                        </td>  
                                                        <td style="vertical-align:middle; text-align: right; font-weight: bold;">
                                                            {{ row.subtotal | currency('Q',',',2,'.','front',true) }}
                                                        </td> 
                                                        <td style="vertical-align:middle; text-align: center; font-weight: bold;">
                                                            {{ Number(row.progress.purchased_amount) }}
                                                        </td>  
                                                        <td style="vertical-align:middle; text-align: right; font-weight: bold;">
                                                            {{ row.sale_price | currency('Q',',',2,'.','front',true) }}
                                                        </td>  
                                                        <td style="vertical-align:middle; text-align: right; font-weight: bold;">
                                                            {{ row.subtotal - row.refund | currency('Q',',',2,'.','front',true) }}
                                                        </td> 
                                                        <td style="vertical-align:middle; text-align: center; font-weight: bold;">       
                                                            <button v-if="!row.aware && !data.item.aware" type="button" @click="check(row)" class="btn btn-success btn-sm" v-b-tooltip.left v-b-tooltip.hover title="check"><i class="fa fa-check"></i></button>
                                                            <button v-if="row.aware && !data.item.aware" type="button" @click="destroy(row,index+1)" class="btn btn-danger btn-sm" v-b-tooltip.left v-b-tooltip.hover title="eliminar"><i class="fa fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                            <tfoot style="background: #28a745;">
                                                <tr class="text-right" style="font-size: 16px;">
                                                    <th style="vertical-align:middle;" colspan="2">Totales</th> 
                                                    <th style="vertical-align:middle;" colspan="3">{{ data.item.total | currency('Q',',',2,'.','front',true) }}</th> 
                                                    <th style="vertical-align:middle;" colspan="3">{{ data.item.total - data.item.refund | currency('Q',',',2,'.','front',true) }}</th> 
                                                </tr>
                                            </tfoot>
                                        </table>   
                                    </div>                   
                                </div>
                            </div>
                          </template>

                          <template v-slot:cell(order)="data">
                            <div class="col-sm-12 text-center">
                              <b-button size="md" @click="data.toggleDetails" :class="data.item.aware == true ?  'mr-2 btn-success' : 'mr-2 btn-danger'">
                                {{ data.item.order }}
                              </b-button>
                            </div>
                          </template> 

                          <template v-slot:cell(date)="data">
                            <div class="text-center">{{ data.item.date | moment('dddd DD MMMM YYYY') }}</div>
                          </template>     
                          <template v-slot:cell(total)="data">
                            <div class="text-right" style="font-size: 18px;">{{ data.item.total - data.item.refund | currency('Q',',',2,'.','front',true) }}</div>
                          </template>              
                          <template v-slot:cell(anio)="data">
                            <div class="text-center">{{ data.item.date | moment('YYYY') }}</div> 
                          </template>    

                        </b-table>
                        <b-row>
                            <b-col md="12" class="my-1">
                              <label v-if="totalRows > 0">total: {{totalRows}} registros</label> 
                              <div class="text-center">
                                  <label v-if="totalRows === 0">No hay registros que mostrar</label> 
                              </div>   
                            </b-col>
                            <div class="pull-right col-md-12">
                              <div class="mt-3" v-if="totalRows > 0">
                                  <b-pagination 
                                    v-model="currentPage" 
                                    :per-page="perPage" 
                                    :total-rows="totalRows" 
                                    align="right"
                                    first-text="⏮"
                                    prev-text="⏪"
                                    next-text="⏩"
                                    last-text="⏭">
                                  </b-pagination>
                                </div>
                            </div>
                        </b-row>
                        </div>
                      </template>                                   
                  </el-tab-pane>
                  <el-tab-pane label="VALIJAS DIDACTICA">
                    <h1>Pedidos de valijas didactica</h1>
                    &nbsp;  
                    <template>
                        <div class="col-sm-12">
                          <b-row>
                          <b-col md="4" class="my-1 form-inline">
                            <label>mostrando: </label>
                                <b-form-select :options="pageOptions" v-model="perPage" />
                              <label>entradas </label>
                          </b-col>
                          <b-col  class="my-1 form-group pull-right">
                              <b-input-group>
                                <b-form-input v-model="filter" placeholder="buscar" />
                              </b-input-group>
                          </b-col>
                        </b-row>
                        <b-table responsive hover small flex
                          bordered
                          :fields="fields" 
                          :items="items"
                          :filter = "filter"
                          :current-page="currentPage"
                          :per-page="perPage"
                          @filtered="onFiltered">
                          <!-- A virtual column -->

                          <template v-slot:row-details="data">
                            <div class="row">
                              <div class="col-sm-3 text-center align-items-center">
                                <h3><b>Total del Pedido </b> {{ data.item.total | currency('Q',',',2,'.','front',true) }}</h3>  
                              </div>
                              <div class="col-sm-1 text-center align-items-center" style="font-size: 18px;">-</div>
                              <div class="col-sm-3 text-center align-items-center">
                                <h3><b>Total del Reembolso </b> {{ data.item.refund | currency('Q',',',2,'.','front',true) }}</h3>  
                              </div>
                              <div class="col-sm-1 text-center align-items-center" style="font-size: 18px;">=</div>
                              <div class="col-sm-3 text-center align-items-center">
                                <h3><b>Total Facturado </b> {{ data.item.total - data.item.refund | currency('Q',',',2,'.','front',true) }}</h3>  
                              </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-12"  v-loading="loading_check">
                                    <div class="table-responsive">
                                        <table class="table table-striped hover bordered">
                                            <thead style="background: #28a745;">
                                                <tr class="text-center" style="font-size: 16px;">
                                                    <th style="vertical-align:middle;" rowspan="2">#</th>
                                                    <th style="vertical-align:middle;" rowspan="2">Producto</th>                                                    
                                                    <th style="vertical-align:middle;" colspan="3">Pedido</th>                                                      
                                                    <th style="vertical-align:middle;" colspan="3">Entregado</th>  
                                                </tr>
                                                <tr class="text-center" style="font-size: 16px;">
                                                    <th>Cantidad</th>
                                                    <th>Precio</th>
                                                    <th>Sub</th>
                                                    <th>Cantidad</th>
                                                    <th>Precio</th>
                                                    <th>Sub</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody style="background: #17a2b8; font-size: 11px;">
                                                <template v-for="(row, index) in data.item.details">
                                                    <tr v-bind:key="index">
                                                        <td style="vertical-align:middle; text-align: center; font-weight: bold;">
                                                            {{ index+1 }}
                                                        </td>                                    
                                                        <td style="vertical-align:middle; text-align: left; font-weight: bold;">
                                                            <span>{{ row.product.name+' / '+row.product.presentation.name }}</span>    
                                                        </td> 
                                                        <td style="vertical-align:middle; text-align: center; font-weight: bold;">
                                                            {{ Number(row.progress.original_quantity) }}
                                                        </td> 
                                                        <td style="vertical-align:middle; text-align: right; font-weight: bold;">
                                                            {{ row.sale_price | currency('Q',',',2,'.','front',true) }}
                                                        </td>  
                                                        <td style="vertical-align:middle; text-align: right; font-weight: bold;">
                                                            {{ row.subtotal | currency('Q',',',2,'.','front',true) }}
                                                        </td> 
                                                        <td style="vertical-align:middle; text-align: center; font-weight: bold;">
                                                            {{ Number(row.progress.purchased_amount) }}
                                                        </td>  
                                                        <td style="vertical-align:middle; text-align: right; font-weight: bold;">
                                                            {{ row.sale_price | currency('Q',',',2,'.','front',true) }}
                                                        </td>  
                                                        <td style="vertical-align:middle; text-align: right; font-weight: bold;">
                                                            {{ row.subtotal - row.refund | currency('Q',',',2,'.','front',true) }}
                                                        </td> 
                                                        <td style="vertical-align:middle; text-align: center; font-weight: bold;">       
                                                            <button v-if="!row.aware && !data.item.aware" type="button" @click="check(row)" class="btn btn-success btn-sm" v-b-tooltip.left v-b-tooltip.hover title="check"><i class="fa fa-check"></i></button>
                                                            <button v-if="row.aware && !data.item.aware" type="button" @click="destroy(row,index+1)" class="btn btn-danger btn-sm" v-b-tooltip.left v-b-tooltip.hover title="eliminar"><i class="fa fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                            <tfoot style="background: #28a745;">
                                                <tr class="text-right" style="font-size: 16px;">
                                                    <th style="vertical-align:middle;" colspan="2">Totales</th> 
                                                    <th style="vertical-align:middle;" colspan="3">{{ data.item.total | currency('Q',',',2,'.','front',true) }}</th> 
                                                    <th style="vertical-align:middle;" colspan="3">{{ data.item.total - data.item.refund | currency('Q',',',2,'.','front',true) }}</th> 
                                                </tr>
                                            </tfoot>
                                        </table>   
                                    </div>                   
                                </div>
                            </div>
                          </template>

                          <template v-slot:cell(order)="data">
                            <div class="col-sm-12 text-center">
                              <b-button size="md" @click="data.toggleDetails" :class="data.item.aware == true ?  'mr-2 btn-success' : 'mr-2 btn-danger'">
                                {{ data.item.order }}
                              </b-button>
                            </div>
                          </template> 

                          <template v-slot:cell(date)="data">
                            <div class="text-center">{{ data.item.date | moment('dddd DD MMMM YYYY') }}</div>
                          </template>     
                          <template v-slot:cell(total)="data">
                            <div class="text-right" style="font-size: 18px;">{{ data.item.total - data.item.refund | currency('Q',',',2,'.','front',true) }}</div>
                          </template>              
                          <template v-slot:cell(anio)="data">
                            <div class="text-center">{{ data.item.date | moment('YYYY') }}</div> 
                          </template>    

                        </b-table>
                        <b-row>
                            <b-col md="12" class="my-1">
                              <label v-if="totalRows > 0">total: {{totalRows}} registros</label> 
                              <div class="text-center">
                                  <label v-if="totalRows === 0">No hay registros que mostrar</label> 
                              </div>   
                            </b-col>
                            <div class="pull-right col-md-12">
                              <div class="mt-3" v-if="totalRows > 0">
                                  <b-pagination 
                                    v-model="currentPage" 
                                    :per-page="perPage" 
                                    :total-rows="totalRows" 
                                    align="right"
                                    first-text="⏮"
                                    prev-text="⏪"
                                    next-text="⏩"
                                    last-text="⏭">
                                  </b-pagination>
                                </div>
                            </div>
                        </b-row>
                        </div>
                      </template>                                   
                  </el-tab-pane>
                </el-tabs>
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
import moment from 'moment'
import FormError from '../shared/FormError'
export default {
  name: "receiveorder",
  components: {
      FormError,
      moment
  },
  data() {
    return {
      loading: false,
      loading_check: false,
      modalidad: '',
      items: [],
      fields: [
        { key: 'order', label: 'Pedido #', sortable: true },
        { key: 'code', label: 'Código', sortable: true },
        { key: 'title', label: 'Título', sortable: true },
        { key: 'date', label: 'Fecha de entrega', sortable: true },
        { key: 'total', label: 'Total Facturado', sortable: true },
        { key: 'type_order', label: 'Tipo de Pedido', sortable: true },
        { key: 'anio', label: 'Año', sortable: true },
      ],
      filter: null,
      currentPage: 1,
      perPage: 5,
      totalRows: 0,
      pageOptions: [ 5, 10, 25 ],
      showStringEmpty: 'no hay registros que mostrar',    
    };
  },
  created() {
    let self = this;
    self.getAll('ALIMENTACION');
  },

  methods: {
    //Clasificar error
    interceptar_error(r){
      let self = this
      let error = 1;

        if(r.response){
            if(r.response.status === 422){
                this.$toastr.info(r.response.data.error, 'Mensaje')
                error = 0
            }

            if(r.response.status != 201 && r.response.status != 422){
                for (let value of Object.values(r.response.data)) {
                    self.$toastr.error(value, 'Mensaje')
                }
                error = 0
            }
        }
      
      return error
    },

    onFiltered (filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length
      this.currentPage = 1
    },

    handleClick(tab, event) {
      let self = this
      switch (tab.index) {
        case '0':
          self.getAll('ALIMENTACION')
          self.modalidad = 'ALIMENTACION'
          break;
      
        case '1':
          self.getAll('GRATUIDAD')
          self.modalidad = 'GRATUIDAD'
          break;

        case '2':
          self.getAll('UTILES')
          self.modalidad = 'UTILES'
          break;  

        case '3':
          self.getAll('VALIJA DIDACTICA')
          self.modalidad = 'VALIJA DIDACTICA'
          break;         
      }      
    },

    getAll(type_order) {
      let self = this;
      self.loading = true;
      self.items = []

      self.$store.state.services.checkschoolService
        .get(type_order)
        .then(r => {
          self.loading = false; 
          self.items = r.data.data;
          self.totalRows = self.items.length
        })
        .catch(r => {});
    },

    check(item){
        let self = this

        self.$validator.validateAll().then((result) => {
            if (result) {

                self.$swal({
                    title: "ADVERTENCIA",
                    text: "¿ESTA SEGURO QUE QUIERE RECIBIR EL PRODUCTO "+item.product.name+' / '+item.product.presentation.name+ '?',
                    type: "warning",
                    showCancelButton: true
                }).then((result) => { 
                    if (result.value) { 
                        self.loading_check = true
                        let data = {}
                        data.progress_orders_id = item.progress.id
                        
                        self.$store.state.services.checkschoolService
                            .create(data)
                            .then(r => {
                                self.loading_check = false
                                if( self.interceptar_error(r) == 0) return 
                                if(r.data.ready)
                                {
                                    self.$router.push('/school/management/receive/order') 
                                }
                                else
                                {
                                    item.aware = true
                                    self.$toastr.success('producto recibido', 'conformidad')  
                                }                                      
                            })
                            .catch(r => {}); 
                    }
                });
            }
        });       
    },

    destroy(item,index){
      let self = this

      self.$swal({
        title: "ADVERTENCIA",
        text: "¿ESTA SEGURO QUE DESEA ELIMINAR EL CHECK EN LA POSICION #"+index+"?",
        type: "warning",
        showCancelButton: true
      }).then((result) => { 
          if (result.value) { 
              self.loading_check = true
              let data = {}
              data.id = item.progress.id
              self.$store.state.services.checkschoolService
                .destroy(data)
                .then(r => {
                  self.loading_check = false
                  if( self.interceptar_error(r) == 0) return
                  item.aware = false
                  self.$toastr.success('registro eliminado con exito', 'exito')
                })
                .catch(r => {});
          }
      });
    },    
  },
  mounted(){
    $("body").resize()
  },
};
</script>