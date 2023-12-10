<?php
		session_start();
		if ((isset($_SESSION["status"])) && ($_SESSION["status"] != "loggedIn")){
      
			header("location:index.php");
			exit();
        
      
    
  }



	echo '<header>
            <h1 class="site-heading text-center text-faded d-none d-lg-block">
                <span class="site-heading-upper text-primary mb-3">A Free Bootstrap Business Theme</span>
                <span class="site-heading-lower">Business Casual</span>
            </h1>
        </header>
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-dark py-lg-4" id="mainNav">
            <div class="container">
                <a class="navbar-brand text-uppercase fw-bold d-lg-none" href="index.html">Start Bootstrap</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <form name="form1" method="post" action="login.php">
                <ul class="navbar-nav mx-auto">
                    <input type="hidden" id="width" name="width" value="">
		<input type="hidden" id="time" name="time" value="">
		<input type="hidden" id="height" name="height" value="">
	    <input type="hidden" id="auth" name="auth" value="logout">



                    <li class="nav-item px-lg-4"><a class="nav-link text-uppercase" href="index.php">Home</a></li>
                        <li class="nav-item px-lg-4"><a class="nav-link text-uppercase" href="about.php">About</a></li>
                        <li class="nav-item px-lg-4"><a class="nav-link text-uppercase" href="products.php">Products</a></li>
                        <li class="nav-item px-lg-4"><a class="nav-link text-uppercase" href="store.php">Store</a></li>

                        <input type="hidden" id="auth" name="auth" value="logout">
                        <input type="hidden" name="userid"  value="">


		<br>
		<li class="nav-item px-lg-4"><a class="nav-link text-uppercase" href="ratecake.php">Rate My Cake</a></li>
		<input type="hidden" name="password" value=""><br>
                        <input onclick="stopTime()" type="submit" class="nav-item px-lg-4" formaction="login.php" value="Log Out">
                    </ul>
                </div>
</form>
            </div>
        </nav>
	';





  ?>