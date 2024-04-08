{{-- resources/views/secondAuth/code-second-auth.blade.php --}}

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Segundo Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>
<body>

    <div class="container">
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <p>Ingresa el código que se te envió a tu correo electrónico</p>

        <form method="post" action="{{ route('set_second_auth') }}">
            @csrf

            <label for="campo">Código:</label>
            <input type="text" id="campo" name="campo" required>

            <button id="btnEnviar" onclick="validationButton()" type="submit" class="btn btn-primary">Enviar</button>
        </form>
    </div>

    <script>
        function validationButton() {
            document.getElementById("btnEnviar").disabled = true;
            document.querySelector('form').submit();
            setTimeout(function() {document.getElementById("btnEnviar").disabled = false;}, 2000);
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
