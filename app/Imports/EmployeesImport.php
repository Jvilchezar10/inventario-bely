<?php

namespace App\Imports;

use App\Models\Employee;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class EmployeesImport
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
                $cod_emp = $cells[0]->getValue();
                $name = $cells[1]->getValue();
                $last_name = $cells[2]->getValue();
                $phone = $cells[3]->getValue();
                $email = $cells[4]->getValue();
                $state = $cells[5]->getValue();

                $estadoOptions = [
                    ['value' => 'vigente', 'label' => 'Vigente'],
                    ['value' => 'retirado', 'label' => 'Retirado']
                ];

                $estadoValue = null;
                foreach ($estadoOptions as $option) {
                    if ($option['label'] === $state) {
                        $estadoValue = $option['value'];
                        break;
                    }
                }

                // Crear un nuevo objeto Area y guardar en la base de datos
                $employee = new Employee([
                    'cod_emp' => $cod_emp,
                    'name' => $name,
                    'last_name' =>  $last_name,
                    'phone ' => $phone,
                    'email' => $email,
                    'state' => $estadoValue,
                ]);
                $employee->save();
            }
        }

        // Cerrar el lector de Excel
        $reader->close();

        return true;
    }
}
