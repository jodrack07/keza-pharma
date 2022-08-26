<?php 
  require_once 'shared/header.php';
  require_once '../utils/functions.php';

  start_session();

  page_access_restriction('../index.php'); 

  $response = fetch_sales_data();
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
              <p><a href="index.php">dashboard</a>/<span class="navigation">Sales</span></p>
              <div>
                  <a href="populate-sale.php" onclick="return confirm('Do you want to add a new sale?')" class="btn btn-success">Add Sale</a>
                  <a href="../utils/print-sales.php" onclick="return confirm('Do you want to print the list of all items?')" class="btn btn-primary">Print PDF</a>
              </div>
            </div>
            <!-- Content Row -->

            <?php if(isset($_SESSION['flash-sales'])) : ?>
            <!-- looping over all the flash messages found -->
            <?php foreach ($_SESSION['flash-sales'] as $type => $message) : ?>
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
            <?php unset($_SESSION['flash-sales']); ?>
        <?php endif; ?>

            <!-- DataTales Example -->
            <div class="card shadow mb-4">
              <div class="card-header py-3">
                <div class="d-flex justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">
                    Available sales in the system
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
                        <th>Cust. Name</th>
                        <th>Cust. Phone</th>
                        <th>Product</th>
                        <th>Unity Price</th>
                        <th>Quantity</th>
                        <th>Sale Amount</th>
                        <th>Dosage</th>
                        <th>Done At</th>
                        <th>Done By</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tfoot>
                      <tr>
                        <th>#</th>
                        <th>Cust. Name</th>
                        <th>Cust. Phone</th>
                        <th>Product</th>
                        <th>Unity Price</th>
                        <th>Quantity</th>
                        <th>Sale Amount</th>
                        <th>Dosage</th>
                        <th>Done At</th>
                        <th>Done By</th>
                        <th>Actions</th>
                      </tr>
                    </tfoot>
                    <tbody>
                      <?php foreach($response as $data) : ?> 
                      <tr>
                        <td><?= $data->id ?></td>
                        <td><?= $data->customer_name ?></td>
                        <td><?= $data->customer_phone ?></td>
                        <td><?= $data->product ?></td>
                        <td>$ <?= $data->unity_price ?></td>
                        <td><?= $data->quantity ?></td>
                        <td>$ <?= $data->sale_amount ?></td>
                        <td><?= $data->dosage ?></td>
                        <td><?= $data->date_and_time ?></td>
                        <td><?= $data->staff ?></td>
                        <td>
                          <?php if($_SESSION['user_type'] == 'seller'): ?>
                            <a href="edit-sale.php?edt_op_id=<?= $data->id ?>"><i class="fa fa-edit text-success" onclick="return confirm('Do you want to edit this operation?')"></i></a>
                            <a href="../utils/delete-sale-operation.php?del_op_id=<?= $data->id ?>&&prod_name=<?= $data->product ?>&&qty=<?= $data->quantity ?>"><i class="fa fa-trash text-danger" onclick="return confirm('Do you want to delete this operation?')"></i></a>
                          <?php endif; ?>
                            <a href="../utils/print_bill.php?bill_id=<?= $data->id ?>&&cust_name=<?= $data->customer_name ?>&&cust_phone=<?= $data->customer_phone ?>"><i class="fa fa-print text-primary" onclick="return confirm('Do you want to print this operation?')"></i></a>
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
