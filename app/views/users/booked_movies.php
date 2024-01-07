<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container mx-auto">
    <!-- Your HTML code for the movies table -->
    <table class="table table-info">
        <thead>
            <tr class="table-danger text-nowrap">
                <th>Movie Name</th>
                <th>Description</th>
                <th>Cost</th>
                <th>Total Tickets</th>
                <th>Total Tickets Booked</th>
                <th>Remaining Tickets</th>
                <th>Booked By</th>
                <th>User's Email</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data['movies'] as $movie) : ?>
                <tr class="">
                    <td><?php echo $movie->movie_name; ?></td>
                    <td><?php echo $movie->movie_description; ?></td>
                    <td><?php echo $movie->movie_cost; ?></td>
                    <td><?php echo $movie->available_tickets; ?></td>
                    <td><?php echo $movie->total_tickets_booked; ?></td>
                    <td><?php echo $movie->remaining_tickets; ?></td>
                    <td><?php echo $movie->user_name; ?></td>
                    <td><?php echo $movie->user_email; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>


<?php require APPROOT . '/views/inc/footer.php'; ?>
<!-- //<?php //echo ($movie->available_tickets - $movie->tickets); 
        ?> -->