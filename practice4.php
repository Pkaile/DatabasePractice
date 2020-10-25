<?php 
require_once 'login.php';

$call = new mysqli($hn,$un, $pw, $db);
if($call->connect_error) die("there was an  error");
if(isset($_POST['delete']) && isset($_POST['id']))
   { 
       $id = get_post($call, 'id');
       $query = "DELETE FROM contact WHERE id='$id' ";
       $result = $call->query($query);
       if(!$result) die("there was an error deleting...");
   }

   if(!empty($_POST['first_name']) &&
     !empty($_POST['last_name']) &&
     !empty($_POST['email']) &&
     !empty($_POST['comments']))
   {

     $first_name = get_post($call, 'first_name');
     $last_name = get_post($call, 'last_name');
     $email = get_post($call, 'email');
     $comments = get_post($call, 'comments');
    if(!empty($_POST['phone'])) $phone = get_post($call, 'phone');
    $query = "INSERT INTO contact(first_name, last_name, email, phone, comments)".
   
    "VALUES('$first_name', '$last_name', '$email', '$phone', '$comments')";
    $results = $call->query($query);
    if(!$results) die("There was an error submitting...");
   }
 
echo <<<_END
<form action="practice4.php" method="post" style=" margin-top:6rem; margin-bottom:6rem;"><pre>
<h1 style="text-align:center;"> Address book </h1>
<h2>add a contact</h2> 
FIRST NAME: <input type="text" name="first_name">
LAST NAME:  <input type="text" name="last_name">
EMAIL:      <input type="text" name="email"> 
PHONE:      <input type="tel" name="phone">
<textarea placeholder="your comments" style="resize:none; width:30%; margin:2rem;"  col=100 rows=10  name="comments"></textarea>
<input type="submit" value="ADD CONTACT">
</pre></form>
_END;
$query = "select * FROM contact";
$result = $call->query($query);
if(!$result) die("there was an error loading your address book, sorry!");
$rows = $result->num_rows;
for($i=0; $i < $rows; ++$i)
{
    $row = $result->fetch_array(MYSQLI_NUM);
    $r0 =htmlspecialchars($row[0]);
    $r1 =htmlspecialchars($row[1]);
    $r2 =htmlspecialchars($row[2]);
    $r3 =htmlspecialchars($row[3]);
    $r4 =htmlspecialchars($row[4]);
    $r5 =htmlspecialchars($row[5]);
echo <<<_END
<pre style=" font-size:15px;">
First Name:$r0
Last Name: $r1
Email:     $r2 
Phone:     $r3
Comments:  $r4 
<form action="practice4.php" method="post">
<input type="hidden" name="delete" value="yes">
<input type="hidden" name="id" value='$r5'>
<input type ="submit" value="DELETE CONTACT">
</form></pre>
_END;
}

$result->close();
$call->close();

function get_post($call, $var)
{ 
    return $call->real_escape_string($_POST[$var]);    
}
?>

