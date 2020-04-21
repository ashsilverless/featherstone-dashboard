<?php include 'header.php';?>

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Row -->
          <div class="row">
            <div class="clearfix"></div>
            <div class="col-6 offset-3 login">
						<div class="text-center border-box login__inner">
								<h1 id="loginlogo" class="logo">
                                    <?php include 'client/images/fs-logo.php'; ?>
                                </h1>
								<!--<form name="reg" action="index.php" method="POST">
									<?=$strmsg;?>
									<input type="hidden" name="csrf" 	 value="<?php print $_SESSION["token"]; ?>" >
									<input type="hidden" name="passcode" value="<?php echo $passcode; ?>" >
                                    <div class="form-group">
										<label>Email Address</label>
										<input type="text" name="username" id="username" autocomplete="off" value="" required>

                                    </div>
                                    <div class="form-group">
										<label>Password</label>
										<input type="password" name="password" id="password" autocomplete="off" value="" required>

                                    </div>
                                    <div class="silverless-button">
                                        <input  id="go" type="submit" value="Log in">
                                    </div>

									 <div class="form-text text-right">
										 <p>Forgot password? Click <a href="">here</a></p>
                                    </div>

                                </form>-->
								<form action="authenticate.php" method="post" name="login" id="login">
									<label for="email" id="emaillabel" >
										EMail Address
									</label>
									<input type="text" name="email" placeholder="Email Address" id="email" required>
									<label for="password" id="pass">
										Password
									</label>
									<input type="password" name="password" placeholder="Password" id="password" required>

                                  <input  id="go" type="submit" value="Log in">

                                  <p>Forgot password? Click <a href="">here</a></p>

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
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Silverless 2019</span>
          </div>
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

  <?php define('__ROOT__', dirname(dirname(__FILE__)));
  require_once(__ROOT__.'/footer.php');?>
