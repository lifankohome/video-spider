<?php
$filename = 'inform.json';

if (!empty($_GET['content']) && !empty($_GET['start']) && !empty($_GET['end'])) {
    $content = $_GET['content'];
    $start = $_GET['start'];
    $end = $_GET['end'];

    $new = json_encode(["start" => strtotime($start), "end" => strtotime($end), "content" => $content]);

    file_put_contents($filename, $new);

    header('location:inform.php');
    die();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>通知管理</title>
    <style>
        body {
            width: 80%;
            margin: 0 auto;
        }

        .inform {
            background-image: linear-gradient(135deg, #F6D242 10%, #FF52E5 100%);
            text-align: center;
            font-size: 20px;
            padding: 15px 10px;
            border-radius: 5px;
            color: #692197;
        }

        .tip {
            color: #F40;
            font-size: 14px;
        }

        input {
            width: 100%;
            height: 30px;
            display: block;
            margin: 5px;
        }
    </style>
</head>
<body>
<div>
    <h2>当前内容
        <span class="tip">
    <?php
    $inform = file_get_contents($filename);
    $inform = json_decode($inform);

    if ($inform->start > time()) {
        echo '（未显示）将于 ' . date('Y/m/d H:i:s', $inform->start) . ' 开始显示';
    } else {
        if ($inform->end > time()) {
            echo '（正在显示）将在 ' . date('Y/m/d H:i', $inform->end) . ' 停止显示';
        } else {
            echo '（已停止）已于 ' . date('Y/m/d H:i', $inform->end) . ' 停止显示';
        }
    }
    ?>
    </span>
    </h2>

    <div class="inform">
        <?php echo $inform->content; ?>
    </div>
</div>

<div>
    <h2>新通知</h2>
    <form method="get" action="inform.php">
        <label>
            通知内容
            <input type="text" placeholder="Content" name="content">
        </label>
        <label>
            开始时间
            <input type="datetime-local" placeholder="Start" name="start">
        </label>
        <label>
            结束时间
            <input type="datetime-local" placeholder="End" name="end">
        </label>
        <input type="submit">
    </form>
</div>
</body>
</html>
