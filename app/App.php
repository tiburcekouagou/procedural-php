<?php

declare(strict_types = 1);

// Your code
function getTransactionFiles(string $dirPath): array {
    $files = [];

    foreach (scandir($dirPath) as $file) {
        if (is_dir($file)) {
            continue;
        } 

        $files[] = FILES_PATH . $file;

        return $files;
    }
}

function getTransactions(string $fileName): array {
    if (!file_exists($fileName)) {
        trigger_error('File "' . $fileName . '" does not exists.', E_USER_ERROR);
    }
    
    $file = fopen($fileName, 'r');

    $transactions = [];

    fgetcsv($file);
    
    while(($transaction = fgetcsv($file)) !== false) {
        $transactions[] = extractTransaction($transaction);
    }

    return $transactions;
}

function extractTransaction(array $transactionRow): array {
    [$date, $checkNumber, $description, $amount] = $transactionRow;
    
    $amount =(float) str_replace(['$', ','], '', $amount);
    
    return [
        'date' => $date,
        'checkNumber' => $checkNumber,
        'description' => $description,
        'amount' => $amount,
    ];
}