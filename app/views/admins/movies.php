<?php require APPROOT . '/views/inc_admin/header.php'; ?>


<div class="container mx-auto">
<table class="table">
    <thead>
        <tr>
            <th>Movie Name</th>
            <th>Description</th>
            <th>Cost</th>
            <th>Available Tickets</th>
            <th>Added at</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data['movies'] as $movie): ?>
        <tr>
            <td><?php echo $movie->movie_name; ?></td>
            <td><?php echo $movie->movie_description; ?></td>
            <td><?php echo $movie->movie_cost; ?></td>
            <td><?php echo $movie->available_tickets; ?></td>
            <td><?php echo $movie->created_at; ?></td>
            <td>
                <form action="<?php echo URLROOT; ?>/admins/deleteMovie/<?php echo $movie->id; ?>" method="POST">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
          
            </td>
            <td>
            <form action="<?php echo URLROOT; ?>/admins/editMovie/<?php echo $movie->id; ?>" method="POST">
                    <button type="submit" class="btn btn-secondary">Edit</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

    
</div>
<?php require APPROOT . '/views/inc_admin/footer.php'; ?>