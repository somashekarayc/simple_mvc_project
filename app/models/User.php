<?php
class User
{
  private $db;

  public function __construct()
  {
    $this->db = new Database;
  }

  // Add User / Register
  public function register($data)
  {
    // Prepare Query
    $this->db->query('INSERT INTO users (name, email,password) 
      VALUES (:name, :email, :password)');

    // Bind Values
    $this->db->bind(':name', $data['name']);
    $this->db->bind(':email', $data['email']);
    $this->db->bind(':password', $data['password']);

    //Execute
    if ($this->db->execute()) {
      return true;
    } else {
      return false;
    }
  }

  // Find USer BY Email
  public function findUserByEmail($email)
  {
    $this->db->query("SELECT * FROM users WHERE email = :email");
    $this->db->bind(':email', $email);

    $row = $this->db->single();

    //Check Rows
    if ($this->db->rowCount() > 0) {
      return true;
    } else {
      return false;
    }
  }

  // Login / Authenticate User
  public function login($email, $password)
  {
    $this->db->query("SELECT * FROM users WHERE email = :email");
    $this->db->bind(':email', $email);

    $row = $this->db->single();

    $hashed_password = $row->password;
    if (password_verify($password, $hashed_password)) {
      return $row;
    } else {
      return false;
    }
  }

  // Find User By ID
  public function getUserById($id)
  {
    $this->db->query("SELECT * FROM users WHERE id = :id");
    $this->db->bind(':id', $id);

    $row = $this->db->single();

    return $row;
  }

  public function bookSingleMovie($data)
  {
    $this->db->query('INSERT INTO users (name, email,password) 
      VALUES (:name, :email, :password)');

    // Bind Values
    $this->db->bind(':name', $data['name']);
    $this->db->bind(':email', $data['email']);
    $this->db->bind(':password', $data['password']);

    //Execute
    if ($this->db->execute()) {
      return true;
    } else {
      return false;
    }
  }

  public function bookMultipleMovie($movieId, $userId, $tickets)
  {
    $this->db->query('INSERT INTO bookings (movie_id, user_id, tickets) VALUES (:movie_id, :user_id, :tickets)');
    $this->db->bind(':movie_id', $movieId);
    $this->db->bind(':user_id', $userId);
    $this->db->bind(':tickets', $tickets);
    return $this->db->execute();
  }


  // Get movie details by ID
  public function getMovieById($id)
  {
    $this->db->query('SELECT * FROM movies WHERE id = :id');
    $this->db->bind(':id', $id);
    return $this->db->single();
  }

  // Get total tickets booked for a movie
  public function getTotalTicketsBooked($id)
  {
    $this->db->query('SELECT SUM(tickets) AS total_tickets_booked FROM bookings WHERE movie_id = :movie_id');
    $this->db->bind(':movie_id', $id);
    $result = $this->db->single();
    return $result->total_tickets_booked ? $result->total_tickets_booked : 0;
  }



  public function getMoviesWithBookingDetails()
  {

    $this->db->query('SELECT movies.*, (movies.available_tickets - SUM(bookings.tickets)) AS remaining_tickets, SUM(bookings.tickets) AS total_tickets_booked, GROUP_CONCAT(users.name SEPARATOR ", ") AS user_name, GROUP_CONCAT(users.email SEPARATOR ", ") AS user_email FROM movies LEFT JOIN bookings ON movies.id = bookings.movie_id LEFT JOIN users ON bookings.user_id = users.id WHERE user_id = :user_id GROUP BY movies.id');
    $this->db->bind(':user_id', $_SESSION['user_id']);


    return $this->db->resultSet();
  }
}
