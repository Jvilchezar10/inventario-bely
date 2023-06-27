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
                $state = trim($cells[5]->getValue());

                $estadoOptions = [
                    ['value' => 'vigente', 'label' => 'Vigente'],
                    ['value' => 'retirado', 'label' => 'Retirado']
                ];

                $estadoValue = null;
                foreach ($estadoOptions as $option) {
                    if ($option['value'] === $state) {
                        $estadoValue = $option['value'];
                        break;
                    }
                }

                // Validar que todos los datos sean aceptados
                if ($this->validateData($cod_emp, $name, $last_name, $phone, $email, $estadoValue)) {
                    // Crear un nuevo objeto Employee y guardar en la base de datos
                    $employee = new Employee([
                        'cod_emp' => $cod_emp,
                        'name' => $name,
                        'last_name' => $last_name,
                        'phone' => $phone,
                        'email' => $email,
                        'state' => $estadoValue,
                    ]);

                    try {
                        $employee->save();
                    } catch (\Exception $e) {
                        throw new \Exception("Error al guardar el empleado en la base de datos. Detalles: " . $e->getMessage());
                    }
                } else {
                    throw new \Exception("Los datos del empleado no son válidos. Cod. Empleado: $cod_emp, Nombre: $name, Apellido: $last_name, Teléfono: $phone, Email: $email, Estado: $estadoValue");
                }
            }
        }

        // Cerrar el lector de Excel
        $reader->close();

        return true;
    }

    private function validateData($cod_emp, $name, $last_name, $phone, $email, $estadoValue)
    {
        // Verificar si alguno de los datos es nulo o vacío
        if (empty($cod_emp) || empty($name) || empty($last_name) || empty($phone) || empty($email) || empty($estadoValue)) {
            return false;
        }

        // Validar que el número de celular tenga 9 dígitos
        if (strlen($phone) !== 9) {
            return false;
        }

        // Realizar otras validaciones según tus requisitos (por ejemplo, validar el formato del correo electrónico)

        return true;
    }
}
