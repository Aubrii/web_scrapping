<?php

class SQL
{
    function connection()
    {
        return new PDO("mysql:host=localhost;dbname=beeside", "root", "root");
    }

    function createTableArticle()
    {
        $conn = $this->connection();
        $sql = "CREATE TABLE IF NOT EXISTS  Article (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) ,
        site VARCHAR(255) ,
        date VARCHAR(50) ,
        theme_id INT(6) UNSIGNED,
        FOREIGN KEY (theme_id) REFERENCES Theme(id)
    )";
        $conn->exec($sql);
    }
    function filterArticlesInDatabase($filter) {
        $conn = $this->connection();

        $sql = $conn->prepare("SELECT * FROM Article WHERE title LIKE :filter");
        $sql->bindValue(':filter', '%' . $filter . '%', PDO::PARAM_STR);
        $sql->execute();

        return $conn->fetchAll(PDO::FETCH_ASSOC);
    }

    function createTableTheme()
    {
        $conn = $this->connection();
        $sql = "CREATE TABLE IF NOT EXISTS  Theme (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        theme VARCHAR(255) NOT NULL
    )";

        $conn->exec($sql);
    }

    function insertArticle($article)
    {

        $conn = $this->connection();
        $sql = "INSERT INTO Article (
        title,
        site,
        date
    ) VALUES ('$article->title','$article->site', '$article->date')";
        $conn->exec($sql);
    }

    function getAllArticle()
    {
        $conn = $this->connection();
        $sql = "SELECT * FROM Article";
        return $conn->query($sql);
    }

    function deleteById($id)
    {
        $conn = $this->connection();
        $sql = "DELETE FROM Article WHERE id = '$id'";
        $conn->query($sql);
    }

    function getArticleById($id)
    {
        $conn = $this->connection();
        $sql = "SELECT * FROM Article WHERE id = '$id'";
        return $conn->query($sql);
    }

    function updateArticleById($id)
    {
        $conn = $this->connection();

        $sql = "UPDATE Article SET title = :title, site = :site, date= :date WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':title', $_POST['title']);
        $stmt->bindParam(':site', $_POST['site']);
        $stmt->bindParam(':date', $_POST['date']);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

    }
}