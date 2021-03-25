<?php
    include 'DB.php';
    $db= new DB();
?>

<link rel="stylesheet" href="style.css">


<form action="request.php" method="post">
    <label for="comment-name">Name</label>
    <input type="text" name="name" id="comment-name">

    <label for="comment-message">Comment</label>
    <textarea name="comment" id="comment-message" cols="30" rows="10"></textarea>
    <button type="submit">add comment</button>
</form>

<?php
    echo $db->last_error;

    $db->displayAll();
?>