<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; border: 1px solid #eee; border-radius: 10px; overflow: hidden; }
        .header { bg-color: #C12026; background: #C12026; padding: 20px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; }
        .content { padding: 30px; }
        .footer { background: #f9f9f9; padding: 20px; text-align: center; font-size: 12px; color: #777; }
        .creds { background: #fef2f2; border: 1px dashed #C12026; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .btn { display: inline-block; padding: 12px 25px; background-color: #C12026; color: #ffffff !important; text-decoration: none; border-radius: 5px; font-weight: bold; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Sistema ENVIA</h1>
        </div>
        <div class="content">
            <h2>¡Hola, {{ $user->name }}!</h2>
            <p>Se ha creado una cuenta para ti en el sistema de gestión de <strong>ENVIA</strong>.</p>
            <p>A continuación encontrarás tus credenciales de acceso:</p>
            
            <div class="creds">
                <p><strong>Correo electrónico:</strong> {{ $user->email }}</p>
                <p><strong>Contraseña temporal:</strong> <span style="color: #C12026; font-family: monospace; font-size: 18px;">{{ $password }}</span></p>
                <p><strong>Rol asignado:</strong> {{ ucfirst($user->role) }}</p>
            </div>

            <p>Te recomendamos cambiar esta contraseña al ingresar por primera vez desde tu perfil.</p>
            
            <a href="{{ url('/login') }}" class="btn">Acceder al Sistema</a>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} ENVIA. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
