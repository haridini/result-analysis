<?php      
    include('connection.php');  
    $username = $_POST['username'];  
    $password = $_POST['password'];  
      
        //to prevent from mysqli injection  
        $username = stripcslashes($username);  
        $password = stripcslashes($password);  
        $username = mysqli_real_escape_string($con, $username);  
        $password = mysqli_real_escape_string($con, $password);  
      
        $sql = "select *from users where username = '$username' and password = '$password'";  
        $result = mysqli_query($con, $sql);  
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);  
        $count = mysqli_num_rows($result);  
          
        if($count == 1){  
            
            echo "<h1> Login Succesfull.</h1>";
            header("Location: ../admindashboard.php ");
            echo`<h1>Login successful $username</h1>`  ;
            
        }  
        else{  
            echo '<h1> Login failed. Invalid username ' .$username.'  or password ' .$password.' </h1>';  
        }     
?>  