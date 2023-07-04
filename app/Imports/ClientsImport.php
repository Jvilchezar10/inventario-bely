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
        $hasData = false; // Variable para verificar si hay datos en el archivo

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                $rowIndex++;

                $cells = $row->getCells();

                // Ignorar la primera fila si contiene encabezados
                if ($rowIndex === 1) {
                    continue;
                }

                // Obtener los valores de las celdas
                $full_name = $cells[0]->getValue();
                $DNI = $cells[1]->getValue();
                $phone = $cells[2]->getValue();

                // Validar que el campo "DNI" tenga 9 dígitos
                if (strlen($DNI) !== 8) {
                    // Aquí puedes manejar el error, lanzar una excepción o realizar cualquier acción necesaria
                    continue; // Saltar a la siguiente fila
                }

                // Validar que el campo "phone" tenga 8 dígitos
                if (strlen($phone) !== 9) {
                    // Aquí puedes manejar el error, lanzar una excepción o realizar cualquier acción necesaria
                    continue; // Saltar a la siguiente fila
                }

                $area = new Client([
                    'full_name'  => $full_name,
                    'DNI' => $DNI,
                    'phone' => $phone,
                ]);
                $area->save();

                // Establecer la variable $hasData como verdadera si se encuentra al menos un dato en el archivo
                $hasData = true;
            }
        }

        // Cerrar el lector de Excel
        $reader->close();

        if ($hasData) {
            return true;
        } else {
            return false;
        }
    }
}

