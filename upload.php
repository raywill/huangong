<!DOCTYPE html>
<html>
<body>
<?php
$target_dir = "cache/upload-" . rand(10,80) . "-";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if file already exists
// if (file_exists($target_file)) {
//   echo "Sorry, file already exists.";
//   $uploadOk = 0;
// }

// Check file size
if ($_FILES["fileToUpload"]["size"] > 5000000000) {
  echo "Sorry, your file is too large.";
  $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "viz") {
  echo "Sorry, only viz files are allowed.";
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  echo "Sorry, your file was not uploaded.";
} else {
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
      $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http';
      $currentUrl = $protocol . '://' . $_SERVER['HTTP_HOST'] . "/perf/" . $target_file;
      header('location:' . "./index.php?perf_file=" . urlencode($currentUrl) . "#perf_file");
      echo "<p>The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded. ";
      // echo "Please copy the link to the URL input box.</p>";
      // echo "<p><kbd>"  . $currentUrl . "</kbd>";
      // echo "<p> <a href='./index.php?perf_file=" . urlencode($currentUrl) . "#perf_file'>Go to paste</a></p>";
  } else {
      echo "Sorry, there was an error uploading your file.";
  }
}
?>
</body>
</html>
