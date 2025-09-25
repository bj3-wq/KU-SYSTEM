    <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>LECTURE MONITORING FORM</title>
            <link rel="stylesheet" href="form.css">
        </head>
    <body id="feed">

        
    <?php
        $name = $_POST["name"];
        $regno = $_POST["regno"];
        $date = $_POST["date"];
        $stime = $_POST ["stime"];
        $etime = $_POST["etime"];
        $course = $_POST["course"];
        $cunit = $_POST["course-unit"];
        $code = $_POST["code"];
        $lname = $_POST["lname"];
        $lat = $_POST["lat"];
        $long = $_POST["long"];
        $loc = ("$lat , $long");
         

        // CONNECTION TO THE DATABASE
        /*
            We use the filter function to capture user input.
            The filter sanitize is applied to remove tags and code special characters.
            This code protects against basic XSS attacks and ensures the input is safe to use.
        */
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $regno = filter_input(INPUT_POST, 'regno', FILTER_SANITIZE_STRING);
        $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
        $stime = filter_input(INPUT_POST, 'stime', FILTER_SANITIZE_STRING);
        $etime = filter_input(INPUT_POST, 'etime', FILTER_SANITIZE_STRING);
        $course = filter_input(INPUT_POST, 'course', FILTER_SANITIZE_STRING);
        $cunit = filter_input(INPUT_POST, 'course-unit', FILTER_SANITIZE_STRING);
        $code = filter_input(INPUT_POST, 'code', FILTER_SANITIZE_STRING);
        $lname = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_STRING);

        // We now set variables to connect the database. This tells our script, which database to connect to through which host
        $host = "localhost";
        $dbusername = "root";
        $dbpassword = "";
        $dbname = "kampala_university";

        // We then initialize the new mysqli object that allows us to connect to the database using the above parameters.
        $con = new mysqli($host, $dbusername, $dbpassword, $dbname);

        /*
            The $host specifies the parameter where the database is hosted i.e localhost. Since we're running it locally.
            We then after handle the potential connection errors and prepare the sql statement ___stmt, for inserting data into the table.
            If an error is found, the script should terminate and display an error message inluding the error number and descriptiion
        */
        if($con -> connect_error){
            die('Connection Error!(' .$con->connect_errno.')' .$con->connect_error);
        } else {
            $stmt = $con->prepare ("INSERT INTO attendance_form(Student_Name, Student_Number, Date, Start_Time, End_Time, Programme, Course_Unit, Course_Code, Lecturer_Name, Location) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ? )");
        }

        /*
            We use the prepare method to prepare the mysqli object to create a prepare statement that helps to prevent insecure injection of data by separating the insecure code from the data.
            However, it's important to check if the preparation process was successful.
            If the preparation fails, then the script should terminate and display the error message. i.e
        */
        if ($stmt === false){
            die ('Prepare failed: ' .$con-> error);
        }

        // We thereafter bind our parameters to the prepare statement to ensure the data we're entering in the database is secure and well formatted. i.e
        $stmt->bind_param("ssssssssss", $name, $regno, $date, $stime, $etime, $course, $cunit, $code, $lname, $loc);

        /*
            We can now execute the statement and prepare the results.
            Give feedback to the user that the registration was successfully submitted.
        */
        if ($stmt -> execute()){
            echo "<h2>Attendance Successfully Submited</h2>";
            echo "<p><b>Name: &nbsp; &nbsp;</b><u> $name</u></P>";
            echo "<p><b>Student No.: &nbsp; &nbsp; </b><u> $regno</u></P>";
            echo "<p><b>Date: &nbsp; &nbsp; </b><u> $date</u></P>";
            echo "<p><b>Time In: &nbsp; &nbsp; </b><u> $stime</u></P>";
            echo "<p><b>Time Out: &nbsp; &nbsp; </b><u> $etime</u></P>";
            echo "<p><b>Course: &nbsp; &nbsp; </b><u> $course</u></P>";
            echo "<p><b>Course Unit: &nbsp; &nbsp; </b><u> $cunit</u></P>";
            echo "<p><b>Course Code: &nbsp; &nbsp; </b><u> $code</u></P>";
            echo "<p><b>Lecturer's Name: &nbsp; &nbsp; </b><u> $lname</u></P>";
            echo "<p><b>Location: &nbsp; &nbsp; </b><u> $lat, $long</u></P>";
            echo "<p><a href='form.php'><b>Back to form</b></a</P>";
        } else {
            echo "Error! Try Again. " .$stmt -> error;
        }
        
        // We then end our program and close the statement and db connection to ensure the resources are properly released. 
        // This maintains a resource mgt and keep the application running smoothly.
        $stmt -> close();
        $con -> close();
    ?>

    </body>
    </html>