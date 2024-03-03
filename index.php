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
    <style>
  .row:hover {
    background-color: #f2f2f2; /* Change the color as needed */
  }
</style>
  </head>

  <body>

  <main style="max-width: 800px; margin: auto;">
  <div style="text-align:center; margin-top:20px;">

    <h1>PlantUML 練習サイト</h1>
  </div>
    <table style="width:100%; text-align:center; border: 1px solid #f2f2f2; margin-top: 50px;">
      <tr style="background-color:aqua;">
        <th>ID</th>
        <th>Title</th>
    <th>Theme</th>
    
  </tr>
  <?php foreach ($questionList as $question) : ?>
   
    <tr class="row" window.location.href = 'question.php?id=<?= $question['id'] ?>'">
        <td> <?= $question['id'] ?></td>
        <td><?= $question['title'] ?></td>
        <td><?= $question['theme'] ?></td>
        
        
      </tr>
      
    <?php endforeach; ?>
    
    
    
  </table>
  
</main>
</body>
</html>
