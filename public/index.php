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
        <h1 class="mb-4">E-Mail Wizard: <span class="text-muted">Schritt 1</span></h1>
        <form action="step2.php" method="post" accept-charset="utf-8" enctype="multipart/form-data">
            <input type="file" name="list" class="form-control form-control-lg mb-3" placeholder="Empfängerliste" accept=".csv, .ods, .xls, .xlsx" required>
            <button type="submit" class="btn btn-primary btn-lg">Empfängerliste einlesen</button>
        </form>
    </div>
</body>
</html>
