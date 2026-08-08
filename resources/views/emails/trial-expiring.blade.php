@extends('emails.layout')

@section('content')
<h1 style="margin: 0 0 16px; font-size: 22px; font-weight: 700; color: #1e293b;">
    {{ $user->name }}, tu prueba esta por vencer
</h1>

<p style="margin: 0 0 20px; font-size: 15px; color: #475569; line-height: 1.6;">
    Te quedan <strong style="color: #d97706;">{{ $daysRemaining }} dias</strong> de tu periodo de prueba en NexosCard. Despues del <strong>{{ $trialEndsAt->format('d/m/Y') }}</strong> el acceso a las funcionalidades sera limitado.
</p>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 10px; margin: 0 0 24px;">
    <tr>
        <td style="padding: 20px 24px;">
            <p style="margin: 0 0 8px; font-size: 14px; font-weight: 600; color: #92400e;">
                Al vencer tu prueba:
            </p>
            <p style="margin: 0 0 4px; font-size: 14px; color: #78350f;">&#10005;&nbsp;&nbsp;Tu tarjeta dejara de estar publicada</p>
            <p style="margin: 0 0 4px; font-size: 14px; color: #78350f;">&#10005;&nbsp;&nbsp;Tu enlace y tu codigo QR dejaran de abrir</p>
            <p style="margin: 0; font-size: 14px; color: #78350f;">&#10003;&nbsp;&nbsp;Tu informacion se conserva: al activar, vuelve tal cual</p>
        </td>
    </tr>
</table>

<p style="margin: 0 0 20px; font-size: 15px; color: #475569; line-height: 1.6;">
    Activa tu plan y tu tarjeta sigue en linea sin interrupciones.
</p>

<table role="presentation" style="margin: 0 auto 16px;">
    <tr>
        <td style="background-color: #7c3aed; border-radius: 10px; padding: 14px 32px;">
            <a href="{{ url('/planes') }}" style="color: #ffffff; text-decoration: none; font-weight: 600; font-size: 15px; display: inline-block;">
                Activar mi plan
            </a>
        </td>
    </tr>
</table>
@endsection
