<?php 
  require_once 'shared/header.php';
  require_once '../utils/functions.php';

  start_session();
  
  page_access_restriction('../index.php'); 

  $user = checking_data_availability($_GET['edt_user_id'],'t_staff');

  start_session();
  $failure_msg ='';

  if(isset($_POST['edit_user_btn'])) {
    if(field_not_empty(['username','email','user_type', 'user_id'])) {
        $username = check_field($_POST['username']);
        $email = check_field($_POST['email']);
        $user_type = check_field($_POST['user_type']);
        $user_id = check_field($_POST['user_id']);
        
        $query = $db->prepare("UPDATE t_staff SET username=?, email=?, type=? WHERE id=?");
        $query->execute([$username, $email, $user_type, $user_id]);
        
        header('location: users.php');
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
                    <h4 class="text-danger text-center"><?= $failure_msg ?></h4> 
                    <input type="hidden" name="user_id" value="<?= $user->id ?>">
                    <div class="form-row">
                        <div class="form-group col-sm-12 col-xl-6">
                            <label for="username">Username<span class="text-danger">*</span></label>
                            <input type="text" class="form-control border-success" value=<?= $user->username ?> id="username" name="username" placeholder="Username" style="font-size: 16px;" required>
                        </div>
                        <div class="form-group col-sm-12 col-xl-6">
                            <label for="user_type">Status</label>
                            <select id="user_type" name="user_type" class="form-control border-success">
                                <option disabled>Select User Type</option>
                                <option value="<?= $user->type ?>"><?= $user->type ?></option>
                                <option value="seller">seller</option>
                                <option value="admin">admin</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-12 col-xl-12">
                            <label for="email">Email<span class="text-danger">*</span></label>
                            <input type="email" class="form-control border-success" value=<?= $user->email ?> id="email" name="email" placeholder="Email" style="font-size: 16px;" required>
                        </div>
                        </div>
                        <input type="submit" value="EDIT A USER" name="edit_user_btn" class="btn btn-primary btn-block" style="font-size: 20px;"> 
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
