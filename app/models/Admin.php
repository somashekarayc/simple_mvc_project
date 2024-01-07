<?php
class Admin
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
    $this->db->query('INSERT INTO admins (name, email,password) 
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
    $this->db->query("SELECT * FROM admins WHERE email = :email");
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
    $this->db->query("SELECT * FROM admins WHERE email = :email");
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
    $this->db->query("SELECT * FROM admins WHERE id = :id");
    $this->db->bind(':id', $id);

    $row = $this->db->single();

    return $row;
  }
  //================================================================
  public function insertMovie($movieData)
  {
    $this->db->query('INSERT INTO movies (movie_name, movie_description, movie_cost, available_tickets, created_at) 
                      VALUES (:movie_name, :movie_description, :movie_cost, :available_tickets, :created_at)');

    $this->db->bind(':movie_name', $movieData['movie_name']);
    $this->db->bind(':movie_description', $movieData['movie_description']);
    $this->db->bind(':movie_cost', $movieData['movie_cost']);
    $this->db->bind(':available_tickets', $movieData['available_tickets']);
    $this->db->bind(':created_at', date('Y-m-d'));

    if ($this->db->execute()) {
      return true; // Insertion successful
    } else {
      return false; // Insertion failed
    }
  }
  public function getMovies()
  {
    $this->db->query('SELECT * FROM movies');
    $movies = $this->db->resultSet();
    return $movies;
  }
  public function getSingleMovies($movie_id)
  {
    $this->db->query('SELECT * FROM movies WHERE id = :id');
    $this->db->bind(':id', $movie_id);
    $row = $this->db->single();

    return $row;
  }

  public function deleteMovie($id)
  {
    $this->db->query('DELETE FROM movies WHERE id = :id');
    $this->db->bind(':id', $id);
    return $this->db->execute();
  }

  public function getMoviesWithBookingDetails()
  {

    $this->db->query('SELECT movies.*, (movies.available_tickets - SUM(bookings.tickets)) AS remaining_tickets, SUM(bookings.tickets) AS total_tickets_booked, GROUP_CONCAT(users.name SEPARATOR ", ") AS user_name, GROUP_CONCAT(users.email SEPARATOR ", ") AS user_email FROM movies LEFT JOIN bookings ON movies.id = bookings.movie_id LEFT JOIN users ON bookings.user_id = users.id GROUP BY movies.id');


    return $this->db->resultSet();
  }
}
