<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        return view('auth.register');
    }

    public function getData(Request $request)
    {
        try {
            if ($request->ajax()) {
                $users = User::all();
                $data = $this->transformUsers($users);
                return response()->json(['data' => $data], Response::HTTP_OK);
            } else {
                throw new \Exception('Invalid request.');
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    private function transformUsers($users)
    {
        // 'id', 'Nombre', 'Correo', 'Empleado', 'Tipo de usuario', 'creado en', 'actualizado en',
        return $users->map(function ($user) {
            $userRoles = $user->roles->all();
            $roles_id = '';
            $roles = '';

            foreach ($userRoles as $key => $value) {
                // Concatenar el ID del rol actual al string `$roles_id`
                // Si hay más de un elemento en el arreglo `$userRoles`, se agrega una coma antes del ID
                // De lo contrario, no se agrega la coma
                $roles_id .= count($userRoles) > 1 ? ', ' . $value->id  : $value->id;

                // Concatenar el nombre del rol actual al string `$roles`
                // Si hay más de un elemento en el arreglo `$userRoles`, se agrega una coma antes del nombre
                // De lo contrario, no se agrega la coma
                $roles .=  count($userRoles) > 1 ? ', ' . $value->name  : $value->name;
            }

            return [
                'id' => $user->id,
                'Nombre' => $user->name,
                'Correo' => $user->email,
                'empleado_id' => optional($user->employee)->id,
                'Empleado' => optional($user->employee)->name . ' ' . optional($user->employee)->last_name,
                'Tipo de usuario_id' => $roles_id,
                'Tipo de usuario' => $roles,
                'Creado en' => optional($user->created_at)->toDateTimeString(),
                'Actualizado en' => optional($user->updated_at)->toDateTimeString(),
            ];
        });
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'i_name' => 'required',
            'i_email' => 'required|email|unique:users,email',
            'i_password' => 'required|same:confirm-password',
            'i_selectEmpleado' => 'required',
            'i_selectRoles' => 'required'
        ]);

        $input = $request->all();
        $input['i_password'] = Hash::make($input['i_password']);

        $user = User::create([
            'name' => $input['i_name'],
            'email' => $input['i_email'],
            'password' => $input['i_password'],
            'employee_id' => $input['i_selectEmpleado']
        ]);

        //$roles = Role::findById($request->i_selectRoles);
        //dd($roles);
        $user->assignRole($request->input('i_selectRoles'));

        return redirect()->back()
            ->with('success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): View
    {
        $user = User::find($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $user = User::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();


        return view('admin.users.edit', compact('user', 'roles', 'userRole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //dd($id);
        $this->validate($request, [
            'e_name' => 'required',
            'e_email' => 'required|email',
            'e_password' => 'required|same:e_confirm-password',
            'e_selectEmpleado' => 'required',
            'e_selectRoles' => 'required'
        ]);

        $input = $request->all();
        if (!empty($input['e_password'])) {
            $input['e_password'] = Hash::make($input['e_password']);
        } else {
            $input = Arr::except($input, array('e_password'));
        }

        $user = User::find($id);
        if($user->email !== $input['e_email']){
            $user->email = $input['e_email'];
        }

        $user->update([
            'name' => $input['e_name'],
            'password' => $input['e_password'],
            'employee_id' => $input['e_selectEmpleado']
        ]);
        DB::table('model_has_roles')->where('model_id', $id)->delete();

        $user->assignRole($request->input('e_selectRoles'));

        //dd($user);

        return redirect()->back()
            ->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): RedirectResponse
    {
        User::find($id)->delete();
        return redirect()->back()
            ->with('success', 'User deleted successfully');
    }
}
