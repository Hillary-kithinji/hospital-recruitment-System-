<?php

session_start();
include "../config/db.php";

$result = mysqli_query(
    $conn,
    "SELECT * FROM jobs
     ORDER BY id DESC"
);

?>

<h2>Manage Jobs</h2>

<table border="1" cellpadding="10">

<tr>
    <th>ID</th>
    <th>Title</th>
    <th>Department</th>
    <th>Status</th>
</tr>

<?php while($job =
      mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?= $job['id']; ?></td>

<td><?= $job['title']; ?></td>

<td><?= $job['department']; ?></td>

<td><?= $job['status']; ?></td>

</tr>

<?php } ?>

</table>