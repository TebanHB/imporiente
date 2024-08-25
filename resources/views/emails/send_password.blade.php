<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tu nueva cuenta ha sido creada</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        .header {
            background-color: #007bff;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px;
        }
        .content h1 {
            font-size: 20px;
            color: #333333;
        }
        .content p {
            font-size: 16px;
            color: #666666;
            line-height: 1.5;
        }
        .content .password {
            font-size: 18px;
            color: #007bff;
            font-weight: bold;
        }
        .footer {
            background-color: #f4f4f4;
            color: #666666;
            padding: 10px;
            text-align: center;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Tu nueva cuenta ha sido creada</h1>
        </div>
        <div class="content">
            <h1>Hola, {{ $user->name }}</h1>
            <p>Tu cuenta ha sido creada exitosamente. Aquí está tu contraseña temporal:</p>
            <p class="password">{{ $password }}</p>
            <p>Por favor, cambia tu contraseña después de iniciar sesión.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Imporiente. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>