<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /*   public function __construct()
      {
          $this->middleware('can:permissions.index')->only('index');
          $this->middleware('can:permissions.create')->only(['create', 'store']);
          $this->middleware('can:permissions.edit')->only(['edit', 'update']);
          $this->middleware('can:permissions.delete')->only('destroy');
      } */
    public function index()
    {
        $permissions = Permission::latest()->paginate(10);
        return view('admin.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:permissions,name',
        ]);

        Permission::create(['name' => $data['name'], 'guard_name' => 'web']);
        return redirect()->route('admin.permissions.index')->with('success', 'Permiso creado correctamente.');
    }

    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:permissions,name,' . $permission->id,
        ]);

        $permission->update(['name' => $data['name']]);
        return redirect()->route('admin.permissions.index')->with('success', 'Permiso actualizado.');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('admin.permissions.index')->with('success', 'Permiso eliminado.');
    }
}
