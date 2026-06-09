@extends('emails.layout')

@section('content')
<h1 style="margin: 0 0 16px; font-size: 22px; font-weight: 700; color: #1e293b;">
    {{ $user->name }}, tu periodo de prueba ha finalizado
</h1>

<p style="margin: 0 0 20px; font-size: 15px; color: #475569; line-height: 1.6;">
    Tu periodo de prueba en NexosCard ha terminado. A partir de ahora, las funcionalidades de tu cuenta estaran limitadas.
</p>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px; margin: 0 0 24px;">
    <tr>
        <td style="padding: 20px 24px;">
            <p style="margin: 0 0 8px; font-size: 14px; font-weight: 600; color: #991b1b;">
                Tu cuenta ha sido limitada
            </p>
            <p style="margin: 0; font-size: 14px; color: #7f1d1d; line-height: 1.6;">
                No podras crear ni editar tarjetas, productos o servicios hasta que actives un plan de pago.
            </p>
        </td>
    </tr>
</table>

<p style="margin: 0 0 20px; font-size: 15px; color: #475569; line-height: 1.6;">
    Activa tu plan ahora y recupera el acceso completo a todas las funcionalidades. Tu informacion sigue segura y disponible.
</p>

<table role="presentation" style="margin: 0 auto 24px;">
    <tr>
        <td style="background-color: #7c3aed; border-radius: 10px; padding: 14px 32px;">
            <a href="{{ url('/planes') }}" style="color: #ffffff; text-decoration: none; font-weight: 600; font-size: 15px; display: inline-block;">
                Activar mi plan ahora
            </a>
        </td>
    </tr>
</table>

<p style="margin: 0; font-size: 13px; color: #94a3b8; text-align: center;">
    ¿Necesitas ayuda? Escribenos a <a href="mailto:soporte@nexoscard.com" style="color: #7c3aed;">soporte@nexoscard.com</a>
</p>
@endsection
