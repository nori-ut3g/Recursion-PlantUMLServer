<?php
// コードベースのファイルのオートロード
spl_autoload_extensions(".php");
spl_autoload_register(function ($class) {
    $base_dir = __DIR__ . '/../';
    $file = $base_dir . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

$id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

try {
    $problem = \Models\Problems\ORM::getProblem($id);
} catch (Exception $e) {
    header("Location: problems.php");
    exit();
}


?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ID:<?php echo $problem->getID() . " " . $problem->getTitle(); ?></title>
    <link rel="stylesheet" href="../node_modules/monaco-editor/min/vs/editor/editor.main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body>

<h2>ID:<?php echo $problem->getID() . " " . $problem->getTitle(); ?></h2>
<div style="display: flex;">
    <div id="editor-container" style="width: 33%; height: 600px; border: 1px solid grey">
    </div>

    <div style="width: 33%; height: 600px; border: 1px solid grey; overflow: auto;">
        <div id="preview"></div>
    </div>
    <div style="width: 33%; height: 600px; border: 1px solid grey; overflow: auto;">
        <div>
            <button onclick="showTab('answerUML')">Answer UML</button>
            <button onclick="showTab('answerCode')">Answer Code</button>
        </div>
        <div id="preview" style="display: none;"></div>
        <div id="answerUML" style="display: none;"></div>
        <div id="answerCode" style="display: none;">
            <pre><?php echo htmlentities($problem->getUML()); ?></pre>
        </div>
    </div>
</div>
<a href="problems.php" style="display: inline-block; padding: 10px 20px; background-color: #f1f1f1; text-decoration: none; color: black; margin: 10px 0;">戻る</a>

<script src="../node_modules/monaco-editor/min/vs/loader.js"></script>
<script>
    showTab('answerUML');
    updateUML(<?php echo json_encode($problem->getUML()); ?>, 'answerUML');
    let editor;
    require.config({ paths: { 'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.20.0/min/vs' }});
    require(['vs/editor/editor.main'], function() {
        editor = monaco.editor.create(document.getElementById('editor-container'), {
            value: '\'write uml code',
            language: 'plaintext'
        });

        updateUML(editor.getValue(), 'preview');

        const debouncedUpdate = debounce(umlCode => updateUML(umlCode, 'preview'), 1000);
        editor.onDidChangeModelContent(function() {
            debouncedUpdate(editor.getValue());
        });

    });

    function showTab(tabName) {
        const tabs = ['answerUML', 'answerCode'];
        tabs.forEach(tab => {
            document.getElementById(tab).style.display = tab === tabName ? 'block' : 'none';
        });
    }
    function updateUML(umlCode, id) {
        fetch('../API/api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `uml=${encodeURIComponent(umlCode)}`
        })
            .then(response => response.text())
            .then(svgContent => {
                const previewDiv = document.getElementById(id);
                previewDiv.innerHTML = svgContent;
                previewDiv.style.display = 'block';
            })
            .catch(error => {
                console.error("There was an error:", error);
            });
    }

    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            const context = this;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), wait);
        };
    }

</script>

</body>
</html>