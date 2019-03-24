<?php

namespace App\Http\Controllers;
use App\Producto;
use App\Precio;

class ProductosController extends Controller
{
     function get_productos(){
        $productos = Producto::with(['precios','presentaciones'])->get();
        $productos2 = Producto::with(['precios','presentaciones'])->where('id',1)->get();
        $productos3 = Producto::with(['precios','presentaciones' => function($query){
            $query->where('presentaciones.id','2');
        }])->get();
        return response()->json([$productos, $productos2, $productos3]);
     }
}
