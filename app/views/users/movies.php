<?php require APPROOT . '/views/inc/header.php'; ?>


<div class="container mx-auto">
    <table class="table">
        <thead>
            <tr>
                <th>Movie Name</th>
                <th>Description</th>
                <th>Cost</th>
                <th>Available Tickets</th>
                <th>Added at</th>
                <!-- <th>Book Single Ticket</th> -->
                <th>Book Tickets</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data['movies'] as $movie) : ?>
                <tr>
                    <td><?php echo $movie->movie_name; ?></td>
                    <td><?php echo $movie->movie_description; ?></td>
                    <td><?php echo $movie->movie_cost; ?></td>
                    <td><?php echo $movie->available_tickets; ?></td>
                    <td><?php echo $movie->created_at; ?></td>
                    <!-- <td class="d-flex">
                        <div class="single d-flex mr-5">
                            <form action="<?php // echo URLROOT; ?>/users/bookSingleMovie/<?php // echo $movie->id; ?>" method="POST">
                                <button type="submit" class="btn btn-success">Book</button>
                            </form>
                        </div>
                    </td> -->
                    <td>
                        <div class="multiple d-flex">
                            <form action="<?php echo URLROOT; ?>/users/bookMultipleMovie/<?php echo $movie->id; ?>" method="POST" class="d-flex">
                                <input type="number" class="form-control" name="tickets" id="tickets" placeholder="Enter number of Tickets" required>
                                <button type="submit" class="btn btn-success">Book</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>


</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
