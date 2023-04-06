<?php
session_start();

//Connect to SQL
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require '/home/edeister/db_portfolio.php';

if(!empty($_POST['approved'])) {
    $sql = 'UPDATE Guestbook 
            SET approved = 1
            WHERE guestID ='.$_POST['approved'].';';
    @mysqli_query($cnxn, $sql);
}

//Store in database
//Button to return to guestbook

if(!empty($_POST['guest-name'])) {
    $guest = array(
        "name" => $_POST['guest-name'],
        "message" => $_POST['guest-message'],
        "link" => $_POST['guest-link']
    );
    
    // TODO: Echo cleanly at the top of each page
    //Use this snippet:
    //if(!empty($_COOKIE['name'])) {
    //  echo $_COOKIE['name'];
    //}
    setcookie('name', $guest['name'], time()+60*60*24);

    //Insert into guestbook
    $sql = '
            INSERT INTO Guestbook (name, message, link, approved)
            VALUES ("'.$guest['name'].'", "'.$guest['message'].'", "'.$guest['link'].'", 0)';
    @mysqli_query($cnxn, $sql);
    //Get guestID to use with query string
    $sql = 'SELECT guestID FROM Guestbook
            WHERE message = "'.$guest['message'].'" AND link = "'.$guest['link'].'"';
    $result = @mysqli_query($cnxn, $sql);
    $guestID = @mysqli_fetch_assoc($result);

    //Send email
    // TODO: Use $_GET['guestID'] to display new guests for approval in Guestbook.php
    $msg = 'A new guest has filled out an application, view it below:
    https://edeister.greenriverdev.com/indiv_portfolio/guestbook.php?guest='.$guestID['guestID'];
    $msg = wordwrap($msg, 70);
    mail('deister.ethan@student.greenriver.edu', 'WHFS/cPanel: New Guest!', $msg);
    
    echo '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Title</title>
        </head>
        <body>
            <a href="guestbook.php">To Guestbook:</a>
        </body>
        </html>';

}





















