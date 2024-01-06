<?php
class Admins extends Controller
{
  public function __construct()
  {
    $this->adminModel = $this->model('Admin');
  }

  public function index()
  {

    if (!$this->isLoggedIn()) {
      redirect('admins/login');
    }

    $this->view('admins/index');
  }

  public function register()
  {
    // Check if logged in
    if ($this->isLoggedIn()) {
      redirect('admins/index');
    }

    // Check if POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Sanitize POST
      $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      $data = [
        'name' => trim($_POST['name']),
        'email' => trim($_POST['email']),
        'password' => trim($_POST['password']),
        'confirm_password' => trim($_POST['confirm_password']),
        'name_err' => '',
        'email_err' => '',
        'password_err' => '',
        'confirm_password_err' => ''
      ];

      // Validate email
      if (empty($data['email'])) {
        $data['email_err'] = 'Please enter an email';
        // Validate name
        if (empty($data['name'])) {
          $data['name_err'] = 'Please enter a name';
        }
      } else {
        // Check Email
        if ($this->adminModel->findUserByEmail($data['email'])) {
          $data['email_err'] = 'Email is already taken.';
        }
      }

      // Validate password
      if (empty($data['password'])) {
        $password_err = 'Please enter a password.';
      } elseif (strlen($data['password']) < 6) {
        $data['password_err'] = 'Password must have atleast 6 characters.';
      }

      // Validate confirm password
      if (empty($data['confirm_password'])) {
        $data['confirm_password_err'] = 'Please confirm password.';
      } else {
        if ($data['password'] != $data['confirm_password']) {
          $data['confirm_password_err'] = 'Password do not match.';
        }
      }

      // Make sure errors are empty
      if (empty($data['name_err']) && empty($data['email_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
        // SUCCESS - Proceed to insert

        // Hash Password
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        //Execute
        if ($this->adminModel->register($data)) {
          // Redirect to login
          flash('register_success', 'You are now registered and can log in');
          redirect('admins/login');
        } else {
          die('Something went wrong');
        }
      } else {
        // Load View
        $this->view('admins/register', $data);
      }
    } else {
      // IF NOT A POST REQUEST

      // Init data
      $data = [
        'name' => '',
        'email' => '',
        'password' => '',
        'confirm_password' => '',
        'name_err' => '',
        'email_err' => '',
        'password_err' => '',
        'confirm_password_err' => ''
      ];

      // Load View
      $this->view('admins/register', $data);
    }
  }

  public function login()
  {
    // Check if logged in
    if ($this->isLoggedIn()) {
      redirect('admins/index');
    }

    // Check if POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Sanitize POST
      $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      $data = [
        'email' => trim($_POST['email']),
        'password' => trim($_POST['password']),
        'email_err' => '',
        'password_err' => '',
      ];

      // Check for email
      if (empty($data['email'])) {
        $data['email_err'] = 'Please enter email.';
      }

      // Check for name
      if (empty($data['name'])) {
        $data['name_err'] = 'Please enter name.';
      }

      // Check for user
      if ($this->adminModel->findUserByEmail($data['email'])) {
        // User Found
      } else {
        // No User
        $data['email_err'] = 'This email is not registered.';
      }

      // Make sure errors are empty
      if (empty($data['email_err']) && empty($data['password_err'])) {

        // Check and set logged in user
        $loggedInUser = $this->adminModel->login($data['email'], $data['password']);

        if ($loggedInUser) {
          // User Authenticated!
          $this->createAdminSession($loggedInUser);
        } else {
          $data['password_err'] = 'Password incorrect.';
          // Load View
          $this->view('admins/login', $data);
        }
      } else {
        // Load View
        $this->view('admins/login', $data);
      }
    } else {
      // If NOT a POST

      // Init data
      $data = [
        'email' => '',
        'password' => '',
        'email_err' => '',
        'password_err' => '',
      ];

      // Load View
      $this->view('admins/login', $data);
    }
  }

  // Create Session With User Info
  public function createAdminSession($user)
  {
    $_SESSION['admin_id'] = $user->id;
    $_SESSION['admin_email'] = $user->email;
    $_SESSION['admin_name'] = $user->name;
    redirect('admins/index');
  }

  // Logout & Destroy Session
  public function logout()
  {
    unset($_SESSION['admin_id']);
    unset($_SESSION['admin_email']);
    unset($_SESSION['admin_name']);
    session_destroy();
    redirect('admins/login');
  }

  // Check Logged In
  public function isLoggedIn()
  {
    if (isset($_SESSION['admin_id'])) {
      return true;
    } else {
      return false;
    }
  }
  // ===========================================================================
  public function storeMovieData()
  {

    if (!$this->isLoggedIn()) {
      redirect('admins/login');
    }

    $movieName = $_POST['movie_name'];
    $movieDescription = $_POST['movie_description'];
    $movieCost = $_POST['movie_cost'];
    $availableTickets = $_POST['available_tickets'];

    $movieData = [
      'movie_name' => $movieName,
      'movie_description' => $movieDescription,
      'movie_cost' => $movieCost,
      'available_tickets' => $availableTickets
    ];

    $this->adminModel->insertMovie($movieData);

    redirect('admins/index');
  }

  public function movies()
  {

    if (!$this->isLoggedIn()) {
      redirect('admins/login');
    }

    $movies = $this->adminModel->getMovies();
    $data = [
      'movies' => $movies
    ];
    $this->view('admins/movies', $data);
  }



  public function deleteMovie($id)
  {

    if (!$this->isLoggedIn()) {
      redirect('admins/login');
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      if ($this->adminModel->deleteMovie($id)) {
        redirect('admins/movies');
      } else {
        die('Error occurred while deleting the movie.');
      }
    } else {
      redirect('admins/movies');
    }
  }


  public function booked_movies()
  {

    if (!$this->isLoggedIn()) {
      redirect('admins/login');
    }
    $movies = $this->adminModel->getMoviesWithBookingDetails();
    $data = [
      'movies' => $movies
    ];
    $this->view('admins/booked_movies', $data);
  }
}
