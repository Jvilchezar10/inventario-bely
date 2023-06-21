<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProofOfPayment;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class ProofOfPaymentController extends Controller
{

    function __construct()
    {
        // Middleware para los permisos
        $this->middleware('permission:proofofpayment-list|proofofpayment-create|proofofpayment-edit|proofofpayment-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:proofofpayment-create', ['only' => ['store']]);
        $this->middleware('permission:proofofpayment-edit', ['only' => ['update']]);
        $this->middleware('permission:proofofpayment-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $proofofpaymentId = 0;
        $columns = [
            'id',
            'nombre',
            'estado',
            'creado en',
            'actualizado en',
            'opciones'
        ];
        $data = [];
        return view('admin.proofofpayment', compact('proofofpaymentId', 'columns', 'data'));
    }

    public function getData(Request $request)
    {
        try {
            if ($request->ajax()) {
                if (!Auth::user()->can('proofofpayment-list')) {
                    abort(403, 'Permisos denegados');
                    throw new \Exception('No tienes permiso para listar comprobantes.');
                }
                $proofofpayments = ProofOfPayment::all();
                $data = $this->transformPOPayment($proofofpayments);
                return response()->json(['data' => $data], Response::HTTP_OK);
            } else {
                throw new \Exception('Invalid request.');
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    private function transformPOPayment($proofofpayments)
    {
        return $proofofpayments->map(function ($proofofpayment) {
            return [
                'id' => $proofofpayment->id,
                'nombre' => $proofofpayment->name,
                'estado' => $proofofpayment->state,
                'creado en' => optional($proofofpayment->created_at)->toDateTimeString(),
                'actualizado en' => optional($proofofpayment->updated_at)->toDateTimeString(),
            ];
        });
    }

    public function search(Request $request)
    {
        $term = $request->input('q');

        try {
            $proofofpayments =  ProofOfPayment::where(function ($query) use ($term) {
                $query->where('name', 'like', '%' . $term . '%');
            })->get();

            $data = [];

            foreach ($proofofpayments as $proofofpayment) {
                $data[] = [
                    'id' => $proofofpayment->id,
                    'text' => $proofofpayment->name
                ];
            }

            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function store(Request $request)
    {
        if (!Auth::user()->can('proofofpayment-create')) {
            // Session::flash('error', 'Permisos denegados');
            abort(403, 'Permisos denegados');
        }

        $validatedData = $request->validate([
            'i_name' => 'required',
            'i_state' => 'required|in:vigente,descontinuado',
        ]);
        $proofofpayment = ProofOfPayment::create([
            'name' => $validatedData['i_name'],
            'state' => $validatedData['i_state'],
        ]);

        return response()->json(['message' => 'Comprobante creado con éxito', 'proofofpayment' => $proofofpayment]);
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->can('proofofpayment-edit')) {
            abort(403, 'Permisos denegados');
        }

        $validatedData = $request->validate([
            'e_name' => 'required',
            'e_state' => 'required|in:vigente,descontinuado',
        ]);

        $proofofpayment = ProofOfPayment::findOrFail($id);
        $proofofpayment->update([
            'name' => $validatedData['e_name'],
            'state' => $validatedData['e_state']
        ]);

        return response()->json(['message' => 'Comprobante actualizado con éxito', 'proofofpayment' => $proofofpayment]);
    }

    public function destroy($id)
    {
        if (!Auth::user()->can('proofofpayment-delete')) {
            abort(403, 'Permisos denegados');
        }

        $proofofpayment = ProofOfPayment::findOrFail($id);
        $proofofpayment->delete();

        return response()->json(['message' => 'Comprobante eliminado con éxito']);
    }
}
