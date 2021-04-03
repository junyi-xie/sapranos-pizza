<?php 
    /* Copyright (c) - 2021 by Junyi Xie */	

    include_once("inc/connect.php");
    include_once("inc/functions.php");
    include_once("inc/class.php");


    // do something to see if user is logged in blah blah
    // printr($_SESSION);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sopranos</title>
    <meta charset="UTF-8">
    <meta name="author" content="Junyi Xie">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/images/favicon/favicon.ico" type="image/x-icon">
    <!-- <link rel="stylesheet" type="text/css" href="assets/css/style.css?<?php echo date("YmdHis"); ?>" media="screen"> -->
	  
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
	  <script src="https://kit.fontawesome.com/b57a0b7ac6.js" crossorigin="anonymous"></script>
    <style>

* {
  padding: 0;
  margin: 0;
}

body {
  font-family: "Poppins", Arial, sans-serif;
  font-size: 16px;
  line-height: 1.8;
  font-weight: normal;
  background: #fafafa;
  color: gray;
}

a {
  -webkit-transition: 0.3s all ease;
  -o-transition: 0.3s all ease;
  transition: 0.3s all ease;
  color: #494ca2;
}
a:hover,
a:focus {
  text-decoration: none !important;
  outline: none !important;
  -webkit-box-shadow: none;
  box-shadow: none;
}

button {
  -webkit-transition: 0.3s all ease;
  -o-transition: 0.3s all ease;
  transition: 0.3s all ease;
}
button:hover,
button:focus {
  text-decoration: none !important;
  outline: none !important;
  -webkit-box-shadow: none !important;
  box-shadow: none !important;
}

h1, h2, h3, h4, h5, .h1, .h2, .h3, .h4, .h5 {
  line-height: 1.5;
  font-weight: 700;
  font-family: "Poppins", Arial, sans-serif;
  color: #000;
}

.wrapper {
  width: 100%;
}

#sidebar {
  min-width: 270px;
  max-width: 270px;
  background: #494ca2;
  color: #fff;
  -webkit-transition: all 0.3s;
  -o-transition: all 0.3s;
  transition: all 0.3s;
  position: relative;
}

#sidebar.active {
  margin-left: -270px;
}

#sidebar.active .custom-menu {
  margin-right: -45px;
}

#sidebar h1 {
  margin-bottom: 20px;
  font-weight: 700;
}

#sidebar h1 .logo {
  color: #fff;
}

#sidebar ul.components {
  padding: 0;
}

#sidebar ul li {
  font-size: 16px;
}

#sidebar ul li > ul {
  margin-left: 10px;
}

#sidebar ul li > ul li {
  font-size: 14px;
}

#sidebar ul li a {
  padding: 10px 0;
  display: block;
  color: rgba(255, 255, 255, 0.6);
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

#sidebar ul li a:hover {
  color: #fff;
}

#sidebar ul li.active > a {
  background: transparent;
  color: #fff;
}

@media (max-width: 1024px) {
  #sidebar {
    margin-left: -270px;
  }
  #sidebar.active {
    margin-left: 0;
  }
  #sidebar .custom-menu {
    margin-right: -60px !important;
    top: 10px !important;
  }
}

#sidebar .custom-menu {
  display: inline-block;
  position: absolute;
  top: 20px;
  right: 0;
  margin-right: -20px;
  -webkit-transition: 0.3s;
  -o-transition: 0.3s;
  transition: 0.3s;
}

@media (prefers-reduced-motion: reduce) {
  #sidebar .custom-menu {
    -webkit-transition: none;
    -o-transition: none;
    transition: none;
  }
}

#sidebar .custom-menu .btn {
  width: 40px;
  height: 40px;
  border-radius: 50%;
}

#sidebar .custom-menu .btn.btn-primary {
  background: #393c7f;
  border-color: #393c7f;
}

.dropdown-toggle::after {
  display: block;
  position: absolute;
  top: 50%;
  right: 0;
  -webkit-transform: translateY(-50%);
  -ms-transform: translateY(-50%);
  transform: translateY(-50%);
}

.dropdown-toggle::after {
  display: inline-block;
  margin-left: 0.255em;
  vertical-align: 0.255em;
  content: "";
  border-top: 0.3em solid;
  border-right: 0.3em solid transparent;
  border-bottom: 0;
  border-left: 0.3em solid transparent;
}

a[data-toggle="collapse"] {
  position: relative;
}

#sidebar .custom-menu .btn.btn-primary:hover,
#sidebar .custom-menu .btn.btn-primary:focus {
  background: #393c7f !important;
  border-color: #393c7f !important;
}

@media (max-width: 1024px) {
  #sidebarCollapse span {
    display: none;
  }
}

#content {
  width: 100%;
  padding: 0;
  min-height: 100vh;
  -webkit-transition: all 0.3s;
  -o-transition: all 0.3s;
  transition: all 0.3s;
}

.mr-3-alt {
  margin-right: 1.3rem!important;
}
    </style>
</head>
<body>

	<div class="wrapper d-flex align-items-stretch">

		<nav id="sidebar">

			<div class="custom-menu">
	
				<button type="button" id="sidebarCollapse" class="btn btn-primary"><i class="fa fa-bars"></i><span class="sr-only">Toggle Menu</span></button>

			</div>

			<div class="p-4 pt-5">

  				<h1><span class="logo">Sopranos</span></h1>

				<ul class="list-unstyled components">

					<li class="active">
						<a href="dashboard.php"><span class="fas fa-tachometer-alt mr-3"></span>Dashboard</a>
					</li>
		
					<li>
					  	<a href="#submenuRooms" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><span class="fas fa-hotel mr-3"></span>Rooms</a>

              			<ul class="collapse list-unstyled" id="submenuRooms">

                			<li>
                    			<a href="rooms.php?page=1"><i class="fas fa-bed mr-3"></i>Show Rooms</a>
							</li>
							
               	 			<li>
                    			<a href="create.php"><i class="fas fa-plus mr-3-alt"></i>Add Room</a>
                			</li>
        
						</ul>
						  
					</li>		  
  
		  			<li>
						<a href="customers.php?page=1"><span class="fas fa-address-card mr-3"></span>Customers</a>
					</li>
		
	  				<li>
						  <a href="reservations.php?page=1"><span class="fas fa-user-alt mr-3"></span>Reservations</a>
		  			</li>
	  
					<li>
					  	<a href="#submenuPages" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="fa fa-paper-plane mr-3"></i>Pages</a>

              			<ul class="collapse list-unstyled" id="submenuPages">

						  	<li>
                    			<a href="location.php"><i class="fas fa-map-marker-alt mr-3"></i>Location</a>
							</li>

                			<li>
                    			<a href="contact.php"><i class="far fa-address-book mr-3"></i>Contact</a>
							</li>
									
							<li>
								<a href="openinghours.php"><i class="fas fa-clock mr-3"></i>Openinghours</a>
							</li>

							<li>
								<a href="alerts.php"><i class="fas fa-exclamation-triangle mr-3"></i>Alerts</a>
							</li>
        
						</ul>
						  
					</li>

					<li>
						<a href="profile.php"><i class="fas fa-user-circle mr-3"></i>Profile</a>
					</li>

					<li>
						<a href="signout.php"><i class="fas fa-sign-out-alt mr-3"></i>Sign Out</a>
					</li>
		  
				</ul>	   

  			</div>

		</nav>

  		<div id="content" class="p-4 p-md-5 pt-5">
		
			<h2 class="mb-4">Welcome!</h2>
	
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
  
		</div>

	</div>

	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
	<script>
    
    $('#sidebarCollapse').on('click', function () {
      $('#sidebar').toggleClass('active');
  	});
    </script>	
</body>
</html>