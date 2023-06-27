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
                $provider = $cells[0]->getValue();
                $DNI= $cells[1]->getValue();
                $RUC = $cells[2]->getValue();
                $phone = $cells[3]->getValue();
                $contact = $cells[4]->getValue();
                $contact_phone= $cells[5]->getValue();



                    $area = new Provider([
                        'provider' => $provider,
                        'DNI' => $DNI,
                        'RUC' => $RUC,
                        'phone' => $phone,
                        'contact' => $contact,
                        'contact_phone' => $contact_phone,
                    ]);
                    $area->save();
            }
        }

        // Cerrar el lector de Excel
        $reader->close();

        return true;
    }
}
