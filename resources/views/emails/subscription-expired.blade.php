@extends('emails.layout')

@section('content')
<h1 style="margin: 0 0 16px; font-size: 22px; font-weight: 700; color: #1e293b;">
    {{ $user->name }}, tu suscripcion ha expirado
</h1>

<p style="margin: 0 0 20px; font-size: 15px; color: #475569; line-height: 1.6;">
    Tu suscripcion al plan <strong>{{ $previousPlan?->display_name ?? 'pago' }}</strong> en NexosCard ha expirado completamente. Tu cuenta ha sido degradada al plan gratuito.
</p>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px; margin: 0 0 24px;">
    <tr>
        <td style="padding: 20px 24px;">
            <p style="margin: 0 0 8px; font-size: 14px; font-weight: 600; color: #991b1b;">
                Acceso limitado
            </p>
            <p style="margin: 0; font-size: 14px; color: #7f1d1d; line-height: 1.6;">
                Las funcionalidades avanzadas de tu plan anterior han sido desactivadas. Tu informacion sigue segura, pero no podras crear ni editar contenido hasta renovar.
            </p>
        </td>
    </tr>
</table>

<p style="margin: 0 0 20px; font-size: 15px; color: #475569; line-height: 1.6;">
    Renueva tu suscripcion para recuperar todas las funcionalidades de tu plan.
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
    ¿Necesitas ayuda? Escribenos a <a href="mailto:soporte@nexoscard.com" style="color: #7c3aed;">soporte@nexoscard.com</a>
</p>
@endsection
