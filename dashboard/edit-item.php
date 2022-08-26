<?php 
  require_once 'shared/header.php';
  require_once '../utils/functions.php';

  start_session();
  
  page_access_restriction('../index.php'); 

  $item = checking_data_availability($_GET['edt_stock_id'],'t_stock');

  start_session();
  $success_msg = $failure_msg ='';
  if(isset($_POST['edit_item_btn'])) {
    if(field_not_empty(['drug_name','manufacturer','batch_no','qty','prod_date','expiry_date','cost_price'])) {
        $drug_name = check_field($_POST['drug_name']);
        $manufacturer = check_field($_POST['manufacturer']);
        $batch_no = check_field($_POST['batch_no']);
        $qty = check_field($_POST['qty']);
        $prod_date = check_field($_POST['prod_date']);
        $expiry_date = check_field($_POST['expiry_date']);
        $cost_price = check_field($_POST['cost_price']);
        $id = $_POST['item_id'];

        $query = $db->prepare("UPDATE t_stock SET drug_name=?, manufacturer=?, batch_no=?, quantity=?, production_date=?, expiry_date=?, registered_date=NOW(), cost_price=?, entered_by=? WHERE id=?");
        $query->execute([$drug_name, $manufacturer, $batch_no, $qty, $prod_date, $expiry_date,$cost_price, $_SESSION['id'], $id]);
        
        $success_msg = 'Product has been deleted successfully';
        header('location: stock.php');
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
              <p><a href="stock.php">dashboard</a>/<a href="stock.php">stock</a>/<span class="navigation">edit item</span></p>
              <!-- <a href="populate-stock.php" class="btn btn-success">Add an Item</a> -->
            </div>
            <!-- Content Row -->
            <div class="row">
                <div class="col-sm-2"></div>
                <div class="col-md-8">
                    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST" class="mb-6 border-success"> 
                    <h4 class="text-success text-center"><?= $success_msg ?></h4> 
                    <h4 class="text-danger text-center"><?= $failure_msg ?></h4> 
                    <input type="hidden" value="<?= $item->id ?>" name="item_id">
                    <div class="form-row">
                        <div class="form-group col-sm-12 col-xl-6">
                            <label for="drug_name">Drug Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control border-success"  value="<?= $item->drug_name ?>" id="drug_name" name="drug_name" placeholder="Drug Name" style="font-size: 16px;" required>
                        </div>
                        <div class="form-group col-sm-12 col-xl-6">
                            <label for="batch_no">Batch Number<span class="text-danger">*</span></label>
                            <input type="text" class="form-control border-success"  value="<?= $item->batch_no ?>" id="batch_no" name="batch_no" placeholder="Batch Number" style="font-size: 16px;" required>
                        </div>
                        <div class="form-group col-sm-12 col-xl-12">
                            <label for="manufacturer">Manufacturer<span class="text-danger">*</span></label>
                            <input type="text" class="form-control border-success" value="<?= $item->manufacturer ?>" id="manufacturer" name="manufacturer" placeholder="Manufacturer" style="font-size: 16px;" required>
                        </div>
                        <div class="form-group col-sm-12 col-xl-6">
                            <label for="qty">Quantity<span class="text-danger">*</span></label>
                            <input type="number" class="form-control border-success"  value="<?= $item->quantity ?>" id="qty" name="qty" placeholder="Quantity" style="font-size: 16px;" required>
                        </div>
                        <div class="form-group col-sm-12 col-xl-6">
                            <label for="prod_date">Production Date<span class="text-danger">*</span></label>
                            <input type="date" class="form-control border-success"  value="<?= $item->production_date ?>" id="prod_id" name="prod_date" style="font-size: 16px;" required>
                        </div>
                        <div class="form-group col-sm-12 col-xl-6">
                            <label for="exp_date">Expiry Date<span class="text-danger">*</span></label>
                            <input type="date" class="form-control border-success"  value="<?= $item->expiry_date ?>" id="exp_date" name="expiry_date" style="font-size: 16px;" required>
                        </div>
                        <div class="form-group col-sm-12 col-xl-6">
                            <label for="cost_price">Cost Price<span class="text-danger">*</span></label>
                            <input type="number" class="form-control border-success" value="<?= $item->cost_price ?>"  id="cost_price" name="cost_price" placeholder="Cost price" style="font-size: 16px;" required>
                        </div>
                        </div>
                        <input type="submit" value="EDIT ITEM" name="edit_item_btn" class="btn btn-primary btn-block" style="font-size: 20px;"> 
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
