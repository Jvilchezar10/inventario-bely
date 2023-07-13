<?php

namespace App\Http\Controllers;

use App\Imports\CategoriesImport;
use Illuminate\Http\Request;
use App\Models\Category;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    function __construct()
    {
        // Middleware para los permisos
        $this->middleware('permission:category-list|category-create|category-edit|category-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:category-create', ['only' => ['store']]);
        $this->middleware('permission:category-edit', ['only' => ['update']]);
        $this->middleware('permission:category-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        // if (!Auth::user()->can('category-list')) {
        //     Session::flash('error', 'Permisos denegados');
        // }

        $categoryId = 0;
        $columns = [
            'id',
            'nombre',
            'estado',
            'creado en',
            'actualizado en',
            'opciones'
        ];
        $data = [];
        return view('admin.category', compact('categoryId', 'columns', 'data'));
    }

    public function getData(Request $request)
    {
        try {
            if ($request->ajax()) {
                if (!Auth::user()->can('category-list')) {
                    abort(403, 'Permisos denegados');
                    throw new \Exception('No tienes permiso para listar categorías.');
                }

                $categories = Category::all();
                $data = $this->transformCategories($categories);
                return response()->json(['data' => $data], Response::HTTP_OK);
            } else {
                throw new \Exception('Invalid request.');
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    private function transformCategories($categories)
    {
        return $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'nombre' => $category->name,
                'estado' => $category->state,
                'creado en' => optional($category->created_at)->toDateTimeString(),
                'actualizado en' => optional($category->updated_at)->toDateTimeString(),
            ];
        });
    }

    public function search(Request $request)
    {
        $term = $request->input('q');

        try {
            $categories = Category::where(function ($query) use ($term) {
                $query->where('name', 'like', '%' . $term . '%');
            })->get();

            $data = [];

            foreach ($categories as $category) {
                $data[] = [
                    'id' => $category->id,
                    'text' => $category->name
                ];
            }

            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function store(Request $request)
    {
        if (!Auth::user()->can('category-create')) {
            // Session::flash('error', 'Permisos denegados');
            abort(403, 'Permisos denegados');
        }

        $validatedData = $request->validate([
            'i_name' => 'required',
            'i_state' => 'required|in:vigente,descontinuado',
        ]);
        // $category = Category::create($validatedData);
        $category = Category::create([
            'name' => $validatedData['i_name'],
            'state' => $validatedData['i_state'],
        ]);

        return response()->json(['message' => 'Categoría creada con éxito', 'category' => $category]);
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->can('category-edit')) {
            abort(403, 'Permisos denegados');
        }

        $validatedData = $request->validate([
            'e_name' => 'required',
            'e_state' => 'required|in:vigente,descontinuado',
        ]);

        $category = Category::findOrFail($id);
        $category->update([
            'name' => $validatedData['e_name'],
            'state' => $validatedData['e_state']
        ]);

        return response()->json(['message' => 'Categoría actualizada con éxito', 'category' => $category]);
    }

    public function destroy($id)
    {
        if (!Auth::user()->can('category-delete')) {
            abort(403, 'Permisos denegados');
        }

        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Categoría eliminada con éxito']);
    }

    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls',
        ]);

        // Obtener la ruta temporal del archivo cargado
        $filePath = $request->file('excel_file')->getRealPath();

        try {
            // Instanciar la clase de importación y llamar al método import
            $categoriesImport = new CategoriesImport();
            $imported = $categoriesImport->import($filePath);

            if ($imported) {
                // Redireccionar o mostrar un mensaje de éxito
                return redirect()->back()->with('success', 'La importación del archivo Excel se realizó correctamente.');
            } else {
                // Redireccionar o mostrar un mensaje de error
                return redirect()->back()->with(['error', 'No se importaron registros del archivo Excel.']);
            }
        } catch (Exception $e) {
            return redirect()->back()->with(['error', 'Ocurrió un error durante la importación del archivo Excel: ' . $e->getMessage()]);
        }
    }
}
