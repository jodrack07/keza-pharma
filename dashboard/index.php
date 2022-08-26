<?php 
require_once 'shared/header.php';
require_once '../utils/functions.php';

  start_session();
    
  page_access_restriction('../index.php'); 

  $response = fetch_stock_data();
  
  // make a product out of stock once it's less than 5.
  $avaialable_drugs = countData('t_stock', 'quantity', '>', 5);

?>
    <!-- Page Wrapper -->
    <div id="wrapper">
      
      <?php  require_once 'shared/side-bar.php'; ?>

      <!-- Content Wrapper -->
      <div id="content-wrapper" class="d-flex flex-column">
        <!-- Main Content -->
        <div id="content">
          
          <?php require_once 'shared/top-bar.php'; ?>

          <!-- Begin Page Content -->
          <div class="container-fluid">
            <!-- Page Heading -->
            <div
              class="d-sm-flex align-items-center justify-content-between mb-4"
            >
              <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            </div>

            <!-- Content Row -->
            <div class="row">
              <!-- Earnings (Monthly) Card Example -->
              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div
                          class="text-xs font-weight-bold text-primary text-uppercase mb-1"
                        >
                          STOCK
                        </div>
                        <div class="h6 mb-0 font-weight-bold text-gray-800">
                          <?= dashboard_count('t_stock') ?> { <span class="text-success drug_report"><?= $avaialable_drugs ?> available</span> - <span class="drug_report" style="color: red; opacity: 0.5"><?= dashboard_count('t_stock') - $avaialable_drugs ?> out of stock</span> }
                        </div>
                      </div>
                      <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Earnings (Monthly) Card Example -->
              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div
                          class="text-xs font-weight-bold text-success text-uppercase mb-1"
                        >
                          SALES
                        </div>
                        <div class="h6 mb-0 font-weight-bold text-gray-800">
                          <?= dashboard_count('t_sales') ?>
                        </div>
                      </div>
                      <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Earnings (Monthly) Card Example -->
              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div
                          class="text-xs font-weight-bold text-info text-uppercase mb-1"
                        >
                          USERS
                        </div>
                        <div class="row no-gutters align-items-center">
                          <div class="col-auto">
                            <div
                              class="h6 mb-0 mr-3 font-weight-bold text-gray-800"
                            >
                            <?php 
                              if($_SESSION['user_type'] == 'admin') {
                                echo dashboard_count('t_staff');
                              }
                              else {
                                echo '####';
                              }
                            ?>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-auto">
                        <i
                          class="fas fa-user fa-2x text-gray-300"
                        ></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Pending Requests Card Example -->
              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div
                          class="text-xs font-weight-bold text-danger text-uppercase mb-1"
                        >
                          EXPIRED PRODUCTS
                        </div>
                        <div class="h6 mb-0 font-weight-bold text-gray-800">
                          <?= count_data_by_field('t_stock', 'expiry_date', '<=' ,date('Y-m-d')) ?>
                        </div>
                      </div>
                      <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-danger"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- DataTales Example -->
            <div class="card shadow mb-4">
              <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                  Available drugs in the stock
                </h6>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table
                    class="table table-bordered"
                    id="dataTable"
                    width="100%"
                    cellspacing="0"
                  >
                    <thead>
                      <th>#</th>
                        <th>Drug Name</th>
                        <th>Manuf.</th>
                        <th>Batch No</th>
                        <th>Prod Name</th>
                        <th>Exp. Date</th>
                        <th>Reg. Date</th>
                        <th>Qty</th>
                        <th>Cost price</th>
                        <th>Reg. By</th>
                      </tr>
                    </thead>
                    <tfoot>
                      <tr>
                        <th>#</th>
                        <th>Drug Name</th>
                        <th>Manuf.</th>
                        <th>Batch No</th>
                        <th>Prod Name</th>
                        <th>Exp. Date</th>
                        <th>Reg. Date</th>
                        <th>Qty</th>
                        <th>Cost price</th>
                        <th>Reg. By</th>
                      </tr>
                    </tfoot>
                    <tbody>

                      <?php 
                        $today_date = date('Y-m-d'); 
                        $indication_styles = '';  
                      ?>

                      <?php foreach($response as $data) : ?> 
                      
                        <?php 
                          $remaing_days = get_remaining_days_between_2_dates($today_date,$data->expiry_date);
                        if(($data->quantity) < 5 && $remaing_days < 1) {
                          $indication_styles = 'style="background: red; opacity: 1; color: #fff; font-weight: 600"';
                        }
                        elseif(($data->quantity) < 5) {
                            $indication_styles = 'style="background: orange; opacity: 0.5; color: #fff; font-weight: 600"';
                        }
                        elseif($remaing_days < 1) {
                          $indication_styles = 'style="background: red; opacity: 0.7; color: #fff; font-weight: 600"';
                        }
                        ?>


                        <tr <?= $indication_styles ?>>
                        <td><?= $data->id ?></td>
                        <td><?= $data->drug_name ?></td>
                        <td><?= $data->manufacturer ?></td>
                        <td><?= $data->batch_no ?></td>
                        <td><?= $data->production_date ?></td>
                        <td><?= $data->expiry_date ?></td>
                        <td><?= $data->registered_date ?></td>
                        <td><?= $data->quantity ?></td>
                        <td>$ <?= $data->cost_price ?></td>
                        <td><?= $data->staff ?></td>
                    </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

          </div>
          <!-- /.container-fluid -->
        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
          <div class="container my-auto">
            <div class="copyright text-center my-auto">
              <span>Copyright &copy; la gloire pharma 2022</span>
            </div>
          </div>
        </footer>
        <!-- End of Footer -->
      </div>
      <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>

    <?php require_once 'shared/log-out-modal.php'; ?>

    <?php require_once 'shared/scripts.php' ?>

  </body>
</html>
