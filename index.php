<?php
  session_start();

  require_once 'utils/config/db_connection.php';
  require_once 'utils/functions.php';

  if(isset($_SESSION['id'])) {
    header('location: dashboard');
    exit();
  }

  $username = $password = "";
  $username_err = $password_err = $server_err = "";
  
  if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(empty(trim($_POST['username']))) {
      $username_err = "username required";
    }
    else {
      $username = htmlspecialchars(trim($_POST['username']));
    }

    if(empty(trim($_POST['password']))) {
      $password_err = "password required";
    }else {
      $password = htmlspecialchars($_POST['password']);
    }

    if(empty($username_err) & empty($password_err)) {

      $req = $db->prepare("SELECT * FROM t_staff WHERE username = ?");
      $req->execute([$username]);

      $user_count = $req->rowCount();

      if($user_count > 0) {
        $user = $req->fetch(PDO::FETCH_OBJ);
        if(password_verify($password,$user->password)) {
          // session_start();
          $_SESSION['id'] = $user->id;
          $_SESSION['username'] = $user->username;
          $_SESSION['user_type'] = $user->type;
          $_SESSION['pwd'] = $user->password;
          $_SESSION['profile_pic'] = $user->profile_picture;

          header('location: dashboard');
          exit();	
        }
        else{
          $password_err = "Incorrect password";
        }
      }else{
        $username_err = "Username not recognized";
      }
    }
  }else {
    $server_err = "Something went wrong, please try again later";
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>Login</title>

    <!-- Custom fonts for this template-->
    <link
      href="dashboard/vendor/fontawesome-free/css/all.min.css"
      rel="stylesheet"
      type="text/css"
    />
    <link
      href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
      rel="stylesheet"
    />

    <!-- Custom styles for this template-->
    <link href="dashboard/css/sb-admin-2.min.css" rel="stylesheet" />
    <style>
      .has-error {
        border: 1px solid red;
      }
    </style>
  </head>

  <body class="">
    <div class="container">
      <!-- Outer Row -->
      <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12 col-md-9">
          <div class="card o-hidden border-0" style="background-color: #f3f3f3; margin-top: 20%">
            <div class="card-body p-0">
              <!-- Nested Row within Card Body -->
              <div class="row">
                <div class="col-lg-3 col-sm-12"></div>
                <div class="col-lg-6 col-sm-12">
                  <div class="p-5">
                    <div class="text-center">
                      <h1 class="mb-4" style="color: #444;font-size: 20px; font-weight: 500;">La Gloire Pharma</h1>
                      <h1><?= isset($msg) ? $msg : '' ?></h1>
                      <p class="text-danger" style="font-weight: bolder; font-size: 16px;"><?= isset($_SESSION['not_loged_in']) ? $_SESSION['not_loged_in'] : '' ?></p>
                    </div>
                    <!-- <form action="" method="post" class="user"> -->
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="user">
                      <div class="form-group">
                        <input
                          type="username"
                          class="form-control form-control-user <?= empty($username_err) ? '' : 'has-error' ?>"
                          id="username"
                          name="username"
                          value="<?= $username ?>"
                          aria-describedby="usernameHelp"
                          placeholder="Username"
                          style="border-radius: 10px; font-size: 1rem;"
                        />
                        <span class="text-danger"><?php echo $username_err; ?></span>
                      </div>
                      <div class="form-group">
                        <input
                          type="password"
                          class="form-control form-control-user <?= empty($username_err) ? '' : 'has-error' ?>"
                          id="exampleInputPassword"
                          name="password"
                          placeholder="Password"
                          style="border-radius: 10px;font-size: 1rem;"
                        />
                        <span class="text-danger"><?php echo $password_err; ?></span>
                      </div>
                      <input type="submit" value="Login" class="btn btn-success btn-user btn-block" style="border-radius: 5px; font-size: 1rem;font-weight: 500;">
                  </div>
                </div>
                <div class="col-lg-3 col-sm-12"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
  </body>
</html>
