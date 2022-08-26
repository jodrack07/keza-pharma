<?php 
  require_once 'shared/header.php';
  require_once '../utils/functions.php';

  start_session();
  
  page_access_restriction('../index.php'); 

  $item = checking_data_availability($_GET['edt_op_id'],'t_sales');

  start_session();
  $failure_msg ='';
  if(isset($_POST['edit_sale_btn'])) {
    if(field_not_empty(['customer_name','customer_phone','unity_price','quantity'])) {
        $customer_name = check_field($_POST['customer_name']);
        $customer_phone = check_field($_POST['customer_phone']);
        $unity_price = check_field($_POST['unity_price']);
        $quantity = check_field($_POST['quantity']);
        $id = $_POST['op_id'];

        $sale_amount = $unity_price * $quantity;

        $query = $db->prepare("UPDATE t_sales SET customer_name=?, customer_phone=?, unity_price=?, quantity=?, sale_amount =?, date_and_time=NOW() WHERE id=?");
        $query->execute([$customer_name, $customer_phone, $unity_price, $quantity, $sale_amount, $id]);
        
        $_SESSION['flash-sales']['success'] = 'Sale has been updated successfully';

        header('location: sales.php');
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
                    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST" class="mb-6 border-success"> 
                    <h4 class="text-success text-center"><?= $success_msg ?></h4> 
                    <h4 class="text-danger text-center"><?= $failure_msg ?></h4> 
                    <input type="hidden" value="<?= $item->id ?>" name="op_id">
                    <div class="form-row">
                        <div class="form-group col-sm-12 col-xl-6">
                            <label for="customer_name">Customer Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control border-success"  value="<?= $item->customer_name ?>" id="customer_name" name="customer_name" placeholder="Customer Name" style="font-size: 16px;" required>
                        </div>
                        <div class="form-group col-sm-12 col-xl-6">
                            <label for="customer_phone">Customer Phone<span class="text-danger">*</span></label>
                            <input type="text" class="form-control border-success" value="<?= $item->customer_phone ?>" id="customer_phone" name="customer_phone" placeholder="Customer Phone" style="font-size: 16px;" required>
                        </div>
                        <div class="form-group col-sm-12 col-xl-6">
                            <label for="unity_price">Unity Price<span class="text-danger">*</span></label>
                            <input type="number" class="form-control border-success"  value="<?= $item->unity_price ?>" id="unity_price" name="unity_price" placeholder="Unity Price" style="font-size: 16px;" required>
                        </div>
                        <div class="form-group col-sm-12 col-xl-6">
                            <label for="quantity">Quantity<span class="text-danger">*</span></label>
                            <input type="number" class="form-control border-success"  value="<?= $item->quantity ?>" id="quantity" name="quantity" placeholder="Quantity" style="font-size: 16px;" required>
                        </div>
                        </div>
                        <input type="submit" value="EDIT SALE" name="edit_sale_btn" class="btn btn-primary btn-block" style="font-size: 20px;"> 
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
<!--  -->