<?php

?>

<div class="container">
    <form id="user-form" method="POST">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" name="name" class="form-control">
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" class="form-control">
        </div>
        <div class="form-group">
            <label for="phone">Phone Number:</label>
            <input type="tel" name="phone" class="form-control">
        </div>
        <div class="form-group">
            <label for="city">City:</label>
            <input type="text" name="city" required " class=" form-control">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>


<div id="response" class="alert alert-dismissible"></div>

<?php