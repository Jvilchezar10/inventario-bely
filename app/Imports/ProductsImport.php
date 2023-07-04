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
                $cod_product = $cells[0]->getValue();
                $category = $cells[1]->getValue();
                $desc = $cells[2]->getValue();
                $color = $cells[3]->getValue();
                $size = $cells[4]->getValue();
                $stock_min = $cells[5]->getValue();
                $stock = $cells[6]->getValue();
                $purchase_price = $cells[7]->getValue();
                $sale_price = $cells[8]->getValue();

                try {
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
                } catch (\Throwable $th) {
                    $errorMessage = $th->getMessage();

                    // Mostrar el mensaje de error en la consola del navegador
                    echo "<script>console.log('Error: " . $errorMessage . "');</script>";

                    // Aquí puedes realizar acciones adicionales, como registrar el error en un archivo de registro
                    return $errorMessage;
                }
            }
        }

        // Cerrar el lector de Excel
        $reader->close();

        return true;
    }
}
