<?php


// 引数である元コードをPOSTで受け取る
$code = $_POST['code'];
$format = $_POST['format'];


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

    if (file_exists($folderPath . '/userCode.png')) {
        unlink($folderPath . '/userCode.png');
    }
    if (file_exists($folderPath . '/userCode.svg')) {
        unlink($folderPath . '/userCode.svg');
    }


    $format_command = "";
    if ($format === "svg") {
        $format_command = "-tsvg ";
    }

    //コマンドをたたいてUMLのイメージを作成
    $command = 'java -jar plantUMLsource/plantuml-1.2024.3.jar ' . $format_command . $filePath;
    shell_exec($command);

    if ($format === "svg") {

        header('Content-Type: image/svg+xml');
        header('Content-Disposition: attachment; filename="userCode.svg"');
        readfile($folderPath . "/userCode.svg");

        // unlink($folderPath . '/userCode.svg');
    } else if ($format === "png") {

        header('Content-Type: image/png');
        header('Content-Disposition: attachment; filename="userCode.png"');
        readfile($folderPath . "/userCode.png");

        // unlink($folderPath . '/userCode.png');
    } else if ($format === "txt") {

        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename="userCode.txt"');

        echo $code;
    } else {
        echo $folderPath . '/userCode.png';
    }



    // 入力コードファイルを削除
    unlink($filePath);
}
