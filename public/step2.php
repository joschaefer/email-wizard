<?php

declare(strict_types=1);

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\CellIterator;
use PhpOffice\PhpSpreadsheet\Worksheet\ColumnIterator;
use PhpOffice\PhpSpreadsheet\Worksheet\RowIterator;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

mb_internal_encoding('UTF-8');
date_default_timezone_set('Europe/Berlin');
setlocale(LC_ALL, 'en_US');
ini_set('error_reporting', '-1');
ini_set('expose_php', 'off');
header_remove('X-Powered-By');

$settings = require __DIR__ . '/../vendor/autoload.php';

$worksheet = load($_FILES['list']['tmp_name']);
$import = parse($worksheet);

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
        <h1 class="mb-4">E-Mail Wizard: <span class="text-muted">Schritt 2</span></h1>
        <form action="step3.php" method="post" accept-charset="utf-8">
            <select name="email" class="form-select form-select-lg" required>
                <option value="" selected disabled hidden>E-Mail-Variable</option>
                <?php for($i=0; $i<count($import['headers']); $i++): ?>
                    <option value="<?= $i ?>"><?= htmlentities($import['headers'][$i]) ?></option>
                <?php endfor; ?>
            </select>
            <div class="form-text">Bitte gib an, welche Variable die E-Mail-Adresse der Empfänger enthält.</div>
            <div class="row my-3">
                <div class="col">
                    <input type="text" name="subject" class="form-control form-control-lg mb-3" value="" placeholder="Betreff" required>
                    <textarea name="message" id="message" class="form-control form-control-lg" placeholder="Nachricht" rows="10" required></textarea>
                </div>
                <div class="col-3 bg-light rounded p-3">
                    <h6>Variablen:</h6>
                    <ul class="list-unstyled">
                        <?php foreach($import['headers'] as $header): ?>
                            <li class="mb-1 cursor-pointer" onclick="typeInTextarea('{{ <?= htmlentities($header) ?> }}');"><code><?= htmlentities($header) ?></code></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <?php for($i=0; $i<count($import['headers']); $i++): ?>
                <input type="hidden" name="headers[<?= $i ?>]" value="{{ <?= htmlspecialchars($import['headers'][$i]) ?> }}">
            <?php endfor; ?>
            <?php for($i=0; $i<count($import['data']); $i++): ?>
                <?php for($j=0; $j<count($import['data'][$i]); $j++): ?>
                    <input type="hidden" name="data[<?= $i ?>][<?= $j ?>]" value="<?= htmlspecialchars($import['data'][$i][$j]) ?>">
                <?php endfor; ?>
            <?php endfor; ?>
            <button type="submit" class="btn btn-primary btn-lg">E-Mails erstellen</button>
        </form>
    </div>
    <script>
        function typeInTextarea(newText, el = document.getElementById('message')) {
            if (el.setRangeText) {
                el.setRangeText(newText)
            } else {
                el.focus()
                document.execCommand('insertText', false /*no UI*/, newText);
            }
        }
    </script>
</body>
</html>
<?php

/**
 * Load and use the specified spreadsheet file for parsing.
 *
 * @param string $file
 *
 * @return Worksheet
 * @throws Exception
 */
function load(string $file): Worksheet
{
    if (!is_readable($file)) {
        throw new Exception('Cannot load spreadsheet: ' . $file);
    }

    try {
        $reader = IOFactory::createReaderForFile($file);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($file);
    } catch (Exception $e) {
        throw new Exception('Cannot read spreadsheet: ' . $file);
    }

    return $spreadsheet->getSheet(0);
}

/**
 * Parse the spreadsheet file and return the headers and data.
 *
 * @param Worksheet $worksheet
 *
 * @return array
 * @throws \PhpOffice\PhpSpreadsheet\Exception
 */
function parse(Worksheet $worksheet): array
{
    $headers = [];
    $data = [];

    $rows = new RowIterator($worksheet, 1);

    foreach ($rows as $row) {

        $cells = $row->getCellIterator();

        if (0 == sizeof($headers)) {
            $cells->setIterateOnlyExistingCells(true);
            $headers = convert($cells);
            continue;
        }

        $result = convert($cells);

        if (0 !== strlen(implode($result))) {
            // Skip rows that are completely empty
            $data[] = $result;
        }
    }

    return [
        'headers' => $headers,
        'data' => $data,
    ];
}

/**
 * @param CellIterator $cells
 *
 * @return array
 */
function convert(CellIterator $cells): array
{
    $result = [];

    foreach ($cells as $cell) {
        $result[] = trim($cell->getFormattedValue());
    }

    return $result;
}
