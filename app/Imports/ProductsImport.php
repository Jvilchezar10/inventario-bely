<?php

namespace App\Imports;

use App\Models\Product;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use App\Models\Category;

class ProductsImport
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

                //dd($cells);

                // Ignorar la primera fila si contiene encabezados
                if ($rowIndex === 1) {
                    continue;
                }

                // Obtener los valores de las celdas
                $cod_product = $this->validateCellValue(trim($cells[0]->getValue()));
                $category = $this->validateCellValue(trim($cells[1]->getValue()));
                $desc = isset($cells[2]) ? $this->validateCellValue(trim($cells[2]->getValue())) : null;
                $color = isset($cells[3]) ? $this->validateCellValue(trim($cells[3]->getValue())) : null;
                $size = isset($cells[4]) ? $this->validateCellValue(trim($cells[4]->getValue())) : null;
                $stock_min = isset($cells[5]) ? $this->validateIntegerValue($cells[5]->getValue()) : null;
                $stock = isset($cells[6]) ? $this->validateIntegerValue($cells[6]->getValue()) : null;
                $purchase_price = isset($cells[7]) ? $this->validateDecimalValue($cells[7]->getValue()) : null;
                $sale_price = isset($cells[8]) ? $this->validateDecimalValue($cells[8]->getValue()) : null;

                $category = Category::where('name', $category)->first();

                if (!$category) {
                    $errorMessage = "Categoría no encontrada: " . $category;
                    // Realiza alguna acción adicional aquí, como registrar el error en un archivo de registro
                    return $errorMessage;
                }

                $product = new Product([
                    'cod_product' => $cod_product,
                    'category_id' => $category->id,
                    'desc' => $desc,
                    'color' => $color,
                    'size' => $size,
                    'stock_min' => $stock_min,
                    'stock' => $stock,
                    'purchase_price' => $purchase_price,
                    'sale_price' => $sale_price,
                ]);

                $product->save();
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

    private function validateIntegerValue($value)
    {
        $intValue = filter_var($value, FILTER_VALIDATE_INT);
        return $intValue !== false && $intValue > 0 ? $intValue : null;
    }

    private function validateDecimalValue($value)
    {
        $decimalValue = filter_var($value, FILTER_VALIDATE_FLOAT);
        return $decimalValue !== false && $decimalValue > 0 ? round($decimalValue, 2) : null;
    }
}
