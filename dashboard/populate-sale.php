<?php 
  require_once 'shared/header.php';
  require_once '../utils/functions.php';

  start_session();
  
  page_access_restriction('../index.php'); 

  start_session();
  $failure_msg ='';
  if(isset($_POST['add_sale_btn'])) {
      if(field_not_empty(['customer_name','customer_phone','product','quantity', 'dosage'])) {
          $customer_name = check_field($_POST['customer_name']);
          $customer_phone = check_field($_POST['customer_phone']);
          $quantity = check_field($_POST['quantity']);
          $dosage = check_field($_POST['dosage']);
          $product = check_field($_POST['product']);

          $current_prod = get_data_by_field('t_stock', 'drug_name', $product);
          $current_prod_qty = $current_prod->quantity;
          $current_prod_unity_price = $current_prod->unity_price;
          $current_prod_name = $current_prod->drug_name;

          if(($current_prod_qty - 5) < $quantity) {
            $_SESSION['flash-stock']['danger'] = 'Cannot sell greater than the actual quantity';
            header('location: stock.php');
          }
          else {
            $sale_amount = $current_prod_unity_price * $quantity;

            // insert the sale in the database
            $query = $db->prepare("INSERT INTO t_sales SET customer_name=?, customer_phone=?, product=?, unity_price=?, quantity=?, dosage=?, sale_amount =?, date_and_time=NOW(), done_by =?");
            $query->execute([$customer_name, $customer_phone, $current_prod_name, $current_prod_unity_price, $quantity, $dosage, $sale_amount, $_SESSION['id']]);
            
            $new_quantity = $current_prod_qty - $quantity;

            // update the the quantity of the current product.
            $query = $db->prepare("UPDATE t_stock SET quantity = ? WHERE drug_name = ?");
            $query->execute([$new_quantity, $product]);

            $_SESSION['flash-sales']['success'] = 'Sale has been added successfully';
            
            header('location: sales.php');
          }
      }else {
          $failure_msg = 'All fields are required';
      }
  }
  
  
  $stock = select_data_greater_than_n_and_not_expired('t_stock', 'quantity', 5);

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
              <p><a href="stock.php">dashboard</a>/<a href="stock.php">sales</a>/<span class="navigation">add sale</span></p>
              <!-- <a href="populate-stock.php" class="btn btn-success">Add an Item</a> -->
            </div>
            <!-- Content Row -->
            <div class="row">
                <div class="col-sm-2"></div>
                <div class="col-md-8">
                  <form action="<?= $_SERVER['PHP_SELF'].'?item-id=' ?>" method="POST" class="mb-6 border-success"> 
                    <h4 class="text-danger text-center"><?= $failure_msg ?></h4> 
                    <!-- <input type="hidden" name="op_id"> -->
                    <div class="form-row">
                        <div class="form-group col-sm-12 col-xl-6">
                            <label for="customer_name">Customer Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control border-success" id="customer_name" name="customer_name" placeholder="Customer Name" style="font-size: 16px;" required>
                        </div>
                        <div class="form-group col-sm-12 col-xl-6">
                            <label for="customer_phone">Customer Phone<span class="text-danger">*</span></label>
                            <input type="number" class="form-control border-success" id="customer_phone" name="customer_phone" placeholder="Customer Phone" style="font-size: 16px;" required>
                        </div>
                        <div class="form-group col-sm-12 col-xl-12">
                          <label for="product">Product/Drug<span class="text-danger">*</span></label>
                          <select id="product" class="form-control border-success" name="product">   
                            <option disabled>Select product</option>
                            
                            <?php foreach($stock as $data) : ?>
                            
                            <option value="<?= $data->drug_name ?>"><?= $data->drug_name ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                        <div class="form-group col-sm-12 col-xl-6">
                            <label for="quantity">Quantity<span class="text-danger">*</span></label>
                            <input type="number" class="form-control border-success" id="quantity" name="quantity" placeholder="Quantity" style="font-size: 16px;" required>
                        </div>
                        <div class="form-group col-sm-12 col-xl-6">
                            <label for="dosage">Dosage<span class="text-danger">*</span></label>
                            <input type="text" class="form-control border-success" id="dosage" name="dosage" placeholder="Dosage" style="font-size: 16px;" required>
                        </div>
                        </div>
                        <input type="submit" value="ADD SALE" name="add_sale_btn" class="btn btn-primary btn-block" style="font-size: 20px;"> 
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
