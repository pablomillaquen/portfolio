<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo mensaje de contacto</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #1a1a2e; color: #fff; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .body { background: #f9f9f9; padding: 20px; border: 1px solid #ddd; }
        .field { margin-bottom: 15px; }
        .label { font-weight: bold; color: #555; font-size: 12px; text-transform: uppercase; }
        .value { margin-top: 3px; }
        .message-box { background: #fff; padding: 15px; border-radius: 5px; border-left: 4px solid #1a1a2e; margin-top: 5px; white-space: pre-wrap; }
        .footer { text-align: center; color: #888; font-size: 12px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Nuevo mensaje de contacto</h2>
            <p style="margin:0;color:#aaa;">New contact message</p>
        </div>
        <div class="body">
            <div class="field">
                <div class="label">Nombre / Name</div>
                <div class="value">{{ $name }}</div>
            </div>
            <div class="field">
                <div class="label">Email</div>
                <div class="value">{{ $email }}</div>
            </div>
            <div class="field">
                <div class="label">Mensaje / Message</div>
                <div class="message-box">{{ $body }}</div>
            </div>
        </div>
        <div class="footer">
            <p>Este mensaje fue enviado desde el formulario de contacto de pablomillaquen.com</p>
            <p>This message was sent from the contact form at pablomillaquen.com</p>
        </div>
    </div>
</body>
</html>
