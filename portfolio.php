<?php
//Test comment
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require '/home/edeister/db_portfolio.php';

echo '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Portfolio</title>
        </head>
        <body>
            <div class="container-fluid"';

//Print project info
for ($i = 0; $i < 4; $i++) {
    //Create empty string which will be added to and then echoed
    $print = '';
    //Create new row for every other project
    if ($i % 2 == 0) {
        $print .= '<div class="row">';
    }
    //Create new div defining the portion of the screen that a project will take up
    $print .= '<div class="col-sm-12 col-md-6">';
    //Get project info (name, details, duration, associated class)
    $proj_query = "
        SELECT * FROM Projects
        WHERE projID = '".($i+1)."';";

    //Get list of project-language associations
    $langID_query = "
        SELECT langID FROM Proj_Lang
        WHERE projID = '".($i+1)."'";
    $langID_result = @mysqli_query($cnxn, $langID_query);

    //For each association
    $langNames = array();
    while($row = @mysqli_fetch_assoc($langID_result)) {
        //Get the name of the language
        $lang_query = "
                SELECT name FROM Languages
                WHERE langID = (".$row['langID'].");";
        $lang_result = @mysqli_query($cnxn, $lang_query);
        $lang_name = @mysqli_fetch_assoc($lang_result);
        //Store name in array
        array_push($langNames, $lang_name['name']);
    }

    //Fetch and print project info
    $proj_result = @mysqli_query($cnxn, $proj_query);
    $proj_row = mysqli_fetch_assoc($proj_result);
    $proj_name = $proj_row['name'];
    $details = $proj_row['details'];
    $duration = $proj_row['duration'];
    $class = $proj_row['class'];
    $print .= '<ul>
                        <li>'.$proj_name.'</li>
                        <li>'.$details.'</li>
                        <li>'.$duration.'</li>
                        <li>'.$class.'</li>
                        <ul>';

    //For each language
    for($j = 0; $j < sizeof($langNames); $j++) {
        //Add list element containing the name
        $print .=   '    <li>'.$langNames[$j].'</li>';
    }
    $print .= '     </ul>
                    </ul>
                </div>';

    //End the div for each row
    if ($i % 2 == 0) {
        $print .= '</div>';
    }

    //Print out current project + bootstrap divs
    echo $print;
}

//Echo the end of the html page
echo '
            </div>
            <a href="guestbook.php">To Guestbook:</a>
        </body>
        </html>';











