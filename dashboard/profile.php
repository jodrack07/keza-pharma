<?php 
  require_once 'shared/header.php';
  require_once '../utils/functions.php';
  require_once '../utils/config/db_connection.php';

  start_session();

  page_access_restriction('../index.php');
  
  if(isset($_POST['update_pwd_btn'])) {
    $password = $_POST['password'];
    $conf_password = $_POST['conf_password'];
    if(!empty($password) && !empty($conf_password)) {
        if(strlen($password) <= 6 || strlen($conf_password) <= 6) {
            $_SESSION['flash-pwd-update']['danger'] = "The length of the password must be greater than 6";
            // header('location: profile.php');
        }
        else {
            if($password == $conf_password) {
                $password_hash = password_hash($password, PASSWORD_BCRYPT);
                // update the password
                $query = $db->prepare('UPDATE t_staff SET password = ?');
                $query->execute([$password_hash]);

                $_SESSION['flash-pwd-update']['success'] = 'Password has been updated successfully';
                // header('location: profile.php');
            }else {
                $_SESSION['flash-pwd-update']['danger'] = 'Passwords don\'t match';
                // header('location: profile.php');
            }
        }    
    }else {
        $_SESSION['flash-pwd-update']['danger'] = 'All fields are required';
        // header('location: profile.php');
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
            <!-- Content Row -->

            <!-- DataTales Example -->
            <div class="card shadow mb-4">
              <div class="card-body">
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">
                            <h5 class="mb-3">General Information</h5>
                            <img src="./img/user-profile-pictures/<?= $_SESSION['profile_pic'] ?>" width="250px" height="250px" alt="$_SESSION['profile_pic']">
                            
                            <?php 
                                $user = select_all_query($_SESSION['id'], 't_staff');
                            ?>
                            
                            <div style="margin-left: 10px; font-size: 17px; font-weight: 600">
                                <span>Username</span> : <?= $user->username ?>
                            </div>
                            <div style="margin-left: 10px; font-size: 17px; font-weight: 600">
                                <span>User Type</span> : <?= $user->type ?>
                            </div>
                            <div style="margin-left: 10px; font-size: 17px; font-weight: 600">
                                <span>Email</span>: <?= $user->email ?>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <h5 class="mb-3">Change Password</h5>

                            <?php if(isset($_SESSION['flash-pwd-update'])) : ?>
                                <?php foreach ($_SESSION['flash-pwd-update'] as $type => $message) : ?>
                                    <div class="alert alert-<?php echo $type;?> alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <div class="alert-message">
                                            <p style="font-size: 17px; font-weight: 500"><?php echo $message; ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                <!-- unsetting the super global $_SESSION['flash'] to erase the flash message above -->
                                <?php unset($_SESSION['flash-pwd-update']); ?>
                            <?php endif; ?>

                            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST" class="mb-6 border-success"> 
                                <div class="form-group col-sm-12 col-xl-12">
                                    <label for="password">Password<span class="text-danger">*</span></label>
                                    <input type="password" class="form-control border-success" id="password" name="password" placeholder="Password" style="font-size: 16px;" required>
                                </div>
                                <div class="form-group col-sm-12 col-xl-12">
                                    <label for="conf_password">Confirm Password<span class="text-danger">*</span></label>
                                    <input type="password" class="form-control border-success" id="conf_password" name="conf_password" placeholder="Confirm Password" style="font-size: 16px;" required>
                                </div>
                                <input type="submit" value="UPDATE PASSWORD" name="update_pwd_btn" class="btn btn-success btn-block" style="font-size: 20px;"> 
                            </form>
                        </div>
                        <div class="col-md-2"></div>
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
