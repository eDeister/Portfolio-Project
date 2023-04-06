<?php
session_start();

//Connect to SQL
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require '/home/edeister/db_portfolio.php';

//Get approved guests
$sql = '
        SELECT * FROM Guestbook
        WHERE approved = 1;';
$result = @mysqli_query($cnxn, $sql);

$approvedGuests = array();

while($row = @mysqli_fetch_assoc($result)) {
    array_push($approvedGuests, array(
            "name" => $row['name'],
            "message" => $row['message'],
            "link" => $row['link']
        ));
}

if(!empty($_GET['guest'])) {
    //Get new guest (from query string in email)
    $sql = '
            Select * FROM Guestbook
            WHERE guestID = '.$_GET['guest'].';';
    
    $result = @mysqli_query($cnxn, $sql);
    $newGuest = @mysqli_fetch_assoc($result);
}


//Echo most of the page content
echo '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Title</title>
    </head>
    <body>
        <form method="POST" action="send.php">
            <label>Input your info below to be entered into the guestbook!</label>
            <input type="text" required id="guest-name" name="guest-name" placeholder="Name:">
            <input type="text" required id="guest-message" name="guest-message" placeholder="Message:">
            <input type="text" required id="guest-link" name="guest-link" placeholder="Link:" >
            <input type="submit" value="Submit">
        </form>
        <table>
            <thead>
                <tr>
                    <th>Guest Name</th>
                    <th>Message</th>
                    <th>Link</th>
                </tr>
            </thead>
            <tbody>';
            

// Iterate over approved guests and display rows
for($i = 0; $i < sizeof($approvedGuests); $i++) {
    echo '      <tr>
                    <td>'.$approvedGuests[$i]['name'].'</td>
                    <td>'.$approvedGuests[$i]['message'].'</td>
                    <td><a href="'.$approvedGuests[$i]['link'].'">'.$approvedGuests[$i]['link'].'</a></td>
                </tr>';
}

//Display new guest for approval
if(!empty($newGuest)) {
    echo '      
                <tr>
                    <td></td>
                    <td>Approve new guest?<td>
                    <td></td>
                </tr>
                <tr>
                    <form method="POST" action="send.php">
                        <td>
                            <input type="radio" name="approved" id="approved" value="'.$_GET['guest'].'">
                            <label for="approve">Yes</label>
                        </td>
                        <td>
                            <input type="radio" name="reject" id="reject">
                            <label for="reject">No</label>
                        </td>
                        <td>
                            <input type="submit" value="submit">
                        </td>
                    </form>
                </tr>    
                    
                <tr>
                    <td>'.$newGuest['name'].'</td>
                    <td>'.$newGuest['message'].'</td>
                    <td><a href="'.$newGuest['link'].'">'.$newGuest['link'].'</a></td>
                </tr>';
}


echo '      </tbody>
        </table>
    </body>
    </html>';