<?php
$db_host = "localhost";
$db_database = "guestbook";
$db_user = "Your_Database_Username";
$db_pass = "Your_Database_Password";

// connect to database
$db_conn = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_database, $db_conn);

$gb_entries = ""; // the string we'll append entries to

// if form is submitted, then insert into database
if (!empty($_POST["submit"])) {
    $username = $_POST["frmName"];
    $email = $_POST["frmEmail"];
    $comment = $_POST["frmComment"];
    $date = Date("Y-m-d h:i:s");

    $sql_insert = "INSERT INTO entries(username, email, comment, date_added) VALUES('$username', '$email', '$comment', '$date')";

    mysql_query($sql_insert);
    $num_rows = mysql_affected_rows();

    // See if insert was successful or not
    if ($num_rows > 0) {
        $ret_str = "Your guestbook entry was successfully added.";
    } else {
        $ret_str = "Your guestbook entry was NOT successfully added.";
    }

    // append success/failure message
    $gb_entries .= "<p>$ret_str</p><br />";
}

$sel_select = "SELECT username, email, comment, DATE_FORMAT(date_added, '%m-%d-%y %H:%i') date_added FROM entries";
$result = mysql_query($sel_select);
while ($get_row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    $username = $get_row["username"];
    $email = $get_row["email"];
    $comment = $get_row["comment"];
    $date = $get_row["date_added"];

    $gb_entries .= "<p>$comment</p><p>Posted on $date by <a href=\"mailto:$email\">$username</a><hr />";
}

// cleanup
mysql_free_result($result);
mysql_close($db_conn);
?>
<HTML>
<HEAD>
    <TITLE>Php Guestbook</TITLE>
</HEAD>
<BODY>
    <? echo $gb_entries; ?>

    <form action="<?php echo $PHP_SELF;?>" method="POST">
        <table border="0">
            <tr>
                <td>Name</td>
                <td><input type="text" name="frmName" value="" size="30" maxlength="50"></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><input type="text" name="frmEmail" value="" size="30" maxlength="100"></td>
            </tr>
            <tr>
                <td>Comment</td>
                <td><textarea name="frmComment" rows="5" cols="30"></textarea></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" name="submit" value="submit">
                    <input type="reset" name="reset" value="reset"></td>
            </tr>
        </table>
    </form>
</BODY>
</HTML>
