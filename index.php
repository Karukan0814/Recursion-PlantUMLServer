<?php

// JSON データの読み込み
$jsonString = file_get_contents('index.json');
// JSON データを PHP の配列にデコード
$questionList = json_decode($jsonString, true);

// デコードされたデータの使用
// foreach ($data as $item) {
//     echo "ID: " . $item['id'] . "<br>";
//     echo "Title: " . $item['title'] . "<br>";
//     echo "Theme: " . $item['theme'] . "<br>";
//     echo "UML: <pre>" . htmlspecialchars($item['uml']) . "</pre><br>";
// }
?>

<!DOCTYPE html>
<html>
  <head>
    <!-- Monaco Editorのスタイルシートを読み込む -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.35.0/min/vs/editor/editor.main.css"
    />
  </head>

  <body>

    <ul>
    <?php foreach ($questionList as $question) : ?>
        <li><a href="question.php?id=<?= $question['id'] ?>"><?=  $question['title']?></a></li>
    <?php endforeach; ?>


    </ul>
  </body>
</html>
