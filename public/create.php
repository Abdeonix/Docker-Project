<?php
require_once "config.php";
 
// Defining and initializing variables
$name = $age = $cgpa = "";
$name_err = $age_err = $cgpa_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }
    
    // Validate age
    $input_age = trim($_POST["age"]);
    if(empty($input_age)){
        $age_err = "Please enter the age.";
    } elseif(!ctype_digit($input_age)){
        $age_err = "Please enter a positive integer value for age.";
    } else{
        $age = $input_age;
    }


        // Validate cgpa
    $input_cgpa = trim($_POST["cgpa"]);
    if(empty($input_cgpa)){
        $cgpa_err = "Please enter the cgpa.";     
    } elseif(!preg_match("/^\d+(\.\d+)?$/", $input_cgpa)){
        $cgpa_err = "Please enter a valid cgpa.";
    } else{
        $cgpa = $input_cgpa;
    }


    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($age_err) && empty($cgpa_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO students (name, age, cgpa) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sis", $param_name, $param_age, $param_cgpa);
            
            // Set parameters
            $param_name = $name;
            $param_age = $age;
            $param_cgpa = $cgpa;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        
            // Close statement
            mysqli_stmt_close($stmt);
        } else {
            echo "Error: " . mysqli_error($link);
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
    <title>Create Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Create Record</h2>
                    </div>
                    <p>Please fill this form and submit to add student record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                            <span class="help-block"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($age_err)) ? 'has-error' : ''; ?>">
                            <label>Age</label>
                            <input type="text" name="age" class="form-control" value="<?php echo $age; ?>">
                            <span class="help-block"><?php echo $age_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($cgpa_err)) ? 'has-error' : ''; ?>">
                            <label>CGPA</label>
                            <input type="text" name="cgpa" class="form-control" value="<?php echo $cgpa; ?>">
                            <span class="help-block"><?php echo $cgpa_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
