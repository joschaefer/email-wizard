<?php

declare(strict_types=1);

mb_internal_encoding('UTF-8');
date_default_timezone_set('Europe/Berlin');
setlocale(LC_ALL, 'en_US');
ini_set('error_reporting', '-1');
ini_set('expose_php', 'off');
header_remove('X-Powered-By');

?>
<!doctype html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Johannes Schäfer">
    <title>E-Mail Wizard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-beta1/css/bootstrap.min.css" integrity="sha512-thoh2veB35ojlAhyYZC0eaztTAUhxLvSZlWrNtlV01njqs/UdY3421Jg7lX0Gq9SRdGVQeL8xeBp9x1IPyL1wQ==" crossorigin="anonymous">
    <style>
        select:invalid {
            color: #6c757d;
        }
        .cursor-pointer {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <h1 class="mb-4">E-Mail Wizard: <span class="text-muted">Schritt 3</span></h1>
        <div class="row row-cols-4 row-cols-md-6">
            <?php

            $i = 1;

            foreach ($_POST['data'] as $data) {

                $email = $data[intval($_POST['email'])];
                $subject = str_replace($_POST['headers'], $data, trim($_POST['subject']));
                $message = str_replace($_POST['headers'], $data, trim($_POST['message']));

                echo '<div class="col pb-2 text-nowrap"><a href="mailto:' . urlencode($email) . '?' . http_build_query([
                        'subject' => $subject,
                        'body' => $message,
                    ], '', '&', PHP_QUERY_RFC3986) . '" onclick="this.style.color = \'green\';">E-Mail #' . $i . '</a></div>';
                $i++;

            }

            ?>
        </div>
    </div>
</body>
</html>
