<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\ProductosImport;
use App\Models\Categoria;
use App\Models\Producto;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProductosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = Producto::all();
        $categorias = Categoria::all();
        return view('admin.productos.index', compact('productos'));
    }
    /**
     *Despliega el formulario para importar productos
     */

    public function importar()
    {
        return view('admin.productos.import');
    }
    /**
     * Procesa el archivo importado
     */
    public function importSubmit(Request $request)
    {
        try {
            $file = $request->file('file');
            $import = new ProductosImport;
            Excel::import($import, $file);

            // Acceder a los contadores después de la importación
            $categoriasCreadas = $import->categorias_creadas;
            $productosCreados = $import->creados;
            $productosActualizados = $import->actualizados;

            // Pasar los contadores a la vista a través de la sesión
            return back()->with('success', 'Productos y categorías importados con éxito.')
                ->with('categoriasCreadas', $categoriasCreadas)
                ->with('productosCreados', $productosCreados)
                ->with('productosActualizados', $productosActualizados);
        } catch (\Exception $e) {
            // dd('Exception: '.$e->getMessage());
            $errorMessage = $this->parseDatabaseError($e->getMessage());
            return back()->with('error', $errorMessage);
            //return back()->with('error', 'Error inesperado: ' . $e->getMessage());

        } catch (\Throwable $e) { // Captura cualquier error/exception que no sea \Exception
            // dd('Throwable: '.$e->getMessage());
            return back()->with('error', 'Error inesperado: ' . $e->getMessage());
        }
    }
    /**
     * Parsea el mensaje de error de la base de datos
     */
    protected function parseDatabaseError($errorMessage)
    {
        if (strpos($errorMessage, 'SQLSTATE[22P02]') !== false && strpos($errorMessage, 'Invalid text representation') !== false) {
            return 'Uno de los valores ingresados no tiene el formato correcto. Por favor, revisa los datos e inténtalo de nuevo. Asegúrate de que los números y las fechas estén en el formato adecuado.';
        }

        // Aquí puedes añadir más condiciones según los errores que desees manejar.

        // Si el error no coincide con ninguno de los anteriores, devuelve el mensaje original o uno genérico.
        return 'Se produjo un error al procesar tu solicitud. Por favor, revisa los datos e inténtalo de nuevo.';
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = Categoria::orderby('nombre')->get();
        return view('admin.productos.create', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'sku' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'oem1' => 'nullable|string|max:255',
            'oem2' => 'nullable|string|max:255',
            'oem3' => 'nullable|string|max:255',
            'oem4' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'costo' => 'required|numeric|min:0',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0',
            'alto' => 'nullable|numeric|min:0',
            'ancho' => 'nullable|numeric|min:0',
            'largo' => 'nullable|numeric|min:0',
            'peso' => 'nullable|numeric|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $producto = new Producto();
        $producto->sku = $request->sku;
        $producto->nombre = $request->nombre;
        $producto->oem1 = $request->oem1;
        $producto->oem2 = $request->oem2;
        $producto->oem3 = $request->oem3;
        $producto->oem4 = $request->oem4;
        $producto->descripcion = $request->descripcion;
        $producto->costo = $request->costo;
        $producto->precio = $request->precio;
        $producto->stock = $request->stock;
        $producto->alto = $request->alto;
        $producto->ancho = $request->ancho;
        $producto->largo = $request->largo;
        $producto->peso = $request->peso;
        $producto->categoria_id = $request->categoria_id;



        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $path = $file->store('public/imagenes');
            $producto->imagen = $path;
        }

        $producto->save();

        return redirect()->route('admin.productos.index')->with('success', 'Producto creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
