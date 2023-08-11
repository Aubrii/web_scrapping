<?php
require('SQL.php');

if (isset($_POST['action'])) {
    if (isset($_POST['article_id'])) {
        $sql = new SQL();
        $sql->deleteById($_POST['article_id']);
    }
}
