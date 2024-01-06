<?php
class Users extends Controller
{
  public function __construct()
  {
    $this->userModel = $this->model('User');
    $this->adminModel = $this->model('Admin');
  }

  // ==========================================================
  public function hasNotifications()
  {
    return isset($_SESSION['notifications']) && !empty($_SESSION['notifications']);
  }

  public function getNotifications()
  {
    if ($this->hasNotifications()) {
      $notifications = $_SESSION['notifications'];
      unset($_SESSION['notifications']);
      return $notifications;
    }

    return [];
  }

  public function addNotification($type, $message, $title = '')
  {
    if (!isset($_SESSION['notifications'])) {
      $_SESSION['notifications'] = [];
    }

    $_SESSION['notifications'][] = [
      'type' => $type,
      'message' => $message,
      'title' => $title
    ];
  }
  // ==========================================================

  public function index()
  {
    if (!$this->isLoggedIn()) {
      redirect('users/login');
    }

    $this->addNotification('success', 'Success message', 'Success');

    $this->view('users/index');
  }

  public function register()
  {
    // Check if logged in
    if ($this->isLoggedIn()) {
      redirect('users/index');
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
        if ($this->userModel->findUserByEmail($data['email'])) {
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
        if ($this->userModel->register($data)) {
          // Redirect to login
          flash('register_success', 'You are now registered and can log in');
          redirect('users/login');
        } else {
          die('Something went wrong');
        }
      } else {
        // Load View
        $this->view('users/register', $data);
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
      $this->view('users/register', $data);
    }
  }

  public function login()
  {
    // Check if logged in
    if ($this->isLoggedIn()) {
      redirect('users/index');
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
      if ($this->userModel->findUserByEmail($data['email'])) {
        // User Found
      } else {
        // No User
        $data['email_err'] = 'This email is not registered.';
      }

      // Make sure errors are empty
      if (empty($data['email_err']) && empty($data['password_err'])) {

        // Check and set logged in user
        $loggedInUser = $this->userModel->login($data['email'], $data['password']);

        if ($loggedInUser) {
          // User Authenticated!
          $this->createUserSession($loggedInUser);
        } else {
          $data['password_err'] = 'Password incorrect.';
          // Load View
          $this->view('users/login', $data);
        }
      } else {
        // Load View
        $this->view('users/login', $data);
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
      $this->view('users/login', $data);
    }
  }

  // Create Session With User Info
  public function createUserSession($user)
  {
    $_SESSION['user_id'] = $user->id;
    $_SESSION['user_email'] = $user->email;
    $_SESSION['user_name'] = $user->name;
    redirect('users/index');
  }

  // Logout & Destroy Session
  public function logout()
  {
    unset($_SESSION['user_id']);
    unset($_SESSION['user_email']);
    unset($_SESSION['user_name']);
    session_destroy();
    redirect('users/login');
  }

  // Check Logged In
  public function isLoggedIn()
  {
    if (isset($_SESSION['user_id'])) {
      return true;
    } else {
      return false;
    }
  }
  // =================================
  public function movies()
  {
    if (!$this->isLoggedIn()) {
      redirect('users/login');
    }

    $movies = $this->adminModel->getMovies();
    $data = [
      'movies' => $movies
    ];
    $this->view('users/movies', $data);
  }



  public function bookSingleMovie($movie_id)
  {
    if (!$this->isLoggedIn()) {
      redirect('users/login');
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $data =
        [
          'user_id' => $_SESSION['user_id'],
          'movie_id' => $movie_id,
        ];
      if ($this->userModel->bookSingleMovie($data)) {
        redirect('users/movies');
      } else {
        die('Error occurred while deleting the movie.');
      }
    } else {
      redirect('users/movies');
    }
  }

  public function bookMultipleMovie($id)
  {
    if (!$this->isLoggedIn()) {
      redirect('users/login');
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $tickets = $_POST['tickets'];

      // Retrieve movie details
      $movie = $this->userModel->getMovieById($id);
      $availableTickets = $movie->available_tickets;

      // Retrieve total tickets booked
      $totalTicketsBooked = $this->userModel->getTotalTicketsBooked($id);

      // Calculate remaining available tickets
      $remainingTickets = $availableTickets - $totalTicketsBooked;

      if ($tickets <= $remainingTickets) {
        // Sufficient tickets available, proceed with booking
        if ($this->userModel->bookMultipleMovie($id, $_SESSION['user_id'], $tickets)) {
          redirect('users/movies');
        } else {
          die('Error occurred while booking the movie.');
        }
      } else {
        die('Tickets not available. Please select a lower quantity.');
      }
    } else {
      redirect('users/movies');
    }
  }
}
