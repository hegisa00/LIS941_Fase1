@extends('layouts.app')

@section('titulo', 'Comprar cupón')

@section('contenido')
    <div class="row g-4">
        <div class="col-lg-5">
            <article class="ticket">
                <div class="p-3">
                    <div class="small text-secondary">{{ $oferta->empresa->nombre }}</div>
                    <h1 class="h4 fw-bold mb-2">{{ $oferta->titulo }}</h1>
                    <p class="small mb-0">{{ $oferta->descripcion }}</p>
                </div>
                <div class="ticket-corte"></div>
                <div class="p-3 d-flex justify-content-between align-items-end">
                    <div>
                        <div class="text-decoration-line-through text-secondary small">${{ number_format($oferta->precio_regular, 2) }}</div>
                        <div class="precio" id="precio" data-precio="{{ $oferta->precio_oferta }}">${{ number_format($oferta->precio_oferta, 2) }}</div>
                    </div>
                    <span class="badge text-bg-warning fs-6">-{{ $oferta->porcentajeDescuento() }}%</span>
                </div>
                <ul class="small text-secondary px-3 pb-3 mb-0 list-unstyled">
                    <li>Oferta válida hasta el {{ $oferta->fecha_fin->format('d/m/Y') }}</li>
                    <li>Canjéalo a más tardar el {{ $oferta->fecha_limite_canje->format('d/m/Y') }}</li>
                    @if($oferta->cuponesRestantes() !== null)
                        <li>Quedan {{ $oferta->cuponesRestantes() }} cupones</li>
                    @endif
                </ul>
            </article>
        </div>

        <div class="col-lg-7">
            <div class="panel p-4">
                <h2 class="h5 fw-bold">Datos de pago</h2>
                <p class="small text-secondary">Pago simulado: no se realiza ningún cobro real y no guardamos el número de tu tarjeta ni el CVV.</p>

                @if($maximo === 0)
                    <div class="alert alert-warning mb-0">
                        @if($yaComprados >= \App\Models\Oferta::MAX_POR_CLIENTE)
                            Ya compraste el máximo de {{ \App\Models\Oferta::MAX_POR_CLIENTE }} cupones de esta oferta.
                        @else
                            Esta oferta se agotó.
                        @endif
                        <a href="{{ route('compras.index') }}">Ver mis cupones</a>
                    </div>
                @else
                    <form method="POST" action="{{ route('compras.store', $oferta->id) }}" novalidate autocomplete="off">
                        @csrf

                        <div class="mb-3">
                            <label for="cantidad" class="form-label">Cantidad de cupones</label>
                            <select id="cantidad" name="cantidad" class="form-select @error('cantidad') is-invalid @enderror">
                                @for($i = 1; $i <= $maximo; $i++)
                                    <option value="{{ $i }}" @selected((int) old('cantidad', 1) === $i)>{{ $i }}</option>
                                @endfor
                            </select>
                            <div class="form-text">
                                Máximo {{ \App\Models\Oferta::MAX_POR_CLIENTE }} por oferta
                                @if($yaComprados > 0) (ya compraste {{ $yaComprados }}) @endif.
                            </div>
                            @error('cantidad')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="titular" class="form-label">Nombre del titular</label>
                            <input type="text" id="titular" name="titular" maxlength="100" value="{{ old('titular') }}"
                                   class="form-control @error('titular') is-invalid @enderror">
                            @error('titular')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="numero_tarjeta" class="form-label">Número de tarjeta</label>
                            <input type="text" id="numero_tarjeta" name="numero_tarjeta" inputmode="numeric"
                                   maxlength="19" placeholder="0000 0000 0000 0000"
                                   class="form-control @error('numero_tarjeta') is-invalid @enderror">
                            @error('numero_tarjeta')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row g-3">
                            <div class="col-6">
                                <label for="vencimiento" class="form-label">Vencimiento (MM/AA)</label>
                                <input type="text" id="vencimiento" name="vencimiento" maxlength="5" placeholder="08/29"
                                       class="form-control @error('vencimiento') is-invalid @enderror">
                                @error('vencimiento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-6">
                                <label for="cvv" class="form-label">CVV</label>
                                <input type="password" id="cvv" name="cvv" inputmode="numeric" maxlength="4" placeholder="123"
                                       class="form-control @error('cvv') is-invalid @enderror">
                                @error('cvv')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div>
                                <div class="small text-secondary">Total a pagar</div>
                                <div class="precio" id="total">${{ number_format($oferta->precio_oferta, 2) }}</div>
                            </div>
                            <button type="submit" class="btn btn-cupon btn-lg">Pagar y obtener cupones</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    (function () {
        const precio = parseFloat(document.getElementById('precio').dataset.precio);
        const cantidad = document.getElementById('cantidad');
        const total = document.getElementById('total');
        const tarjeta = document.getElementById('numero_tarjeta');
        const vence = document.getElementById('vencimiento');
        if (!cantidad) return;

        const pintar = () => total.textContent = '$' + (precio * parseInt(cantidad.value || 1, 10)).toFixed(2);
        cantidad.addEventListener('change', pintar);
        pintar();

        // Ayudas de formato: grupos de 4 dígitos y barra en el vencimiento.
        tarjeta.addEventListener('input', () => {
            tarjeta.value = tarjeta.value.replace(/\D/g, '').slice(0, 16).replace(/(.{4})/g, '$1 ').trim();
        });
        vence.addEventListener('input', () => {
            const d = vence.value.replace(/\D/g, '').slice(0, 4);
            vence.value = d.length > 2 ? d.slice(0, 2) + '/' + d.slice(2) : d;
        });
    })();
</script>
@endpush
