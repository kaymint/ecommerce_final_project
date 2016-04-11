<?php
session_start();
if(isset($_FILES["file"]["type"]))
{
    $validextensions = array("jpeg", "jpg", "png");
    $temporary = explode(".", $_FILES["file"]["name"]);
    $file_extension = end($temporary);
    if ((($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/jpeg")
        ) && ($_FILES["file"]["size"] < 500000)//Approx. 100kb files can be uploaded.
        && in_array($file_extension, $validextensions)) {
        if ($_FILES["file"]["error"] > 0)
        {
            echo "Return Code: " . $_FILES["file"]["error"] . "<br/><br/>";
        }
        else
        {
            if (file_exists("../images/uploads/" . $_FILES["file"]["name"])) {
                echo $_FILES["file"]["name"] . " <span id='invalid'><b>already exists.</b></span> ";
                $_SESSION['filepath'] = '';
            }
            else
            {
                $sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable
                $targetPath = "uploads/".$_FILES['file']['name']; // Target path where file is to be stored

                $target_dir = "../images/uploads/";
                $targetPath = $target_dir . basename($_FILES["file"]["name"]);

                if(move_uploaded_file($sourcePath,$targetPath)){
                    echo "<span id='success'>Image Uploaded Successfully...!!</span><br/>";
                    echo "<br/><b>File Name:</b> " . $_FILES["file"]["name"] . "<br>";
                    echo "<b>Type:</b> " . $_FILES["file"]["type"] . "<br>";
                    echo "<b>Size:</b> " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
                    echo "<b>Temp file:</b> " . $_FILES["file"]["tmp_name"] . "<br>";
                    echo "<b>Saved Into:</b> " . $targetPath . "<br>";

                    $_SESSION['filepath'] = "images/uploads/".basename($_FILES["file"]["name"]);

                }else{
                    echo 'failed';
                } // Moving Uploaded file

            }
        }
    }
    else
    {
        echo "<span id='invalid'>***Invalid file Size or Type***<span>";
    }
}
?>