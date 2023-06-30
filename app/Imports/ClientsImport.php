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
                $full_name = $cells[0]->getValue();
                $DNI = $cells[1]->getValue();
                $phone = $cells[2]->getValue();

                $area = new Client([
                    'full_name'  => $full_name,
                    'DNI' => $DNI,
                    'phone' => $phone,
                ]);
                $area->save();
            }
        }

        // Cerrar el lector de Excel
        $reader->close();

        return true;
    }
}
