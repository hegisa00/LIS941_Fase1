<?php

namespace Database\Seeders;

use App\Models\Empresa;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Administrador inicial (el resto se registran desde el panel de administración).
        $admin = User::create([
            'usuario' => 'admin',
            'email' => 'admin@cuponera.sv',
            'password' => 'Admin1234',
            'rol' => User::ROL_ADMIN,
        ]);
        $admin->administrador()->create(['nombre_completo' => 'Administrador Principal']);

        // Cliente de prueba.
        $cliente = User::create([
            'usuario' => 'cliente_demo',
            'email' => 'cliente@demo.sv',
            'password' => 'Demo1234',
            'rol' => User::ROL_CLIENTE,
        ]);
        $cliente->cliente()->create([
            'nombre' => 'Ana María',
            'apellidos' => 'Hernández López',
            'dui' => '01234567-8',
            'fecha_nacimiento' => '2000-05-15',
        ]);

        // Empresa ya aprobada, con algunas ofertas para que la portada no esté vacía.
        $aprobada = User::create([
            'usuario' => 'pupuseria_marta',
            'email' => 'marta@demo.sv',
            'password' => 'Demo1234',
            'rol' => User::ROL_EMPRESA,
        ]);
        $empresa = $aprobada->empresa()->create([
            'nombre' => 'Pupusería Doña Marta',
            'nit' => '0614-010190-101-2',
            'direccion' => 'Calle Principal #12, San Salvador',
            'telefono' => '2222-3333',
            'estado' => Empresa::APROBADA,
            'comision' => 10,
            'resuelta_por' => $admin->id,
            'fecha_resolucion' => now(),
        ]);

        $empresa->ofertas()->createMany([
            [
                'titulo' => '6 pupusas revueltas + 2 bebidas',
                'precio_regular' => 8.00,
                'precio_oferta' => 4.99,
                'fecha_inicio' => now()->subDays(2)->toDateString(),
                'fecha_fin' => now()->addDays(20)->toDateString(),
                'fecha_limite_canje' => now()->addDays(40)->toDateString(),
                'cantidad_cupones' => 100,
                'descripcion' => 'Seis pupusas a elegir (revueltas, queso con loroco o frijol con queso) y dos bebidas.',
                'estado' => 'disponible',
            ],
            [
                'titulo' => 'Desayuno típico para 2 personas',
                'precio_regular' => 12.50,
                'precio_oferta' => 7.25,
                'fecha_inicio' => now()->subDay()->toDateString(),
                'fecha_fin' => now()->addDays(15)->toDateString(),
                'fecha_limite_canje' => now()->addDays(30)->toDateString(),
                'cantidad_cupones' => null, // sin límite de cupones
                'descripcion' => 'Huevos, frijoles, plátano frito, crema, queso y café para dos personas.',
                'estado' => 'disponible',
            ],
            [
                'titulo' => 'Oferta oculta (no disponible)',
                'precio_regular' => 5.00,
                'precio_oferta' => 3.00,
                'fecha_inicio' => now()->subDay()->toDateString(),
                'fecha_fin' => now()->addDays(10)->toDateString(),
                'fecha_limite_canje' => now()->addDays(20)->toDateString(),
                'cantidad_cupones' => 10,
                'descripcion' => 'Esta oferta no debe aparecer en la portada porque su estado es no disponible.',
                'estado' => 'no_disponible',
            ],
        ]);

        // Una compra de ejemplo para que los reportes y el historial tengan datos.
        $oferta = $empresa->ofertas()->where('estado', 'disponible')->orderBy('id')->first();
        $compra = $cliente->cliente->compras()->create([
            'oferta_id' => $oferta->id,
            'numero_factura' => 'FAC-'.now()->format('Ymd').'-DEMO1',
            'cantidad' => 2,
            'precio_unitario' => $oferta->precio_oferta,
            'total' => $oferta->precio_oferta * 2,
            'tarjeta_ultimos4' => '4242',
        ]);
        foreach (['CUP-DEMO1-00001', 'CUP-DEMO1-00002'] as $codigo) {
            $compra->cupones()->create(['codigo' => $codigo]);
        }

        // Empresa pendiente: sirve para probar la aprobación desde el panel de administración.
        $pendiente = User::create([
            'usuario' => 'tecnoplaza',
            'email' => 'contacto@tecnoplaza.sv',
            'password' => 'Demo1234',
            'rol' => User::ROL_EMPRESA,
        ]);
        $pendiente->empresa()->create([
            'nombre' => 'Tecno Plaza SV',
            'nit' => '0614-250585-102-4',
            'direccion' => 'Centro Comercial Plaza Mundo, local 45',
            'telefono' => '2555-0101',
            'estado' => Empresa::PENDIENTE,
        ]);
    }
}
