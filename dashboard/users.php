<?php 
  require_once 'shared/header.php';
  require_once '../utils/functions.php';

  start_session();

  page_access_restriction('../index.php'); 

  $response = fetch_user_data();
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
              <p><a href="index.php">dashboard</a>/<span class="navigation">Users</span></p>
              <div>
                <a href="populate-user.php" onclick="return confirm('Do you want to add a new user?')" class="btn btn-success">Add a user</a>
                <a href="../utils/print-users.php" onclick="return confirm('Do you want to print the list of all users?')" class="btn btn-primary">Print PDF</a>
              </div>
            </div>
            <!-- Content Row -->

            <?php if(isset($_SESSION['flash-user'])) : ?>
            <!-- looping over all the flash messages found -->
            <?php foreach ($_SESSION['flash-user'] as $type => $message) : ?>
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
            <?php unset($_SESSION['flash-user']); ?>
        <?php endif; ?>

            <!-- DataTales Example -->
            <div class="card shadow mb-4">
              <div class="card-header py-3">
                <div class="d-flex justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">
                    Available users in the system
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
                        <th>#</th>
                        <th>Username</th>
                        <th>Email.</th>
                        <th>Type</th>
                        <th>Profile</th>
                        <th>Created At</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tfoot>
                      <tr>
                        <th>#</th>
                        <th>Username</th>
                        <th>Email.</th>
                        <th>Type</th>
                        <th>Profile</th>
                        <th>Created At</th>
                        <th>Actions</th>
                      </tr>
                    </tfoot>
                    <tbody>
                      <?php foreach($response as $user) : ?> 
                      <tr>
                        <td><?= $user->id ?></td>
                        <td><?= $user->username ?></td>
                        <td><?= $user->email ?></td>
                        <td><?= $user->type ?></td>
                        <td><img src="./img/user-profile-pictures/<?= $user->profile_picture ?>" alt="<?= $user->profile_picture ?>" style="width: 50px; height: 50px; border-raduis: 50%"></td>
                        <td><?= $user->created_at ?></td>
                        <td>
                          <a href="edit-user.php?edt_user_id=<?= $user->id ?>"><i class="fa fa-edit text-success" onclick="return confirm('Do you want to edit this user?')"></i></a>
                          <a href="../utils/delete.php?del_user_id=<?= $user->id ?>"><i class="fa fa-trash text-danger" onclick="return confirm('Do you want to delete this user?')"></i></a>
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
