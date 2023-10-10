<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-3">
  <div class="container">
    <a class="navbar-brand" href="#"><?php echo SITENAME; ?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">

        <li class="nav-item active">
          <a class="nav-link" href="<?php echo URLROOT; ?>/admins/index">Add Movie</a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="<?php echo URLROOT; ?>/admins/movies">Added Movies</a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="<?php echo URLROOT; ?>/admins/booked_movies">Booked Movies</a>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto">
        <?php if (isset($_SESSION['admin_id'])) : ?>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo URLROOT; ?>/admins/logout"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
          </li>
        <?php else : ?>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo URLROOT; ?>/admins/register">Register</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo URLROOT; ?>/admins/login">Login</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>