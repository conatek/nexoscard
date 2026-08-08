@extends('emails.layout')

@section('content')
<h1 style="margin: 0 0 16px; font-size: 22px; font-weight: 700; color: #1e293b;">
    {{ $user->name }}, es hora de renovar
</h1>

<p style="margin: 0 0 20px; font-size: 15px; color: #475569; line-height: 1.6;">
    Tu plan <strong>{{ $plan?->display_name ?? 'de NexosCard' }}</strong> vence
    @if ($daysRemaining === 1)
        <strong style="color: #7c3aed;">manana</strong>,
    @else
        en <strong style="color: #7c3aed;">{{ $daysRemaining }} dias</strong>,
    @endif
    el <strong>{{ $periodEndsAt->format('d/m/Y') }}</strong>. Renuevalo y tu tarjeta sigue en linea sin que nadie note nada.
</p>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background: #f5f3ff; border: 1px solid #ddd6fe; border-radius: 10px; margin: 0 0 24px;">
    <tr>
        <td style="padding: 20px 24px;">
            <p style="margin: 0 0 12px; font-size: 14px; font-weight: 600; color: #5b21b6;">
                Un año mas de presencia digital
            </p>
            <p style="margin: 0 0 6px; font-size: 14px; color: #4c1d95;">&#10003;&nbsp;&nbsp;Tu tarjeta publicada y siempre actualizada</p>
            <p style="margin: 0 0 6px; font-size: 14px; color: #4c1d95;">&#10003;&nbsp;&nbsp;Tu enlace y tu codigo QR, sin cambios</p>
            <p style="margin: 0; font-size: 14px; color: #4c1d95;">&#10003;&nbsp;&nbsp;Todo tu contenido tal como lo dejaste</p>
        </td>
    </tr>
</table>

@if ($renewalPrice = optional($plan)->effectivePrice())
<p style="margin: 0 0 20px; font-size: 15px; color: #475569; line-height: 1.6;">
    Renovacion: <strong style="color: #1e293b;">${{ number_format($renewalPrice, 0, ',', '.') }} COP</strong> por
    {{ $plan->billing_period === 'monthly' ? 'un mes mas' : 'un año mas' }}.
</p>
@endif

<table role="presentation" style="margin: 0 auto 24px;">
    <tr>
        <td style="background-color: #7c3aed; border-radius: 10px; padding: 14px 32px;">
            <a href="{{ url('/planes') }}" style="color: #ffffff; text-decoration: none; font-weight: 600; font-size: 15px; display: inline-block;">
                Renovar mi plan
            </a>
        </td>
    </tr>
</table>

{{-- El periodo de gracia es una cortesia, no una excusa para no renovar: se cuenta como
     margen de seguridad, sin invitar a dejarlo vencer. --}}
<p style="margin: 0 0 20px; font-size: 14px; color: #64748b; line-height: 1.6; text-align: center;">
    Si se te pasa la fecha, tu tarjeta sigue publicada {{ $graceDays }} dias mas. Despues deja de estar disponible hasta que renueves.
</p>

<p style="margin: 0; font-size: 13px; color: #94a3b8; text-align: center;">
    ¿Necesitas ayuda? Escribenos a <a href="{{ 'mailto:' . \App\Models\AppSetting::getSupportEmail() }}" style="color: #7c3aed;">{{ \App\Models\AppSetting::getSupportEmail() }}</a>
</p>
@endsection
