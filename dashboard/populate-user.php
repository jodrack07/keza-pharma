<?php 
  require_once 'shared/header.php';
  require_once '../utils/functions.php';
//   require_once '../utils/config/db_connection.php';

start_session();
  
page_access_restriction('../index.php'); 

  start_session();
  $success_msg = $failure_msg ='';
  if(isset($_POST['add_user_btn'])) {
    if(field_not_empty(['username','email','user_type','password'])) {
        $username = check_field($_POST['username']);
        $email = check_field($_POST['email']);
        $user_type = check_field($_POST['user_type']);
        $password = check_field($_POST['password']);
        $conf_password = check_field($_POST['conf_password']);

        if(!input_field_verif("/^[a-zA-Z_' ]+$/",$username)) {
            $_SESSION['flash-user']['danger'] = "Username must be alphabetical";
            header('location: users.php');
        }          
        if(strlen($password) <= 6 || strlen($conf_password) <= 6) {
            $_SESSION['flash-user']['danger'] = "The length of the password must be greater than 6";
            header('location: users.php');
        }          
        /*checking whether we have already a user within our database*/
        elseif(check_data_duplication('t_staff','username',$username)){
          $_SESSION['flash-user']['danger'] =  'Username already assigned to another user, Please choose another one';   
          header('location: users.php');
        }
        elseif(check_data_duplication('t_staff','email',$email)) {
            $_SESSION['flash-user']['danger'] =  'Email already assigned to another user, Please choose another one';
            header('location: users.php');
          }
        else {
            if($password == $conf_password) {
                $file = $_FILES['user_profile_picture'];
                $file_name = $file['name'];
                $file_tmp_name = $file['tmp_name'];
                $file_size = $file['size'];
                $file_error = $file['error'];
                // $file_type = $file['user_picture']['type'];
                $file_extension = explode('.', $file_name);
                $final_file_ext = strtolower(end($file_extension));
                $allowed_format = array('jpg','png','jpeg','svg','jfif');

                if(in_array($final_file_ext, $allowed_format)) {
                    if($file_error === 0) {
                        if($file_size <= 1000000) { //1MB
                            $profile_pic_name = $username.".".$final_file_ext;

                            //upload destination C:\xampp\htdocs\pharma-project\dashboard\img
                            $file_destination_path = 'C:\xampp\htdocs\pharma-project\dashboard\img\user-profile-pictures/'.$profile_pic_name;

                            //moving the file from the tmp to a new location
                            move_uploaded_file($file_tmp_name, $file_destination_path);

                            $hashed_password = password_hash($password,PASSWORD_BCRYPT);
                
                            $query = $db->prepare("INSERT INTO t_staff SET username=?, email=?, type=?, password=?, profile_picture=? ,created_at=NOW()");
                            $query->execute([$username, $email, $user_type, $hashed_password, $profile_pic_name]);
                            
                            $_SESSION['flash-user']['success'] = 'User has been created successfully';
                            header('location: users.php');
                        }else{
                            $_SESSION['flash-user']['danger'] = 'Too large file, Not supported';
                            header('location: users.php');
                        }
                    }
                    else{
                        $_SESSION['flash-user']['danger'] = 'An error occured when trying to load the image';
                        header('location: users.php');
                    }
                    }else{
                      $_SESSION['flash-user']['danger'] = 'File format not supported';
                      header('location: users.php');
                }
            }
            else {
                $_SESSION['flash-user']['danger'] = "Passwords don't match";
                header('location: users.php');
            }
        }

    }else {
        $_SESSION['flash-user']['danger'] = 'All fields are required';
        header('location: users.php');
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
              <p><a href="stock.php">dashboard</a>/<a href="stock.php">stock</a>/<span class="navigation">add user</span></p>
              <!-- <a href="populate-stock.php" class="btn btn-success">Add an Item</a> -->
            </div>
            <!-- Content Row -->
            <div class="row">
                <div class="col-sm-2"></div>
                <div class="col-md-8">
                    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST" enctype="multipart/form-data" class="mb-6 border-success"> 
                    <h4 class="text-success text-center"><?= $success_msg ?></h4> 
                    <h4 class="text-danger text-center"><?= $failure_msg ?></h4> 
                    <div class="form-row">
                        <div class="form-group col-sm-12 col-xl-6">
                            <label for="username">Username<span class="text-danger">*</span></label>
                            <input type="text" class="form-control border-success" id="username" name="username" placeholder="Username" style="font-size: 16px;" required>
                        </div>
                        <div class="form-group col-sm-12 col-xl-6">
                            <label for="email">Email<span class="text-danger">*</span></label>
                            <input type="email" class="form-control border-success" id="email" name="email" placeholder="Email" style="font-size: 16px;" required>
                        </div>
                        <div class="form-group col-sm-12 col-xl-6">
                            <label for="password">Password<span class="text-danger">*</span></label>
                            <input type="password" class="form-control border-success" id="password" name="password" placeholder="Password" style="font-size: 16px;" required>
                        </div>
                        <div class="form-group col-sm-12 col-xl-6">
                            <label for="conf_password">Confirm Password<span class="text-danger">*</span></label>
                            <input type="password" class="form-control border-success" id="conf_password" name="conf_password" placeholder="Confirm Password" style="font-size: 16px;" required>
                        </div>
                        <div class="form-group col-sm-12 col-xl-6">
                            <label for="user_type">Status</label>
                            <select id="user_type" name="user_type" class="form-control border-success">
                                <option disabled>Select User Type</option>
                                <option value="seller">seller</option>
                                <option value="admin">admin</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-12 col-xl-6">
                    <label for="user_profile_picture">Picture</label>
                    <input type="file" class="form-control border-success" value="" name="user_profile_picture" id="user_profile_picture" required>
                  </div>
                        </div>
                        <input type="submit" value="ADD A USER" name="add_user_btn" class="btn btn-primary btn-block" style="font-size: 20px;"> 
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
