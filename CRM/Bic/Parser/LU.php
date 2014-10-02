<?php
/*-------------------------------------------------------+
| Project 60 - Little BIC extension                      |
| Copyright (C) 2014                                     |
| Author: B. Endres (endres -at- systopia.de)            |
| http://www.systopia.de/                                |
+--------------------------------------------------------+
| This program is released as free software under the    |
| Affero GPL license. You can redistribute it and/or     |
| modify it under the terms of this license which you    |
| can read by viewing the included agpl.txt or online    |
| at www.gnu.org/licenses/agpl.html. Removal of this     |
| copyright header is strictly prohibited without        |
| written permission from the original author(s).        |
+--------------------------------------------------------*/

require_once 'CRM/Bic/Parser/Parser.php';
require_once 'dependencies/PHPExcel.php';

/**
 * Abstract class defining the basis for national bank info parsers
 */
class CRM_Bic_Parser_LU extends CRM_Bic_Parser_Parser {

  static $page_url = 'http://www.abbl.lu/download/15222/iban-bic-codes-updated-on-30-september-2014.xlsx';
  static $country_code = 'LU';

  public function update() {
    // First, download the file
    $file_name = sys_get_temp_dir() . '/lu-banks.xls';
    $downloaded_file = $this->downloadFile(CRM_Bic_Parser_LU::$page_url);
    file_put_contents($file_name, $downloaded_file);
    unset($downloaded_file);

    // Automatically detect the correct reader to load for this file type
    $excel_reader = PHPExcel_IOFactory::createReaderForFile($file_name);

    // Set reader options
    $excel_reader->setReadDataOnly();
    //$excel_reader->setLoadSheetsOnly(array("BIC-lijst"));

    // Read Excel file
    $excel_object = $excel_reader->load($file_name);
    $excel_rows = $excel_object->getActiveSheet()->toArray();

    // Process Excel data
    $skip_lines = 2;
    $banks[] = array();
    foreach($excel_rows as $excel_row) {
      $skip_lines -= 1;
      if ($skip_lines >= 0) continue;

      // Process row
      $bank = array(
        'value' => $excel_row[1],
        'name' => str_replace(' ', '', $excel_row[2]),
        'label' => $excel_row[0],
        'description' => ''
      );
      $banks[] = $bank;
    }

    // clean up before importing
    unset($excel_rows);
    unset($excel_object);
    unset($excel_reader);
    unlink($file_name);

    // Finally, update DB
    return $this->updateEntries(CRM_Bic_Parser_LU::$country_code, $banks);
  }

  /*
   * Extracts the National Bank Identifier from an IBAN.
   */
  public function extractNBIDfromIBAN($iban) {
    return array(
      substr($iban, 4, 3)
    );
  }

}