{{-- <div class="col-sm-12 col-md-4 mt-2 mt-sm-4">
    <label class="form-label">Sku</label>
    <input wire:model="form.sku" class="form-control" type="text">
</div> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>

{{-- SKU --}}
<div class="col-sm-12 col-md-4 mt-2 mt-sm-4">
    <label class="form-label">Sku</label>
    <input wire:model="form.sku" id="sku-input" class="form-control" type="text">
    <button id="start-scanner" class="btn btn-primary mt-2">Leer Código de Barras</button>
</div>

<div id="interactive" class="viewport" style="display: none;"></div>


<script>
    document.getElementById('start-scanner').addEventListener('click', function() {
        // Mostrar el área de escaneo
        document.getElementById('interactive').style.display = 'block';

        // Configurar Quagga
        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: document.getElementById('interactive'), // Elemento donde se mostrará la cámara
                constraints: {
                    facingMode: "environment" // Usar la cámara trasera
                },
            },
            decoder: {
                readers: ["code_128_reader"] // Puedes agregar más tipos de códigos de barras si es necesario
            },
        }, function(err) {
            if (err) {
                console.log(err);
                return;
            }
            console.log("Iniciando Quagga...");
            Quagga.start();
        });

        // Manejar la lectura del código de barras
        Quagga.onDetected(function(data) {
            var code = data.codeResult.code;
            document.getElementById('sku-input').value = code; // Asignar el código leído al input
            Quagga.stop(); // Detener Quagga
            document.getElementById('interactive').style.display = 'none'; // Ocultar el área de escaneo
        });
    });
</script>
