<?php

// JSON データの読み込み
$jsonString = file_get_contents('index.json');
// JSON データを PHP の配列にデコード
$questionList = json_decode($jsonString, true);

// GET パラメータから ID を取得
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

// questionListから該当idのデータを引き抜く
$selectedQuestion = null;
foreach ($questionList as $question) {
    if ($question["id"] === $id) {
        $selectedQuestion = $question;
    }
}

//answerUMLを表示させる
$answerCode = $selectedQuestion["uml"];

// フォルダのパス
$folderPath = 'temp';

// フォルダが存在しない場合は作成
if (!is_dir($folderPath)) {
    mkdir($folderPath, 0777, true);
}

// ファイルパス（フォルダパスを含む）
$filePath = $folderPath . '/answerCode.txt';

// ファイルに内容を書き込む
$result = file_put_contents($filePath, $answerCode);

// 書き込みの結果を確認
if ($result === false) {
    echo "コードの読み込み失敗しました。";
} else {

    //コマンドをたたいてUMLのイメージを作成
    $command = 'java -jar plantUMLsource/plantuml-1.2024.3.jar ' . $filePath;
    $output = shell_exec($command);

    echo $output;

    // 入力コードファイルを削除
    // unlink($filePath);
}

?>
<!DOCTYPE html>
<html>

<head>
    <!-- Monaco Editorのスタイルシートを読み込む -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.35.0/min/vs/editor/editor.main.css" />
</head>

<body>
    <div>
        <h1><?= $id . ":" . $selectedQuestion['title'] ?></h1>
    </div>
    <div style="width: 100%; display: flex">
        <div>
            <p></p>
        </div>
        <div id="editor" style="width: 33.3%; height: 600px; border: 1px solid slategray; position:relative">
            <div id="placeholder" style="position: absolute; top:0; left:0; z-index:1; color:slategray">ここにコードを書いてください</div>
        </div>
        <div id="preview" style="width: 33.3%; height: 600px;border: 1px solid slategray;">
        <img id="userImage" src="" alt="userUMLImg"  style="display: none; max-width: 90%; min-width: 100px; height: 500px; object-fit: contain;">

        </div>
        <div id="answer" style="width: 33.3%; height: 600px;border: 1px solid slategray; ">
            <div>

                <button type="button" id="answerUMLButton">AnswerUML</button>
                <button type="button" id="answerCodeButton">AnswerCode</button>
            </div>
            <div style="width: 100%; height: 100%; padding:10px;">

                <pre id="answerCodeHtml" style="width: 100%; height: 100%; display:none;"><?= $answerCode ?></pre>
                <img id="answerImage" src="/temp/answerCode.png" alt="<?= $title ?>" style="max-width: 90%; min-width: 100px; height: 500px; object-fit: contain;" >
            </div>

        </div>

    </div>

    <form id="editorForm" action="submit.php" method="post">
        <input type="hidden" id="editorContent" name="editorContent" />

        <button type="submit">Download</button>
    </form>

    <!-- Monaco Editorのスクリプトを読み込む -->
    <script src="https://cdn.jsdelivr.net/npm/marked@3.0.7/marked.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.20.0/min/vs/loader.min.js"></script>
    <script>
        document.getElementById('answerCodeButton').addEventListener('click', function() {
            var img = document.getElementById('answerImage');
            img.style.display = 'none'; // 
            var code = document.getElementById('answerCodeHtml');
            code.style.display = 'inline';

        });
        document.getElementById('answerUMLButton').addEventListener('click', function() {
            var img = document.getElementById('answerImage');
            img.style.display = 'inline'; // 
            var code = document.getElementById('answerCodeHtml');
            code.style.display = 'none';

        });
    </script>
    <script>
        require.config({
            paths: {
                vs: "https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.20.0/min/vs",
            },
        });
        require(["vs/editor/editor.main"], function() {
            var editor = monaco.editor.create(document.getElementById("editor"), {
                value: "",
                language: "markdown",
            });

            let timeout;
            editor.onDidChangeModelContent(function() {

                // ユーザーがエディターに記入したらプレイスホルダーを消す
                var placeholder = document.getElementById("placeholder");
                if (editor.getValue()) {
                    placeholder.style.display = "none";
                } else {
                    placeholder.style.display = "block";
                }

                //ユーザーが3秒手を止めたらUML図を作成
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    // ユーザーが入力を停止したときの処理

                    fetch('generateUML.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: 'code=' + encodeURIComponent(editor.getValue()),
                        })
                        .then(response => response.text())
                        .then(data => {
                            // PHPスクリプトからの応答を処理
                            console.log("data",data);
                            var img = document.getElementById('userImage');
                            
                            if(data){
                                img.src = data + '?t=' + new Date().getTime();
                                img.style.display = 'inline'; 
                            }else{
                                img.style.display = 'none';
                            }

                        })
                        .catch(error => {
                            // console.error('Error:', error);
                        });


                    // let markdown = editor.getValue();
                    // console.log(markdown);
                    // let html = marked(markdown);
                    // document.getElementById("preview").innerHTML = html;


                    // console.log('Input stopped');
                }, 300);

            });
        });
    </script>
</body>

</html>