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
        $errores = 0;
        $validos = 0;
        $rowIndex = 0; // Variable para el índice de la fila

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                $rowIndex++;

                // Ignorar la primera fila si contiene encabezados
                if ($rowIndex === 1) {
                    continue;
                }

                // Obtener los valores de las celdas
                $cod_emp = $row->getCellAtIndex(0)->getValue();
                $name = $row->getCellAtIndex(1)->getValue();
                $last_name = $row->getCellAtIndex(2)->getValue();
                $phone = $row->getCellAtIndex(3)->getValue();
                $email = $row->getCellAtIndex(4)->getValue();
                $state = trim($row->getCellAtIndex(5)->getValue());

                $estadoOptions = [
                    ['value' => 'vigente'],
                    ['value' => 'retirado']
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
                    // Verificar si el empleado ya existe en la base de datos
                    $existingEmployee = Employee::where('cod_emp', $cod_emp)
                        ->orWhere('email', $email)
                        ->first();

                    if ($existingEmployee) {
                        $errores += 1;
                        //$reader->close();
                        //return ["Los datos del empleado ya existen. Cod. Empleado: $cod_emp, Email: $email", false];
                    } else {
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
                            $validos += 1;
                            $employee->save();
                        } catch (\Exception $e) {
                            $errores += 1;
                            //$reader->close();
                            //return ["Error al guardar el empleado en la base de datos", false];
                        }
                    }
                } else {
                    $errores += 1;
                    //$reader->close();
                    //return ["Los datos del empleado no son válidos. Cod. Empleado: $cod_emp, Nombre: $name, Apellido: $last_name, Teléfono: $phone, Email: $email, Estado: $estadoValue", false];
                }
            }
        }

        // Cerrar el lector de Excel
        $reader->close();
        if ($validos > 0) {
            return ["Registro realizados exitosamente, resultados: validos: $validos, errores: $errores", true];
        }
        return ["No se registro el excel, resultados: errores: $errores", false];
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

        // Validar el formato del correo electrónico
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }
}
