<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Http\Response;
use App\Imports\ProductsImport;

class ProductController extends Controller
{
    function __construct()
    {
        // Middleware para los permisos
        $this->middleware('permission:product-list|product-create|product-edit|product-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:product-create', ['only' => ['store']]);
        $this->middleware('permission:product-edit', ['only' => ['update']]);
        $this->middleware('permission:product-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $productId = 0;
        $columns = [
            'id',
            'cod producto',
            'categoria', 'categoria_id',
            'descripción',
            'color',
            'talla',
            'stock min',
            'stock',
            'precio compra', 'precio venta',
            'creado en', 'actualizado en', 'opciones'
        ];
        $data = [];
        return view('admin.product', compact('productId', 'columns', 'data'));
    }

    public function getData(Request $request)
    {
        try {
            if ($request->ajax()) {
                //$products = Product::with('category')->get(); // usar with para con eloquent extraer mediante un join
                $products = Product::all();
                $data = $this->transformProducts($products);

                return response()->json(['data' => $data], Response::HTTP_OK);
            } else {
                throw new \Exception('Invalid request.');
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    private function transformProducts($products)
    {
        return $products->map(function ($product) {
            return [
                'id' => $product->id,
                'cod producto' => $product->cod_product,
                'categoria_id' =>  $product->category->id,
                'categoria' => $product->category->name,
                'descripción' => $product->desc,
                'talla' => $product->size,
                'color' => $product->color,
                'stock min' => $product->stock_min,
                'stock' => $product->stock,
                'precio compra' => $product->purchase_price,
                'precio venta' => $product->sale_price,
                'creado en' => optional($product->created_at)->toDateTimeString(),
                'actualizado en' => optional($product->updated_at)->toDateTimeString(),
            ];
        });
    }

    public function search(Request $request)
    {
        $term = $request->input('pro');
        try {
            $products = Product::where('desc', 'like', '%' . $term . '%')->get();

            $data = [];
            foreach ($products as $product) {
                $data[] = [
                    'id' => $product->cod_product,
                    'text' => $product->desc . '-' . $product->purchase_price . '-' . $product->id,
                ];
            }

            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function searchSales(Request $request)
    {
        $term = $request->input('pro');
        try {
            $products = Product::where('desc', 'like', '%' . $term . '%')->get();

            $data = [];
            foreach ($products as $product) {
                $data[] = [
                    'id' => $product->cod_product,
                    'text' => $product->desc . '-' . $product->sale_price . '-' . $product->id,
                ];
            }

            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'i_cod_producto' => 'required',
            'selectCategoria' => 'required',
            'i_descripcion' => 'required',
            'i_talla' => 'required',
            'i_color' => 'required',
            'i_stock_min' => 'required|numeric|gte:0',
            'i_stock' => 'required|numeric|gte:0',
            'i_precio_compra' => 'required|numeric|gte:0',
            'i_precio_venta' => 'required|numeric|gte:0',
        ]);

        $product = Product::create([
            'cod_product' => $validatedData['i_cod_producto'],
            'category_id' => $validatedData['selectCategoria'],
            'desc' => $validatedData['i_descripcion'],
            'color' => $validatedData['i_color'],
            'size' => $validatedData['i_talla'],
            'stock_min' => $validatedData['i_stock_min'],
            'stock' => $validatedData['i_stock'],
            'purchase_price' => $validatedData['i_precio_compra'],
            'sale_price' => $validatedData['i_precio_venta'],
        ]);

        return response()->json(['message' => 'Producto creado con éxito', 'product' => $product]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'e_cod_producto' => 'required',
            'e_selectCategoria' => 'required',
            'e_descripcion' => 'required',
            'e_color' => 'required',
            'e_talla' => 'required',
            'e_stock_min' => 'required|numeric',
            'e_stock' => 'required|numeric',
            'e_precio_compra' => 'required|numeric',
            'e_precio_venta' => 'required|numeric',
        ]);

        $product = Product::findOrFail($id);
        $product->update([
            'cod_product' => $validatedData['e_cod_producto'],
            'category_id' => $validatedData['e_selectCategoria'],
            'desc' => $validatedData['e_descripcion'],
            'color' => $validatedData['e_color'],
            'size' => $validatedData['e_talla'],
            'stock_min' => $validatedData['e_stock_min'],
            'stock' => $validatedData['e_stock'],
            'purchase_price' => $validatedData['e_precio_compra'],
            'sale_price' => $validatedData['e_precio_venta'],
        ]);

        return response()->json(['message' => 'Producto actualizado con éxito', 'product' => $product]);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Producto eliminado con éxito']);
    }

    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls',
        ]);

        // Obtener la ruta temporal del archivo cargado
        $filePath = $request->file('excel_file')->getRealPath();

        // Instanciar la clase de importación y llamar al método import
        $productsImport = new ProductsImport();
        $imported = $productsImport->import($filePath);

        if ($imported) {
            // Redireccionar o mostrar un mensaje de éxito
            return redirect()->back()->with('success', 'La importación del archivo Excel se realizó correctamente.');
        } else {
            // Redireccionar o mostrar un mensaje de error
            return redirect()->back()->with('error', 'Ocurrió un error durante la importación del archivo Excel.');
        }
    }
}
