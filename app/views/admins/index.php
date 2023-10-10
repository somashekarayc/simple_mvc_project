<?php require APPROOT . '/views/inc_admin/header.php'; ?>


<div class="container mx-auto">
    <form action="<?php echo URLROOT; ?>/admins/storeMovieData" method="POST">
        <div class="form-group">
            <label for="movie_name">Movie Name</label>
            <input type="text" class="form-control" id="movie_name" name="movie_name" required>
        </div>
        <div class="form-group">
            <label for="movie_description">Movie Description</label>
            <textarea class="form-control" id="movie_description" name="movie_description" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label for="movie_cost">Movie Cost</label>
            <input type="text" class="form-control" id="movie_cost" name="movie_cost" required>
        </div>
        <div class="form-group">
            <label for="available_tickets">Available Tickets</label>
            <input type="number" class="form-control" id="available_tickets" name="available_tickets" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    
</div>
<?php require APPROOT . '/views/inc_admin/footer.php'; ?>