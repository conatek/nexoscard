@extends('emails.layout')

@section('content')
<h1 style="margin: 0 0 16px; font-size: 22px; font-weight: 700; color: #1e293b;">
    {{ $user->name }}, tu plan ha sido activado
</h1>

<p style="margin: 0 0 20px; font-size: 15px; color: #475569; line-height: 1.6;">
    Tu plan <strong style="color: #7c3aed;">{{ $plan->display_name }}</strong> esta activo. Ya puedes disfrutar de todas las funcionalidades incluidas.
</p>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; margin: 0 0 24px;">
    <tr>
        <td style="padding: 20px 24px;">
            <p style="margin: 0 0 12px; font-size: 13px; color: #16a34a; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;">Resumen de tu plan</p>
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="padding: 4px 0; font-size: 14px; color: #475569;">Plan</td>
                    <td style="padding: 4px 0; font-size: 14px; color: #1e293b; font-weight: 600; text-align: right;">{{ $plan->display_name }}</td>
                </tr>
                <tr>
                    <td style="padding: 4px 0; font-size: 14px; color: #475569;">Monto pagado</td>
                    <td style="padding: 4px 0; font-size: 14px; color: #1e293b; font-weight: 600; text-align: right;">${{ number_format($payment->amount, 0, ',', '.') }} {{ $payment->currency }}</td>
                </tr>
                <tr>
                    <td style="padding: 4px 0; font-size: 14px; color: #475569;">Vigente hasta</td>
                    <td style="padding: 4px 0; font-size: 14px; color: #1e293b; font-weight: 600; text-align: right;">{{ $subscription->current_period_end->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td style="padding: 4px 0; font-size: 14px; color: #475569;">Tarjetas</td>
                    <td style="padding: 4px 0; font-size: 14px; color: #1e293b; font-weight: 600; text-align: right;">{{ $plan->max_cards ?? 'Ilimitadas' }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table role="presentation" style="margin: 0 auto 16px;">
    <tr>
        <td style="background-color: #7c3aed; border-radius: 10px; padding: 14px 32px;">
            <a href="{{ url('/') }}" style="color: #ffffff; text-decoration: none; font-weight: 600; font-size: 15px; display: inline-block;">
                Ir a mi dashboard
            </a>
        </td>
    </tr>
</table>
@endsection
