<?php 
  require_once 'shared/header.php';
  require_once '../utils/functions.php';
//   require_once '../utils/config/db_connection.php';

start_session();
  
page_access_restriction('../index.php'); 

  start_session();
  $success_msg = $failure_msg ='';
  if(isset($_POST['add_item_btn'])) {
    if(field_not_empty(['drug_name','manufacturer','batch_no','qty','unity_price','prod_date','expiry_date','cost_price'])) {
        $drug_name = check_field($_POST['drug_name']);
        $manufacturer = check_field($_POST['manufacturer']);
        $batch_no = check_field($_POST['batch_no']);
        $qty = check_field($_POST['qty']);
        $prod_date = check_field($_POST['prod_date']);
        $expiry_date = check_field($_POST['expiry_date']);
        $cost_price = check_field($_POST['cost_price']);
        $unity_price = check_field($_POST['unity_price']);

        $today_date = date('D-m-y');
        echo $today_date;

        if($prod_date > $today_date) {
          if($qty >= 1) {
            if(check_data_duplication('t_stock','drug_name',trim($drug_name)) > 0) {
              $_SESSION['flash-stock']['danger'] = 'Product duplication not allowed';
                header('location: stock.php');
            }else {
              $query = $db->prepare("INSERT INTO t_stock SET drug_name=?, manufacturer=?, batch_no=?, quantity=?, unity_price=? ,production_date=?, expiry_date=?, registered_date=NOW(), cost_price=?, entered_by=?");
              $query->execute([$drug_name, $manufacturer, $batch_no, $qty, $unity_price ,$prod_date, $expiry_date,$cost_price, $_SESSION['id']]);
              
              $_SESSION['flash-stock']['success'] = 'Product has been added successfully';
              header('location: stock.php');
            }
          }else {
            $_SESSION['flash-stock']['danger'] = '0 quantity is not supported';
            header('location: stock.php');
          }
        }else {
            $_SESSION['flash-stock']['danger'] = 'Production date connot be greater than today date';
            header('location: stock.php');
        }
    }else {
        $failure_msg = 'All fields are required';
    }
  }

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
              <p><a href="stock.php">dashboard</a>/<a href="stock.php">stock</a>/<span class="navigation">Add Item</span></p>
              <!-- <a href="populate-stock.php" class="btn btn-success">Add an Item</a> -->
            </div>
            <!-- Content Row -->
            <div class="row">
                <div class="col-sm-2"></div>
                <div class="col-md-8">
                    <form action="" method="POST" class="mb-6 border-success"> 
                    <h4 class="text-success text-center"><?= $success_msg ?></h4> 
                    <h4 class="text-danger text-center"><?= $failure_msg ?></h4> 
                    <div class="form-row">
                        <div class="form-group col-sm-12 col-xl-6">
                            <label for="drug_name">Drug Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control border-success" id="drug_name" name="drug_name" placeholder="Drug Name" style="font-size: 16px;" required>
                        </div>
                        <div class="form-group col-sm-12 col-xl-6">
                            <label for="manufacturer">Manufacturer<span class="text-danger">*</span></label>
                            <input type="text" class="form-control border-success" id="manufacturer" name="manufacturer" placeholder="Manufacturer" style="font-size: 16px;" required>
                        </div>
                        <div class="form-group col-sm-12 col-xl-6">
                            <label for="batch_no">Batch Number<span class="text-danger">*</span></label>
                            <input type="text" class="form-control border-success" id="batch_no" name="batch_no" placeholder="Batch Number" style="font-size: 16px;" required>
                        </div>
                        <div class="form-group col-sm-12 col-xl-6">
                            <label for="qty">Quantity<span class="text-danger">*</span></label>
                            <input type="number" class="form-control border-success" id="qty" name="qty" placeholder="Quantity" style="font-size: 16px;" required>
                        </div>
                        <div class="form-group col-sm-12 col-xl-6">
                            <label for="prod_date">Production Date<span class="text-danger">*</span></label>
                            <input type="date" class="form-control border-success" id="prod_id" name="prod_date" style="font-size: 16px;" required>
                        </div>
                        <div class="form-group col-sm-12 col-xl-6">
                            <label for="exp_date">Expiry Date<span class="text-danger">*</span></label>
                            <input type="date" class="form-control border-success" id="exp_date" name="expiry_date" style="font-size: 16px;" required>
                        </div>
                        <div class="form-group col-sm-12 col-xl-6">
                            <label for="unity_price">Unity Price<span class="text-danger">*</span></label>
                            <input type="number" class="form-control border-success" id="unity_price" name="unity_price" placeholder="Unity price" style="font-size: 16px;" required>
                        </div>
                        <div class="form-group col-sm-12 col-xl-6">
                            <label for="cost_price">Cost Price<span class="text-danger">*</span></label>
                            <input type="number" class="form-control border-success" id="cost_price" name="cost_price" placeholder="Cost price" style="font-size: 16px;" required>
                        </div>
                        </div>
                        <input type="submit" value="ADD AN ITEM" name="add_item_btn" class="btn btn-primary btn-block" style="font-size: 20px;"> 
                    </form>
                </div>
                <div class="col-sm-2"></div>
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

    <?php require_once 'shared/scripts.php' ?>

  </body>
</html>
