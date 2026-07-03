<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\ProgresoCurso;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CertificadoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function ver(Curso $curso)
    {
        $user = auth()->user();

        $progresoCurso = ProgresoCurso::where('user_id', $user->id)
            ->where('curso_id', $curso->id)
            ->where('completado', true)
            ->firstOrFail();

        $codigo = str_pad($progresoCurso->id, 6, '0', STR_PAD_LEFT);

        return view('certificado.ver', compact('curso', 'user', 'progresoCurso', 'codigo'));
    }

    public function descargar(Curso $curso)
    {
        $user = auth()->user();

        $progresoCurso = ProgresoCurso::where('user_id', $user->id)
            ->where('curso_id', $curso->id)
            ->where('completado', true)
            ->firstOrFail();

        $codigo = str_pad($progresoCurso->id, 6, '0', STR_PAD_LEFT);

        $settings = \App\Models\PageSetting::getAll();

        if (!empty($settings['logo'])) {
            $logoPath = storage_path('app/public/' . $settings['logo']);
        } else {
            $logoPath = public_path('images/unas-logo.png');
        }
        $logoSrc = file_exists($logoPath)
            ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
            : '';

        $firmaRectorSrc = '';
        $firmaRectorPath = public_path('images/firma-rector.png');
        if (file_exists($firmaRectorPath)) {
            $firmaRectorSrc = 'data:image/png;base64,' . base64_encode(file_get_contents($firmaRectorPath));
        }

        $firmaDirectorSrc = '';
        $firmaDirectorPath = public_path('images/firma-director.png');
        if (file_exists($firmaDirectorPath)) {
            $firmaDirectorSrc = 'data:image/png;base64,' . base64_encode(file_get_contents($firmaDirectorPath));
        }

        $verifyUrl = url('/verificar/' . $codigo);
        $qrCodeSvg = null;
        try {
            $qrCodeSvg = QrCode::format('svg')->size(90)->generate($verifyUrl);
            $qrCodeSvg = preg_replace('/^<\?xml.*?\?>/', '', $qrCodeSvg);
            $qrCodeSvg = preg_replace('/<svg /', '<svg width="75" height="75" ', $qrCodeSvg);
        } catch (\Exception $e) {
            $qrCodeSvg = null;
        }

        $pdf = PDF::loadView('certificado.pdf', compact(
            'curso', 'user', 'progresoCurso', 'codigo', 'settings', 'logoSrc',
            'qrCodeSvg', 'verifyUrl', 'firmaRectorSrc', 'firmaDirectorSrc'
        ));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('certificado-' . $codigo . '.pdf');
    }
}
