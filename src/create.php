<?php
$host = 'localhost';
$username = 'root';
$password = '';
$conn = new mysqli($host, $username, $password);

$cipher = 'AES-128-CBC';
$key = 'thebestsecretkey';

if ($conn->connect_error) {
  die('Connection failed: ' . $conn->connect_error);
}

if (isset($_POST['delete-everything'])) {
  $sql = 'DROP DATABASE CreateAccount;';
  if (!$conn->query($sql) === TRUE) {
    die('Error dropping database: ' . $conn->error);
  }
}

$sql = 'CREATE DATABASE IF NOT EXISTS CreateAccount;';
if (!$conn->query($sql) === TRUE) {
  die('Error creating database: ' . $conn->error);
}

$sql = 'USE CreateAccount;';
if (!$conn->query($sql) === TRUE) {
  die('Error using database: ' . $conn->error);
}
$sql = 'CREATE TABLE IF NOT EXISTS Account (
id int NOT NULL AUTO_INCREMENT,
iv varchar(256) NOT NULL,
Username varchar(256) NOT NULL,
Password varchar(256) NOT NULL,
PRIMARY KEY (id));';
if (!$conn->query($sql) === TRUE) {
  die('Error creating table: ' . $conn->error);
}
?>
<html>
<head>
<title>create Account</title> </head>
<body>
<h1>Create Account</h1>
<?php
if (isset($_POST['Username'])) {
  $iv = random_bytes(16);
  $escaped_Username = $conn -> real_escape_string($_POST['Username']);
  $encrypted_Username = openssl_encrypt($escaped_Username, $cipher, $key, OPENSSL_RAW_DATA, $iv);
  $iv_hex = bin2hex($iv);
  $Username_hex = bin2hex($encrypted_Username);
}
if (isset($_POST['Password'])) {
  $iv = random_bytes(16);
  $escaped_Password = $conn -> real_escape_string($_POST['Password']);
  $hash = password_hash($password, PASSWORD_DEFAULT);
  $encrypted_Password = openssl_encrypt($escaped_Password, $cipher, $key, OPENSSL_RAW_DATA, $iv, $hash);
  $iv_hex = bin2hex($iv);
  $Password_hex = bin2hex($encrypted_Password);
}
  $sql = "INSERT INTO Account (iv, Username, Password) VALUES ('$iv_hex', '$Username_hex' ,'$Password_hex')";
  if ($conn->query($sql) === TRUE) {
    echo '<p><i>New note added!</i></p>';
  } else {
    die('Error creating note: ' . $conn->error);
  }
?>
<h2>Username</h2>

<form method="post">
  <input type="text" id="Username" name="Username" size="64"><br><br>

<h2>Password</h2>

<form method="post">
  <input type="text" id="Password" name="Password" size="64"><br><br>
  <button type="submit" name="new-note">Create</button>
</form>
</form>
</form>
</form>

<?php
$sql = "SELECT id, iv, Username, Password FROM Account";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $id = $row['id'];
    $iv = hex2bin($row['iv']);
    $Username = hex2bin($row['Username']);
    $unencrypted_Username = openssl_decrypt($Username, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    $Password = hex2bin($row['Password']);
    $unencrypted_Password = openssl_decrypt($Password, $cipher, $key, OPENSSL_RAW_DATA, $iv);
  }
  echo '</table>';
} else {
  echo '<p>There are no notes!</p>';
}
?>
</body>
</html>
