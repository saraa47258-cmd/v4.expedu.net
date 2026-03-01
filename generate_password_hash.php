<?php
/**
 * سكريبت لتوليد hash لكلمة المرور
 * 
 * الاستخدام من Terminal:
 * php generate_password_hash.php "كلمة_المرور_الجديدة"
 * 
 * أو قم بتشغيله مباشرة من المتصفح وأدخل كلمة المرور
 */

// إذا تم تمرير كلمة المرور من Terminal
if (isset($argv[1])) {
    $password = $argv[1];
    $hash = password_hash($password, PASSWORD_DEFAULT);
    echo "\n";
    echo "========================================\n";
    echo "كلمة المرور: $password\n";
    echo "========================================\n";
    echo "الـ Hash:\n";
    echo "$hash\n";
    echo "========================================\n";
    echo "\nانسخ هذا الـ Hash واستخدمه في ملف SQL أو في قاعدة البيانات مباشرة.\n";
    exit;
}

// إذا تم التشغيل من المتصفح
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>توليد Hash لكلمة المرور</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 50px rgba(0,0,0,0.2);
            max-width: 600px;
            width: 100%;
        }
        h1 {
            color: #667eea;
            margin-bottom: 30px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: bold;
        }
        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border 0.3s;
        }
        input:focus {
            outline: none;
            border-color: #667eea;
        }
        button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s;
        }
        button:hover {
            transform: translateY(-2px);
        }
        .result {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            border-right: 4px solid #667eea;
        }
        .hash-output {
            background: #2d3748;
            color: #48bb78;
            padding: 15px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            word-break: break-all;
            margin-top: 10px;
            direction: ltr;
            text-align: left;
        }
        .copy-btn {
            margin-top: 10px;
            background: #48bb78;
            padding: 8px 16px;
            width: auto;
            font-size: 14px;
        }
        .copy-btn:hover {
            background: #38a169;
        }
        .info {
            background: #fff3cd;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-right: 4px solid #ffc107;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 توليد Hash لكلمة المرور</h1>
        
        <div class="info">
            <strong>ملاحظة:</strong> استخدم هذا السكريبت لتوليد hash آمن لكلمة المرور يمكن استخدامه في قاعدة البيانات.
        </div>

        <form method="POST">
            <div class="form-group">
                <label for="password">كلمة المرور الجديدة:</label>
                <input type="password" id="password" name="password" required 
                       placeholder="أدخل كلمة المرور">
            </div>
            
            <button type="submit">توليد Hash</button>
        </form>

        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['password'])): ?>
            <?php
                $password = $_POST['password'];
                $hash = password_hash($password, PASSWORD_DEFAULT);
            ?>
            <div class="result">
                <h3 style="color: #48bb78; margin-bottom: 10px;">✅ تم التوليد بنجاح!</h3>
                <p><strong>كلمة المرور:</strong> <?= htmlspecialchars($password) ?></p>
                <p style="margin-top: 10px;"><strong>الـ Hash:</strong></p>
                <div class="hash-output" id="hashOutput"><?= htmlspecialchars($hash) ?></div>
                <button type="button" class="copy-btn" onclick="copyHash()">📋 نسخ الـ Hash</button>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function copyHash() {
            const hashText = document.getElementById('hashOutput').textContent;
            navigator.clipboard.writeText(hashText).then(() => {
                alert('تم نسخ الـ Hash بنجاح! ✅');
            });
        }
    </script>
</body>
</html>


