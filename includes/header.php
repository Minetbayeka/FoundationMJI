<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($page_title) ? $page_title . ' - ' . SITE_NAME : SITE_NAME; ?></title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
 <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/bootstrap-icons.svg"></link>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/styles/Styles.css">
  <?php if(isset($additional_css)): ?>
      <?php foreach($additional_css as $css): ?>
          <link rel="stylesheet" href="<?php echo $css; ?>">
      <?php endforeach; ?>
  <?php endif; ?>

  <style>
    /* Hamburger icon */
    .navbar-toggler-icon {
      display: inline-block;
      width: 30px;
      height: 2px;
      background-color: white;
      position: relative;
    }

    .navbar-toggler-icon::before,
    .navbar-toggler-icon::after {
      content: "";
      position: absolute;
      width: 30px;
      height: 2px;
      background-color: white;
      left: 0;
    }

    .navbar-toggler-icon::before {
      top: -8px;
    }

    .navbar-toggler-icon::after {
      top: 8px;
    }

    /* Close icon */
    .close-icon {
      display: none;
      font-size: 1.5rem;
      color: white;
    }

    .bi-list{
       color:red
    }

    #navbarToggler {
  display: none;
  margin-right: 6px;
}

@media (max-width: 991.98px) {
  #navbarToggler {
    display: block;
  }
 
    .donate-btn {
    display: block;
    text-align: center;
    margin: 10px auto;
    width: 100%;
    max-width: 300px;
  }

}



@media (max-width: 575.98px) {
  .donate-btn {
    display: block;
    text-align: center;
    margin: 10px auto;
    width: 100%;
    max-width: 300px;
  }
}


    /* Toggle logic using Bootstrap's .collapsed class */
    /* .navbar-toggler:not(.collapsed) .navbar-toggler-icon {
      display: none;
    }

    .navbar-toggler:not(.collapsed) .close-icon {
      display: inline;
    } */
  </style>
</head>

<body>
  <header class="header">
    <nav class="navbar navbar-expand-lg navbar-dark">
      <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="<?php echo SITE_URL; ?>">
          <img src="<?php echo SITE_URL; ?>/assets/images/logo.png" alt="MJL Foundation" height="40" class="me-2">
          MJL Foundation
        </a>
        <button id="navbarToggler"><i class="fa fa-bars" aria-hidden="true"></i></button>

        <!-- Menu -->
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link"  href="<?php echo SITE_URL; ?>">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo SITE_URL; ?>/pages/about.php">About</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo SITE_URL; ?>/pages/projects.php">Projects</a></li>
            <li class="nav-item"><a class="nav-link" style="color:red" href="<?php echo SITE_URL; ?>/pages/blog.php">Blog</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo SITE_URL; ?>/pages/get-involved.php">Get Involved</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo SITE_URL; ?>/pages/contact.php">Contact</a></li>
            <li class="nav-item">
              <a class="nav-link donate-btn" href="<?php echo SITE_URL; ?>/features/donations/donation.php">
                <i class="fas fa-heart"></i> Donate
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>





  
<script>
  const toggler = document.getElementById('navbarToggler');
  toggler.addEventListener('click', function() {
    toggler.classList.toggle('collapsed');
    // console.log(toggler)

    const navbarNav = document.getElementById('navbarNav');
    if (navbarNav.classList.contains('show')) {
      navbarNav.classList.remove('show');
      toggler.innerHTML = '<i class="fa fa-bars" aria-hidden="true"></i>' 
    } else {
      navbarNav.classList.add('show');
      toggler.innerHTML = '<i class="fa fa-times" aria-hidden="true"></i>';
    }

  });
  
</script>

 
</body>
</html>

    <!-- http://localhost/foundation/pages/contact.php -->











    
