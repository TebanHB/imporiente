<!DOCTYPE html>
<html>
<head>
    <title>Tu nueva cuenta ha sido creada</title>
</head>
<body>
    <h1>Hola, {{ $user->name }}</h1>
    <p>Tu cuenta ha sido creada exitosamente. Aquí está tu contraseña temporal:</p>
    <p><strong>{{ $password }}</strong></p>
    <p>Por favor, cambia tu contraseña después de iniciar sesión.</p>
</body>
</html>