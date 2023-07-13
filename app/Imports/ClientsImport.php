<?php

namespace App\Imports;

use App\Models\Client;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class ClientsImport
{
    public function import($filePath)
    {
        $reader = ReaderEntityFactory::createXLSXReader();

        // Abrir el archivo Excel
        $reader->open($filePath);

        $rowIndex = 0; // Variable para el índice de la fila

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                $rowIndex++;

                $cells = $row->getCells();

                // Ignorar la primera fila si contiene encabezados
                if ($rowIndex === 1) {
                    continue;
                }

                // Obtener los valores de las celdas
                $full_name = $this->validateCellValue(trim($cells[0]->getValue()));
                $DNI = $this->validateDNI($cells[1]->getValue());
                $phone = $this->validatePhone($cells[2]->getValue());

                $client = new Client([
                    'full_name'  => $full_name,
                    'DNI' => $DNI,
                    'phone' => $phone,
                ]);
                $client->save();
            }
        }

        // Cerrar el lector de Excel
        $reader->close();

        return false;
    }

    private function validateCellValue($value)
    {
        return !empty($value) ? $value : null;
    }

    private function validateDNI($value)
    {
        if (preg_match('/^\d{8}$/', $value)) {
            return $value;
        } else {
            return null;
        }
    }

    private function validatePhone($value)
    {
        if (preg_match('/^\d{9}$/', $value)) {
            return $value;
        } else {
            return null;
        }
    }
}
