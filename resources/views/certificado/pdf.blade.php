<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { size: A4 landscape; margin: 0; }
        html, body {
            width: 297mm;
            height: 210mm;
            margin: 0;
            padding: 0;
            font-family: 'DejaVu Sans', sans-serif;
            background: #fff;
            color: #2d2d2d;
        }
        .page {
            position: absolute;
            top: 0;
            left: 0;
            width: 297mm;
            height: 210mm;
            overflow: hidden;
        }
        .outer {
            position: absolute;
            top: 10mm;
            left: 10mm;
            right: 10mm;
            bottom: 10mm;
            border: 3px solid #0B5E2E;
        }
        .inner {
            position: absolute;
            top: 5px;
            left: 5px;
            right: 5px;
            bottom: 5px;
            border: 1.5px solid #C9A227;
        }
        .content {
            position: absolute;
            top: 4px;
            left: 4px;
            right: 4px;
            bottom: 4px;
            padding: 3mm 10mm 2mm 10mm;
            text-align: center;
            overflow: hidden;
        }
        .logo-img {
            width: 92px;
            margin-bottom: 2px;
        }
        .university {
            font-size: 23px;
            color: #0B5E2E;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 1px;
        }
        .faculty {
            font-size: 15px;
            color: #666;
            letter-spacing: 1px;
            margin-bottom: 2px;
        }
        .divider {
            width: 120px;
            border-bottom: 2px solid #C9A227;
            margin: 7px auto 9px auto;
        }
        .title {
            font-size: 37px;
            color: #0B5E2E;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2.5px;
            margin-bottom: 10px;
        }
        .label {
            font-size: 18px;
            color: #555;
            margin-bottom: 3px;
        }
        .student {
            font-size: 39px;
            color: #0B5E2E;
            font-weight: bold;
            margin: 7px 0;
            letter-spacing: 0.5px;
        }
        .c-label {
            font-size: 16px;
            color: #777;
            margin-bottom: 3px;
        }
        .c-name {
            font-size: 25px;
            color: #333;
            font-weight: bold;
            margin: 5px 0 10px 0;
        }
        .details {
            font-size: 16px;
            color: #666;
            line-height: 1.6;
            margin-bottom: 12px;
        }
        .sig-table {
            width: 100%;
            margin: 0 auto;
            border-collapse: collapse;
        }
        .sig-table td {
            width: 50%;
            text-align: center;
            padding: 0 5px;
        }
        .sig-line {
            width: 190px;
            border-top: 1.5px solid #333;
            margin: 0 auto;
        }
        .sig-name {
            font-size: 16px;
            color: #333;
            font-weight: bold;
            margin-top: 2px;
        }
        .sig-role {
            font-size: 15px;
            color: #666;
        }
        .footer {
            font-size: 14px;
            color: #999;
            margin-top: 8px;
        }
        .footer a {
            color: #999;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="outer">
            <div class="inner">
                <div class="content">

                    <img src="{{ $logoSrc }}" alt="UNAS" class="logo-img">

                    <div class="university">{{ $settings['institution_name'] ?? 'Universidad Nacional Agraria de la Selva' }}</div>
                    <div class="faculty">Facultad de Recursos Naturales Renovables</div>
                    <div class="divider"></div>

                    <div class="title">Certificado de Finalizacion</div>
                    <div class="label">Otorga el presente certificado a:</div>
                    <div class="student">{{ $user->name }}</div>
                    <div class="c-label">Por haber completado satisfactoriamente el curso de:</div>
                    <div class="c-name">{{ $curso->titulo }}</div>

                    <div class="details">
                        Fecha de culminacion: {{ $progresoCurso->updated_at->format('d \d\e F \d\e\l Y') }}<br>
                        Duracion: {{ $curso->horas }} horas academicas
                    </div>

                    <table class="sig-table" cellspacing="0" cellpadding="0">
                        <tr>
                            <td>
                                <div style="font-family: 'DejaVu Sans', cursive; font-size: 22px; color: #1a1a2e; margin-bottom: 3px; font-style: italic;">Dr. Jorge Rojas Garcia</div>
                                <div class="sig-line"></div>
                                <div class="sig-name">Rector</div>
                                <div class="sig-role">Universidad Nacional Agraria de la Selva</div>
                            </td>
                            <td>
                                <div style="font-family: 'DejaVu Sans', cursive; font-size: 22px; color: #1a1a2e; margin-bottom: 3px; font-style: italic;">Ing. Maria Torres Paredes</div>
                                <div class="sig-line"></div>
                                <div class="sig-name">Directora de la OTI</div>
                                <div class="sig-role">Oficina de Tecnologias de Informacion</div>
                            </td>
                        </tr>
                    </table>

                    <table style="width:100%;border-collapse:collapse;">
                        <tr>
                            <td style="text-align:left;vertical-align:bottom;">
                                <div class="footer">
                                    Codigo: <strong>{{ $codigo }}</strong><br>
                                    {{ $verifyUrl }}
                                </div>
                            </td>
                            <td style="width:85px;text-align:right;vertical-align:bottom;">
                                @if($qrCodeSvg)
                                    {!! $qrCodeSvg !!}
                                @endif
                            </td>
                        </tr>
                    </table>

                </div>
            </div>
        </div>
    </div>
</body>
</html>