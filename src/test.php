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
  $sql = 'DROP DATABASE StudentAccount;';
  if (!$conn->query($sql) === TRUE) {
    die('Error dropping database: ' . $conn->error);
  }
}

$sql = 'CREATE DATABASE IF NOT EXISTS StudentAccount;';
if (!$conn->query($sql) === TRUE) {
  die('Error creating database: ' . $conn->error);
}

$sql = 'USE StudentAccount;';
if (!$conn->query($sql) === TRUE) {
  die('Error using database: ' . $conn->error);
}
$sql = 'CREATE TABLE IF NOT EXISTS notes (
id int NOT NULL AUTO_INCREMENT,
iv varchar(256) NOT NULL,
StudentNumber varchar(256) NOT NULL,
PhoneNumber varchar(256) NOT NULL,
Email varchar(256) NOT NULL,
DateOfBirth varchar(256) NOT NULL,
MedicalInfo varchar(256) NOT NULL,
DoctorInfo varchar(256) NOT NULL,
NextOfKin varchar(256) NOT NULL,
Image varchar(256) NOT NULL,
PRIMARY KEY (id));';
if (!$conn->query($sql) === TRUE) {
  die('Error creating table: ' . $conn->error);
}
?>
<html>
<head>
<title>Student Account</title> </head>
<body>
<h1>Student Account</h1>
<?php
if (isset($_POST['StudentNumber'])) {
  $iv = random_bytes(16);
  $escaped_StudentNumber = $conn -> real_escape_string($_POST['StudentNumber']);
  $encrypted_StudentNumber = openssl_encrypt($escaped_StudentNumber, $cipher, $key, OPENSSL_RAW_DATA, $iv);
  $iv_hex = bin2hex($iv);
  $StudentNumber_hex = bin2hex($encrypted_StudentNumber);
}
if (isset($_POST['PhoneNumber'])) {
  $iv = random_bytes(16);
  $escaped_PhoneNumber = $conn -> real_escape_string($_POST['PhoneNumber']);
  $encrypted_PhoneNumber = openssl_encrypt($escaped_PhoneNumber, $cipher, $key, OPENSSL_RAW_DATA, $iv);
  $iv_hex = bin2hex($iv);
  $PhoneNumber_hex = bin2hex($encrypted_PhoneNumber);
}
if (isset($_POST['Email'])) {
  $iv = random_bytes(16);
  $escaped_Email = $conn -> real_escape_string($_POST['Email']);
  $encrypted_Email = openssl_encrypt($escaped_Email, $cipher, $key, OPENSSL_RAW_DATA, $iv);
  $iv_hex = bin2hex($iv);
  $Email_hex = bin2hex($encrypted_Email);
}
if (isset($_POST['DateOfBirth'])) {
  $iv = random_bytes(16);
  $escaped_DateOfBirth = $conn -> real_escape_string($_POST['DateOfBirth']);
  $encrypted_DateOfBirth = openssl_encrypt($escaped_DateOfBirth, $cipher, $key, OPENSSL_RAW_DATA, $iv);
  $iv_hex = bin2hex($iv);
  $DateOfBirth_hex = bin2hex($encrypted_DateOfBirth);
}
if (isset($_POST['MedicalInfo'])) {
  $iv = random_bytes(16);
  $escaped_MedicalInfo = $conn -> real_escape_string($_POST['MedicalInfo']);
  $encrypted_MedicalInfo = openssl_encrypt($escaped_MedicalInfo, $cipher, $key, OPENSSL_RAW_DATA, $iv);
  $iv_hex = bin2hex($iv);
  $MedicalInfo_hex = bin2hex($encrypted_MedicalInfo);
}
if (isset($_POST['DoctorInfo'])) {
  $iv = random_bytes(16);
  $escaped_DoctorInfo = $conn -> real_escape_string($_POST['DoctorInfo']);
  $encrypted_DoctorInfo = openssl_encrypt($escaped_DoctorInfo, $cipher, $key, OPENSSL_RAW_DATA, $iv);
  $iv_hex = bin2hex($iv);
  $DoctorInfo_hex = bin2hex($encrypted_DoctorInfo);
}
if (isset($_POST['NextOfKin'])) {
  $iv = random_bytes(16);
  $escaped_NextOfKin = $conn -> real_escape_string($_POST['NextOfKin']);
  $encrypted_NextOfKin = openssl_encrypt($escaped_NextOfKin, $cipher, $key, OPENSSL_RAW_DATA, $iv);
  $iv_hex = bin2hex($iv);
  $NextOfKin_hex = bin2hex($encrypted_NextOfKin);
}
if (isset($_POST['Image'])) {
  $iv = random_bytes(16);
  $escaped_Image = $conn -> real_escape_string($_POST['Image']);
  $encrypted_Image = openssl_encrypt($escaped_Image, $cipher, $key, OPENSSL_RAW_DATA, $iv);
  $iv_hex = bin2hex($iv);
  $Image_hex = bin2hex($encrypted_Image);
}
  $sql = "INSERT INTO notes (iv, StudentNumber, PhoneNumber, Email, DateOfBirth, MedicalInfo, DoctorInfo, NextOfKin, Image) VALUES ('$iv_hex', '$StudentNumber_hex' ,'$PhoneNumber_hex','$Email_hex','$DateOfBirth_hex','$MedicalInfo_hex','$DoctorInfo_hex','$NextOfKin_hex', '$Image_hex')";
  if ($conn->query($sql) === TRUE) {
    echo '<p><i>New note added!</i></p>';
  } else {
    die('Error creating note: ' . $conn->error);
  }
?>
<h2>Student Number</h2>

<form method="post">
  <input type="text" id="StudentNumber" name="StudentNumber" size="64"><br><br>

<h2>Phone Number</h2>

<form method="post">
  <input type="number" id="PhoneNumber" name="PhoneNumber" size="64"><br><br>

<h2>Email</h2>

<form method="post">
  <input type="email" id="Email" name="Email" size="64"><br><br>

<h2>Date of Birth</h2>

<form method="post">
  <input type="date" id="DateOfBirth" name="DateOfBirth" size="64"><br><br>

<h2>Medical Info</h2>

<form method="post">
  <input type="text" id="MedicalInfo" name="MedicalInfo" size="64"><br><br>

<h2>Doctor Info</h2>

<form method="post">
  <input type="text" id="DoctorInfo" name="DoctorInfo" size="64"><br><br>

<h2>Next of Kin</h2>

<form method="post">
  <input type="text" id="NextOfKin" name="NextOfKin" size="64"><br><br>

<h2>Image</h2>

<form method="post">
  <input type="file" id="Image" name="Image" size="64"><br><br>
  <button type="submit" name="new-note">Create</button>
</form>
</form>
</form>
</form>

<?php
$sql = "SELECT id, iv, StudentNumber, PhoneNumber,Email, DateOfBirth, MedicalInfo, DoctorInfo, NextOfKin, Image FROM notes";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $id = $row['id'];
    $iv = hex2bin($row['iv']);
    $StudentNumber = hex2bin($row['StudentNumber']);
    $unencrypted_StudentNumber = openssl_decrypt($StudentNumber, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    $PhoneNumber = hex2bin($row['PhoneNumber']);
    $unencrypted_PhoneNumber = openssl_decrypt($PhoneNumber, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    $Email = hex2bin($row['Email']);
    $unencrypted_Email = openssl_decrypt($Email, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    $DateOfBirth = hex2bin($row['DateOfBirth']);
    $unencrypted_DateOfBirth = openssl_decrypt($DateOfBirth, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    $MedicalInfo = hex2bin($row['MedicalInfo']);
    $unencrypted_MedicalInfo = openssl_decrypt($MedicalInfo, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    $DoctorInfo = hex2bin($row['DoctorInfo']);
    $unencrypted_DoctorInfo = openssl_decrypt($DoctorInfo, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    $NextOfKin = hex2bin($row['NextOfKin']);
    $unencrypted_NextOfKin = openssl_decrypt($NextOfKin, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    $Image = hex2bin($row['Image']);
    $unencrypted_Image = openssl_decrypt($Image, $cipher, $key, OPENSSL_RAW_DATA, $iv);
  }
  echo '<img src="data:Image/jpeg;base64,'.base64_encode($Image).'"/>';
  echo '</table>';
} else {
  echo '<p>There are no notes!</p>';
}
?>
</body>
</html>
