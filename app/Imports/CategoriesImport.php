<?php

namespace App\Imports;

use App\Models\Category;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Exception;

class CategoriesImport
{
    // public function import($filePath)
    // {
    //     $reader = ReaderEntityFactory::createXLSXReader();

    //     // Abrir el archivo Excel
    //     $reader->open($filePath);

    //     $rowIndex = 0; // Variable para el índice de la fila
    //     $errorMessages = [];

    //     try {
    //         foreach ($reader->getSheetIterator() as $sheet) {
    //             foreach ($sheet->getRowIterator() as $row) {
    //                 $rowIndex++;

    //                 $cells = $row->getCells();

    //                 // Ignorar la primera fila si contiene encabezados
    //                 if ($rowIndex === 1) {
    //                     continue;
    //                 }

    //                 try {
    //                     // Obtener los valores de las celdas
    //                     $id = $cells[0]->getValue();
    //                     $name = $cells[1]->getValue();
    //                     $state = trim($row->getCellAtIndex(2)->getValue());

    //                     // Agregar tus validaciones aquí
    //                     if (empty($id) || empty($name) || empty($state)) {
    //                         throw new Exception("Registro incompleto en la fila $rowIndex");
    //                     }

    //                     $estadoOptions = [
    //                         ['value' => 'vigente'],
    //                         ['value' => 'descontinuado']
    //                     ];

    //                     $estadoValue = null;
    //                     foreach ($estadoOptions as $option) {
    //                         if ($option['value'] === $state) {
    //                             $estadoValue = $option['value'];
    //                             break;
    //                         }
    //                     }

    //                     $category = new Category([
    //                         'id' => $id,
    //                         'name' => $name,
    //                         'state' => $estadoValue,
    //                     ]);
    //                     $category->save();
    //                 } catch (Exception $e) {
    //                     $errorMessages[] = $e->getMessage();
    //                 }
    //             }
    //         }
    //     } catch (Exception $e) {
    //         $reader->close(); // Cerrar el lector de Excel en caso de error
    //         throw $e; // Relanzar la excepción original
    //     }

    //     // Cerrar el lector de Excel
    //     $reader->close();

    //     // if (!empty($errorMessages)) {
    //     //     $errorMessage = implode("\n", $errorMessages);
    //     //     throw new Exception("Se encontraron errores al importar los registros:\n$errorMessage");
    //     // }

    //     return true;
    // }
    public function import($filePath)
    {
        $reader = ReaderEntityFactory::createXLSXReader();

        // Abrir el archivo Excel
        $reader->open($filePath);

        $rowIndex = 0; // Variable para el índice de la fila
        $errorMessages = [];


        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                $rowIndex++;

                $cells = $row->getCells();

                // Ignorar la primera fila si contiene encabezados
                if ($rowIndex === 1) {
                    continue;
                }

                // Obtener los valores de las celdas
                $id = $this->validateCellValue($cells[0]->getValue());
                $name = $this->validateCellValue(trim($cells[1]->getValue()));
                $state = $this->validateCellValue(trim(($cells[2])->getValue()));

                // Agregar tus validaciones aquí
                if (empty($id) || empty($name) || empty($state)) {
                    throw new Exception("Registro incompleto en la fila $rowIndex");
                }

                $existingCategory = Category::where('id', $id)->first();
                if ($existingCategory) {
                    continue; // Saltar los datos existentes
                }

                $estadoOptions = [
                    ['value' => 'vigente'],
                    ['value' => 'descontinuado']
                ];

                $estadoValue = null;
                foreach ($estadoOptions as $option) {
                    if ($option['value'] === $state) {
                        $estadoValue = $option['value'];
                        break;
                    }
                }

                $category = new Category([
                    'id' => $id,
                    'name' => $name,
                    'state' => $estadoValue,
                ]);
                $category->save();
            }
        }
        $reader->close(); // Cerrar el lector de Excel en caso de error

        return true;
    }

    private function validateCellValue($value)
    {
        return !empty($value) ? $value : null;
    }
}
