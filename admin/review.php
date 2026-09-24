<?php

include "../config/db.php";

$applications = mysqli_query(
    $conn,
    "SELECT
        applications.*,
        users.fullname,
        jobs.title
    FROM applications

    JOIN users
    ON applications.user_id = users.id

    JOIN jobs
    ON applications.job_id = jobs.id"
);

while($row =
      mysqli_fetch_assoc(
      $applications)){
?>

<h4>
<?= $row['fullname']; ?>
</h4>

<p>
<?= $row['title']; ?>
</p>

<p>
<?= $row['status']; ?>
</p>

<a href="review.php?id=
<?= $row['id']; ?>">
Review
</a>

<hr>

<?php } ?>