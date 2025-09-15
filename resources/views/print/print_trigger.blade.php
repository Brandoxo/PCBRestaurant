<!DOCTYPE html>
<html>
<head>
    <title>Imprimiendo Ticket...</title>
</head>
<body>
    <h1>Procesando la impresión...</h1>
    <p>Si la impresión no comienza automáticamente, haz clic en el siguiente enlace:</p>
    <a id="print-link" href="print://{{ $printData }}">Imprimir Ticket</a>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var printUrl = document.getElementById('print-link').href;
            
            // Intenta abrir el protocolo
            window.location.href = printUrl;
            
            // Opcional: cierra la ventana o redirecciona después de un breve retraso
            setTimeout(function() {
                // Puedes cerrar la pestaña o redirigir a otra página, como la página de inicio
                // window.close(); 
                // window.location.href = '/'; 
            }, 1000); 
        });
    </script>
</body>
</html>