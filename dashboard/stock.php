<?php 
  require_once 'shared/header.php';
  require_once '../utils/functions.php';

  start_session();

  page_access_restriction('../index.php'); 

  $response = fetch_stock_data();
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
            <div class="d-flex justify-content-between align-items-center mb-4">
              <p><a href="index.php">dashboard</a>/<span class="navigation">Stock</span></p>
              <div>
                <a href="populate-stock.php" onclick="return confirm('Do you want to add a new item?')" class="btn btn-success">Add Item</a>
                <a href="../utils/print-stock.php" onclick="return confirm('Do you want to print the list of all items?')" class="btn btn-primary">Print PDF</a>
              </div>
            </div>
            <!-- Content Row -->

            <?php if(isset($_SESSION['flash-stock'])) : ?>
            <!-- looping over all the flash messages found -->
            <?php foreach ($_SESSION['flash-stock'] as $type => $message) : ?>
                <div class="alert alert-<?php echo $type;?> alert-dismissible" role="alert">
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                      <div class="alert-message">
                        <h4><?php echo $message; ?></h4>
                     </div>
                </div>
            <?php endforeach; ?>
            <!-- unsetting the super global $_SESSION['flash'] to erase the flash message above -->
            <?php unset($_SESSION['flash-stock']); ?>
        <?php endif; ?>

            <!-- DataTales Example -->
            <div class="card shadow mb-4">
              <div class="card-header py-3">
                <div class="d-flex justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">
                    Available drugs in the stock
                  </h6>

                </div>
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
                      <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Manuf.</th>
                        <th>Bt. No</th>
                        <th>Prod Date</th>
                        <th>Exp Date</th>
                        <th>Reg Date</th>
                        <th>Qty</th>
                        <th>UP</th>
                        <th>Cst price</th>
                        <th>Reg By</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tfoot>
                      <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Manuf.</th>
                        <th>Bt. No</th>
                        <th>Prod Name</th>
                        <th>Exp. Date</th>
                        <th>Reg. Date</th>
                        <th>Qty</th>
                        <th>UP</th>
                        <th>Cost Price</th>
                        <th>Reg. By</th>
                        <th>Actions</th>
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
                        <td>$ <?= $data->unity_price ?></td>
                        <td>$ <?= $data->cost_price ?></td>
                        <td><?= $data->staff ?></td>
                        <td style="background-color: #fff;">
                          <a href="edit-item.php?edt_stock_id=<?= $data->id ?>"><i class="fa fa-edit text-success" onclick="return confirm('Do you want to edit this item?')"></i></a>
                          <a href="../utils/delete.php?del_stock_id=<?= $data->id ?>"><i class="fa fa-trash text-danger" onclick="return confirm('Do you want to delete this item?')"></i></a>
                        </td>
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
            <?php require_once 'copy_right.php' ?>
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

    <?php require_once 'shared/scripts.php'; ?>

  </body>
</html>
