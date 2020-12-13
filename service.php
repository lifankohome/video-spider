<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

if (isset($_GET['feedback'])) {
    $path = 'Cinema/feedback.txt';

    $info = $_SERVER["QUERY_STRING"];
    $info = substr($info, 0, 200);

    $opt = ['产品问题', '播放不了', '信息错误', '其他问题'];

    $body = '<div><b>类型：</b>' . $opt[$_GET['Opt']] . '</div>';
    $body .= '<div><b>留言：</b>' . $_GET['Msg'] . '</div>';
    $body .= '<div><b>联系方式：</b>' . $_GET['Num'] . '</div>';
    $body .= '<div><b>链接：</b><a href="' . $_GET['Link'] . '">' . $_GET['Link'] . '</a></div>';

    $res = send('收到新的反馈消息', $body);

    $info .= $res ? '&Send=OK' : '&Send=Fail';
    echo file_put_contents($path, $info . "\n", FILE_APPEND) ? 1 : 0;
} else {
    echo 'Video-Spider Service!';
}

function send($subject, $body)
{
    // Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);
    $mail->CharSet = $mail::CHARSET_UTF8;

    $config = file_get_contents('config.json');
    $config = json_decode($config, true);

    try {
        //Server settings
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host = $config['smtp-Host'];                                // Set the SMTP server to send through
        $mail->SMTPAuth = true;                                     // Enable SMTP authentication
        $mail->Username = $config['smtp-Username'];                       // SMTP username
        $mail->Password = $config['smtp-Password'];                       // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port = $config['smtp-Port'];                                          // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom('479846095@qq.com', '影视爬虫');
        $mail->addAddress('lifankohome@163.com');           // Add a recipient

        // Content
        $mail->isHTML(true);                                 // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body = $body;

        return $mail->send();
    } catch (Exception $e) {
        return false;
    }
}
