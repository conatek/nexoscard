@extends('emails.layout')

@section('content')
<h1 style="margin: 0 0 16px; font-size: 22px; font-weight: 700; color: #1e293b;">
    Hola {{ $user->name }},
</h1>

<p style="margin: 0 0 20px; font-size: 15px; color: #475569; line-height: 1.6;">
    Bienvenido a <strong style="color: #7c3aed;">NexosCard</strong>. Tu cuenta ha sido creada exitosamente y tu periodo de prueba de <strong>{{ $trialDays }} dias</strong> ha comenzado.
</p>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background: #f8fafc; border-radius: 10px; margin: 0 0 24px;">
    <tr>
        <td style="padding: 20px 24px;">
            <p style="margin: 0 0 8px; font-size: 13px; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;">Tu periodo de prueba</p>
            <p style="margin: 0 0 4px; font-size: 15px; color: #1e293b;">
                <strong>Empresa:</strong> {{ $company->name }}
            </p>
            <p style="margin: 0 0 4px; font-size: 15px; color: #1e293b;">
                <strong>Vence el:</strong> {{ $trialEndsAt->format('d/m/Y') }}
            </p>
            <p style="margin: 0; font-size: 15px; color: #1e293b;">
                <strong>Plan:</strong> Gratis (prueba)
            </p>
        </td>
    </tr>
</table>

<p style="margin: 0 0 16px; font-size: 15px; color: #475569; line-height: 1.6;">
    Durante tu periodo de prueba puedes:
</p>

<table role="presentation" cellpadding="0" cellspacing="0" style="margin: 0 0 24px;">
    <tr><td style="padding: 4px 0; font-size: 14px; color: #475569;">&#10003;&nbsp;&nbsp;Crear tu tarjeta de presentacion digital</td></tr>
    <tr><td style="padding: 4px 0; font-size: 14px; color: #475569;">&#10003;&nbsp;&nbsp;Personalizar con multiples plantillas</td></tr>
    <tr><td style="padding: 4px 0; font-size: 14px; color: #475569;">&#10003;&nbsp;&nbsp;Agregar productos y servicios</td></tr>
    <tr><td style="padding: 4px 0; font-size: 14px; color: #475569;">&#10003;&nbsp;&nbsp;Compartir tu tarjeta al instante</td></tr>
</table>

<table role="presentation" style="margin: 0 auto 24px;">
    <tr>
        <td style="background-color: #7c3aed; border-radius: 10px; padding: 14px 32px;">
            <a href="{{ url('/') }}" style="color: #ffffff; text-decoration: none; font-weight: 600; font-size: 15px; display: inline-block;">
                Explorar NexosCard
            </a>
        </td>
    </tr>
</table>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background: #f5f3ff; border: 1px solid #ddd6fe; border-radius: 10px; margin: 0 0 8px;">
    <tr>
        <td style="padding: 20px 24px;">
            <p style="margin: 0 0 6px; font-size: 13px; color: #7c3aed; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;">
                Guia de usuario
            </p>
            <p style="margin: 0 0 14px; font-size: 14px; color: #475569; line-height: 1.6;">
                Preparamos una guia paso a paso para que armes y compartas tu primera tarjeta en minutos.
            </p>
            <a href="{{ config('mail.user_guide_url') }}" style="color: #7c3aed; text-decoration: underline; font-weight: 600; font-size: 14px;">
                Ver la guia de usuario &rarr;
            </a>
        </td>
    </tr>
</table>
@endsection
