<?php
require 'Article.php';
require 'SQL.php';

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js"></script>

    <title>Document</title>
</head>
<body>

<div class="d-flex flex-wrap">
    <form method="post">
        <input type="text" name="filtre_article">
        <button class="btn btn-primary btn-sm remove">Search</button>
    </form>
    <div class="d-flex flex-wrap">

        <?php foreach (displayData() as $article) : ?>

            <div class="card" id="<?php echo $article->id ?>" style="width: 17rem; margin: 5px">
                <img src="..." class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $article->title ?> </h5>
                    <p class="card-text"><?php echo $article->date ?></p>
                    <!--            <p class="card-text">--><?php //echo $article->site ?><!--</p>-->
                    <a href="<?php echo $article->site ?>"></a>
                    <p class="card-text"><?php echo $article->id ?></p>
                    <form method="post">
                        <button class="btn btn-danger btn-sm remove" name="delete">Delete</button>
                        <input type="hidden" name="article_id" value="<?php echo $article->id ?>">
                    </form>
                    <!--                <a href="#" class="btn btn-primary">Go somewhere</a>-->
                </div>
            </div>
        <?php endforeach; ?>


        <?php
        $sql = new SQL();
        $sql->createTableTheme();
        $sql->createTableArticle();
        if (isset($_POST['filtre_article'])) {
            var_dump($sql->filterArticlesInDatabase($_POST['filtre_article']));
        }
        function extractUrl1x($string)
        {
            $position = strpos($string, "1x");
            if ($position !== false) {
                return trim(substr($string, 0, $position));
            }
            return false;
        }


        function getArticle()
        {
            $html = file_get_contents("https://news.google.com");
            $e_commerce = new DOMDocument();
            libxml_use_internal_errors(true);
            $sql = new SQL();
            if (!empty($html)) {
                $e_commerce->loadHTML($html);
                libxml_clear_errors();
                $e_commerce_path = new DOMXPath($e_commerce);
                $queryTitle = "//h4[@class = 'gPFEn']";
                $querySite = "//div[@class = 'MCAGUe']";
                $queryDate = "//time[@class = 'hvbAAd']";
                $resultTitle = $e_commerce_path->query($queryTitle);
                $resultSite = $e_commerce_path->query($querySite);
                $resultDate = $e_commerce_path->query($queryDate);
                $result = $e_commerce_path->query($querySite);
                var_dump($result);
                $listArticle = [];
                foreach ($result as $index => $item) {
                    $article = new Article();
                    $article->title = $resultTitle[$index]->nodeValue;
                    $article->date = $resultDate[$index]->nodeValue;
                    if ($result[$index]->firstElementChild->attributes[1]->nodeValue)
                        $article->site = extractUrl1x($result[$index]->firstElementChild->attributes[1]->nodeValue);
                    array_push($listArticle, $article);
                }
                foreach ($listArticle as $article) {
                    $sql->insertArticle($article);
                }
            }
        }

        function displayData()
        {
            $sql = new SQL();
            $result = $sql->getAllArticle();
            $listArticles = array();
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $article = new Article();
                $article->id = $row['id'];
                $article->title = $row['title'];
                $article->date = $row['date'];
                $article->site = $row['site'];
                array_push($listArticles, $article);
            }
            return $listArticles;
        }

        displayData();
        ?>
        <script>

            $(document).ready(function () {
                $(".remove").click(removeRecord);
            });

        </script>
</body>
</html>