<?php

namespace App\Imports;

use App\Models\Provider;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class ProvidersImport
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
                $provider = $this->validateCellValue(trim(($cells[0]->getValue())));
                $DNI = $this->validateDNI($cells[1]->getValue());
                $RUC = $this->validateRUC($cells[2]->getValue());
                $phone = $this->validatePhone($cells[3]->getValue());
                $contact = $this->validateCellValue(trim($cells[4]->getValue()));
                $contact_phone = $this->validatePhone($cells[5]->getValue());

                $provider = new Provider([
                    'provider' => $provider,
                    'DNI' => $DNI,
                    'RUC' => $RUC,
                    'phone' => $phone,
                    'contact' => $contact,
                    'contact_phone' => $contact_phone,
                ]);
                $provider->save();
            }
        }

        // Cerrar el lector de Excel
        $reader->close();

        return true;
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

    private function validateRUC($value)
    {
        if (preg_match('/^\d{11}$/', $value)) {
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
