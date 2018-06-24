<?php
// Include config file

$link = mysqli_connect("localhost", "root", "", "terraold");
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";
// Processing form data when form is submitted
$remoteip = $_SERVER['HTTP_X_FORWARDED_FOR'];
$now = new DateTime();
$timenow = $now->format('Y-m-d H:i:s');      // Unix Timestamp -- Since PHP 5.3
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = 'Please enter username.';
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST['password']))){
        $password_err = 'Please enter your password.';
    } else{
        $password = trim($_POST['password']);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            /* Password is correct, so start a new session and
                            save the username to the session */
                            session_start();
                            $_SESSION['username'] = $username;   
                            $_SESSION['start'] = time(); // Taking now logged in time.
                              // Ending a session in 30 minutes from the starting time.
                            $_SESSION['expire'] = $_SESSION['start'] + (1 * 60);
                            header("location: index.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = 'The information you entered was not valid.';
                          	
$statement = $odb->prepare('INSERT INTO login_attempts (ip,date) VALUES (:ip, :date)');
$statement->execute(array('ip' => $remoteip, 'date' => $timenow));
                       }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = 'The information you entered was not valid.';
                          	
$statement = $odb->prepare('INSERT INTO login_attempts (ip,date) VALUES (:ip, :date)');
$statement->execute(array('ip' => $remoteip, 'date' => $timenow));
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
                                      	
$statement11 = $odb->prepare('INSERT INTO login_attempts (ip,date) VALUES (:ip, :date)');
$statement11->execute(array('ip' => $remoteip, 'date' => $timenow));
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
  <center>
    <div class="wrapper">
        <h2>Login</h2>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username"class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
        </form>
    </div>    
   </center>
</body>
</html>