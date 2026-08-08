@extends('emails.layout')

@section('content')
<h1 style="margin: 0 0 16px; font-size: 22px; font-weight: 700; color: #1e293b;">
    {{ $user->name }}, tu suscripcion ha expirado
</h1>

<p style="margin: 0 0 20px; font-size: 15px; color: #475569; line-height: 1.6;">
    Tu plan <strong>{{ $previousPlan?->display_name ?? 'de NexosCard' }}</strong> vencio y el periodo de gracia termino.
</p>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px; margin: 0 0 24px;">
    <tr>
        <td style="padding: 20px 24px;">
            <p style="margin: 0 0 8px; font-size: 14px; font-weight: 600; color: #991b1b;">
                Tu tarjeta esta fuera de linea
            </p>
            <p style="margin: 0; font-size: 14px; color: #7f1d1d; line-height: 1.6;">
                Quien abra tu enlace o escanee tu codigo QR ya no vera tu tarjeta. Tu informacion sigue guardada y puedes entrar a tu cuenta cuando quieras: al renovar, todo vuelve tal como lo dejaste, con el mismo enlace y el mismo QR.
            </p>
        </td>
    </tr>
</table>

<p style="margin: 0 0 20px; font-size: 15px; color: #475569; line-height: 1.6;">
    Renueva y tu tarjeta vuelve a estar publicada en el momento.
</p>

<table role="presentation" style="margin: 0 auto 24px;">
    <tr>
        <td style="background-color: #7c3aed; border-radius: 10px; padding: 14px 32px;">
            <a href="{{ url('/planes') }}" style="color: #ffffff; text-decoration: none; font-weight: 600; font-size: 15px; display: inline-block;">
                Renovar mi suscripcion
            </a>
        </td>
    </tr>
</table>

<p style="margin: 0; font-size: 13px; color: #94a3b8; text-align: center;">
    ¿Necesitas ayuda? Escribenos a <a href="{{ 'mailto:' . \App\Models\AppSetting::getSupportEmail() }}" style="color: #7c3aed;">{{ \App\Models\AppSetting::getSupportEmail() }}</a>
</p>
@endsection
