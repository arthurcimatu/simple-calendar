<?php

function isFormValid() {
    
    $eval = true;
    
    if(!isset($_POST["submit"])) {
        return false;
    }
    
    $event_name = $_POST["event_name"];
    if(!isset($event_name) || trim($event_name) == '') {
        echo "Please Specify an Event Name <br>";
        $eval = false;
    } 
    
    $start_date = $_POST["start_date"];
    if(!isset($start_date) || trim($start_date) == '') {
        echo "Please Specify a Start Date <br>";
        $eval = false;
    }
    
    $end_date = $_POST["end_date"];
    if(!isset($end_date) || trim($end_date) == '') {
        echo "Please Specify an End Date <br>";
        $eval = false;
    }
    
    
    if(isset($start_date) && isset($end_date)) {
        $start = new DateTime($start_date);
        $end   = new DateTime($end_date);
        
        if ($start > $end) {
            echo "Start Date should not be beyond End Date <br>";
            $eval = false;
        }
    }
    
    if ((!isset($_POST["monday"])    || $_POST["monday"] == "")    &&
        (!isset($_POST["tuesday"])   || $_POST["tuesday"] == "")   &&
        (!isset($_POST["wednesday"]) || $_POST["wednesday"] == "" ) &&
        (!isset($_POST["thursday"])  || $_POST["thursday"] == "" )  &&
        (!isset($_POST["friday"])    || $_POST["friday"] == "" )    &&
        (!isset($_POST["saturday"])  || $_POST["saturday"] == "" )  &&
        (!isset($_POST["sunday"])    || $_POST["sunday"] == "")) {
        
        echo "Choose at least 1 Day of the Week <br>";
        $eval = false;
    }
    
    return $eval;
}

function isDayIncluded($days, $target) {
    foreach ($days as $day) {
        if ($day == $target) {
            return true;
        }
    } 
    return false;
}

function getDayField($day) {
    
    if (isset($_POST[$day])) {
        return $_POST[$day];
    }
    return -1;
}


function clearEvents($connection) {
    $sql = "delete from events";
    $connection->query($sql);
}

function saveEvent($connection) {
    
    if (!isFormValid()) {
        return;
    }
    
    
    clearEvents($connection);
    
    $start = new DateTime($_POST["start_date"]);
    $end   = new DateTime($_POST["end_date"]);
    $event = $_POST["event_name"];
    
    $mon = getDayField("monday");
    $tue = getDayField("tuesday");
    $wed = getDayField("wednesday");
    $thu = getDayField("thursday");
    $fri = getDayField("friday");
    $sat = getDayField("saturday");
    $sun = getDayField("sunday");
    
   
    
    $days = array($mon, $tue, $wed, $thu, $fri, $sat, $sun);
    
    $current = $start;
    
    while ($current <= $end) {
        
        
        
        $day = $current->format("w");
        if (isDayIncluded($days, $day)) {
            $sql = "INSERT INTO events (date, name) VALUES ('" . $current->format("Ymd") . "', '" . $event . "')";
            $connection->query($sql);
            
        }
        
        $current->modify("+1 day");
    }
    
}

function fill($start) {
    
    $current = $start;
    
    if ($current->format("d") == "01") {
        echo "<hr><h2>" . $current->format("M") . "&nbsp;&nbsp;&nbsp;" . $current->format("Y") . "</h2>";
    }
    
    do {
        echo "<table class='table table-condensed table-hover'><tr><td>" . $current->format("j") . "&nbsp;&nbsp;&nbsp;" . $current->format("D") . "</td></tr></table>";
        $current->modify("+1 day");
        
    } while ($current->format("d") != "01");
}

function display($connection) {
    
   
    $sql = "select * from events order by date ASC";

    $connection->query($sql);

    $result = $connection->query($sql);
   
    $db_event_dates = array();
    $db_event_name  = "";


    if ($result->num_rows < 1) {
        
      
      $start = new DateTime(); 
      
      $start_month = $start->format("M");
      $start_year  = $start->format("Y");
  
      $start_date_str = $start_year . $start->format("m") . "01";
      $start = new DateTime($start_date_str);
      
     
      
      fill($start);
      
      return;
    }

    
    while($row = $result->fetch_assoc()) {
        array_push($db_event_dates, new DateTime($row["date"]));
        $db_event_name = $row["name"];
        
    }
  
    $start_month = $db_event_dates[0]->format("M");
    $start_year = $db_event_dates[0]->format("Y");

    $start_date_str = $start_year . $db_event_dates[0]->format("m") . "01";
    
    $start = new DateTime($start_date_str);
    
    
    
    $current = $start;
    
    while (count($db_event_dates) > 0) {
        
        // display header
        if ($current->format("d") == "01") {
            echo "<hr><h2>" . $current->format("M") . "&nbsp;&nbsp;&nbsp;" . $current->format("Y") . "</h2>";
        } 
       
        $line = "";
        
        if ($current->format("Ymd") == $db_event_dates[0]->format("Ymd")) {
            
            $line = "<table class='table table-condensed table-hover'><tr><td style='background-color: #DFF0D8'>" . $current->format("j") . "&nbsp;&nbsp;&nbsp;" . $current->format("D") . "&emsp;&emsp;&emsp;" . $db_event_name . "</td></tr></table>";
            array_shift($db_event_dates); // remove 1st element
        } else {
            $line = "<table class='table table-condensed table-hover'><tr><td>" . $current->format("j") . "&nbsp;&nbsp;&nbsp;" . $current->format("D") . "</td></tr></table>";
            
        }
        
        echo $line;
        $current->modify("+1 day");
        
    }
    
    if ($current->format("d") != "01") { // fill only if not "exact"
        fill($current);
    }
}


?>




<?php




/** The name of the database */
define( 'DB_NAME', 'artcim_scal' );

/** MySQL database username */
define( 'DB_USER', 'artcim_scal' );

/** MySQL database password */
define( 'DB_PASSWORD', '776.PSpP2(' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

 
$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);

mysqli_select_db($connection, DB_NAME);

saveEvent($connection);

display($connection);

$connection->close();

?>

<hr>




