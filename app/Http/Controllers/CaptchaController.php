<?php

namespace App\Http\Controllers;

use App\Libraries\Captcha\SimpleCaptcha;

class CaptchaController extends Controller
{
    /**
     * Controlador para generar la imagen de CAPTCHA
     *
     * @return string
     */
    public function generarCaptcha()
    {
        $confs = $this->CaptchaConf();
        $image_data_base64 = $this->CrearImagen($confs);

        return response()->json([
            'captcha' => $image_data_base64,
            'text' => $confs->text,
        ]);
    }

    /**
     * Inicializa la configuración del CAPTCHA
     *
     * @return SimpleCaptcha
     */
    private function CaptchaConf()
    {
        $captcha_conf = new SimpleCaptcha();
        $captcha_conf->wordsFile = null;
        $captcha_conf->lineWidth = 3;
        $captcha_conf->scale = 4;
        $captcha_conf->blur = true;

        return $captcha_conf;
    }

    /**
     * Crea imagen del CAPTCHA
     *
     * @param SimpleCaptcha $simple_captcha
     * @return string
     */
    private function CrearImagen(SimpleCaptcha $simple_captcha)
    {
        // Image generation
        $img = $simple_captcha->GenerateImage();

        ob_start();

        imagejpeg($img);
        $image_data = ob_get_contents();

        ob_end_clean();

        return base64_encode($image_data);
    }
}
