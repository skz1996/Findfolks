<?php
session_start();
require_once("config/db.php");
//$_SESSION["creategroup_GroupName"] = $_POST['creategroup_GroupName'];
//$_SESSION["creategroup_Description"] = $_POST['creategroup_Description'];
$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$joingroup_GroupName = $connection->real_escape_string(strip_tags($_POST['joingroup_GroupName'], ENT_QUOTES));
$joingroup_GroupId = $connection->real_escape_string(strip_tags($_POST['joingroup_GroupId'], ENT_QUOTES));


        $connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if (!$connection->set_charset("utf8")) {
            $_SESSION['JoinGroupErrorMsg2'] = $connection->error;
        }
        if (!$connection->connect_errno) {
          $user = $connection->real_escape_string(strip_tags($_SESSION['user_name'], ENT_QUOTES));
            $sql = "SELECT * FROM belongs_to WHERE group_id = ? AND username = ?";
            $check1 = $connection->prepare($sql);
            $check1->bind_param("is", $joingroup_GroupId,$user);
            $check1->execute();
            $check1->store_result();
            if ($check1->num_rows == 1) {
                $_SESSION['JoinGroupErrorMsg2'] = "You've already joined the group";
                header("Location:groupPage.php?group_page_groupid=".$joingroup_GroupId."");
            }else{
            $sql2 = "INSERT INTO belongs_to (group_id, username, authorized)
                      VALUES(?,?,?)";
            $query_group_member_insert = $connection->prepare($sql2);
            $autho = 0;
            $query_group_member_insert->bind_param("isi", $joingroup_GroupId,$user,$autho);
            $query_group_member_insert->execute();
            if($query_group_member_insert){
              $_SESSION['JoinGroupErrorMsg2'] = "Success! You have joined the group.";
            }else{
              $_SESSION['JoinGroupErrorMsg2'] = "Error, please try again";
            }
          }
        } else {
            $_SESSION['JoinGroupErrorMsg'] = "Sorry, no database connection.";
        }


    header("Location:groupPage.php?group_page_groupid=".$joingroup_GroupId."");

?>
