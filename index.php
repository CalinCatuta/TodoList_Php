<?php include 'config/database.php'; ?>
<?php
// Fetch todos from the database
$sql = 'SELECT * FROM todo';
$result = mysqli_query($conn, $sql);
$todo = mysqli_fetch_all($result, MYSQLI_ASSOC);

$body = '';
$bodyErr = '';

// Handle form submission
if(isset($_POST['submit'])){
    // Validation body
    if(empty($_POST['body'])){
        $bodyErr = 'Text is required';
    } else{
        $body = filter_input(INPUT_POST, 'body', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        // Add to db if body is not empty
        if(empty($bodyErr)){
            $sql = "INSERT INTO todo (body) VALUES ('$body')";
            if(mysqli_query($conn, $sql)){
                // Redirect to prevent form resubmission
                header('Location: index.php');
                exit(); // Exit to prevent further execution
            } else {
                // Display error if insertion fails
                echo 'Error' . mysqli_error($conn);
            }
        }
    }
}

// Handle deletion
if(isset($_POST['delete'])){
    $delete_id = $_POST['delete'];
    $sql = "DELETE FROM todo WHERE id='$delete_id'";
    if(mysqli_query($conn, $sql)){
        // Redirect to prevent form resubmission
        header('Location: index.php');
        exit(); // Exit to prevent further execution
    } else {
        // Display error if deletion fails
        echo 'Error' . mysqli_error($conn);
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List</title>
</head>
<body>
   <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST" >
       <textarea name="body" id="body" placeholder="Create todo"></textarea>
       <input type="submit" name="submit" value="Send">
       <?php if(!empty($bodyErr)): ?>
            <span style="color: red;"><?php echo $bodyErr; ?></span>
       <?php endif; ?>
   </form>
   <?php if(empty($todo)): ?>
       <p>There is no Todo</p>
   <?php else: ?>
       <ul>
           <?php foreach($todo as $item): ?>
               <li>
                   <span class="todo-text"><?php echo $item['body']; ?></span>
                   <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST" style="display: inline;">
                       <input type="hidden" name="delete" value="<?php echo $item['id']; ?>">
                       <button type="submit">X</button>
                   </form>
                   
               </li>
           <?php endforeach; ?>
       </ul>
   <?php endif; ?>


</body>
</html>
