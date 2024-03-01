<?php


// 引数である元コードをPOSTで受け取る
$code = $_POST['code'] ;

// フォルダのパス
$folderPath = 'temp';

// フォルダが存在しない場合は作成
if (!is_dir($folderPath)) {
    mkdir($folderPath, 0777, true);
}

// ファイルパス（フォルダパスを含む）
$filePath = $folderPath . '/userCode.txt';

// ファイルに内容を書き込む
$result = file_put_contents($filePath, $code);

// 書き込みの結果を確認
if ($result === false) {
    echo false;
} else {
    unlink($folderPath . '/userCode.png');

    //コマンドをたたいてUMLのイメージを作成
    $command = 'java -jar plantUMLsource/plantuml-1.2024.3.jar ' . $filePath;
    $output = shell_exec($command);

    // 入力コードファイルを削除
    unlink($filePath);
}

//TODO ファイルパスを返却
echo $folderPath . '/userCode.png';
// echo $output;
?>