<?php

namespace App\Http\Controllers\Ds;

use App\Http\Controllers\Controller;
use App\Libraries\Utils\UtilsArray;
use App\Models\Oracle\DS\BeneficiarioAll;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class BeneficiarioController extends Controller
{

    public function BeneficiariosPag(Request $req)
    {
        $totalRegistros = 0;
        // $req->validate([]);
        $filtros = [
            'idtipoidentificacion'    => [$req->input('idtipoidentificacion')],
            'numdocumento'            => [$req->input('numdocumento')],
            'nombre'                  => [$req->input('nombre')],
            'idprovincia'             => [$req->input('idprovincia')],
            'idcanton'                => [$req->input('idcanton')],
            'idparroquia'             => [$req->input('idparroquia')],
            'fechainicio'             => [$req->input('fechainicio')],
            'fechafin'                => [$req->input('fechafin')],
            'idservicio'              => [$req->input('idservicio')],
            'idestructura'            => [$req->input('idestructura')],
            'idprovinciaServicio'     => [$req->input('idprovinciaServicio')],
            'idcantonServicio'        => [$req->input('idcantonServicio')],
            'idparroquiaBeneficiario' => [$req->input('idparroquiaBeneficiario')],
        ];
        $xmlFiltros    = UtilsArray::array2xml($filtros, '', 'numdocumento', 'filtro');
        $oBeneficiario = new BeneficiarioAll;
        $rs = $oBeneficiario->getBeneficiariosPag($xmlFiltros, 1, 10, $totalRegistros);

        if ($oBeneficiario->getMsjAlerta() != '') return response()->json(['ok' => false, 'error' => $oBeneficiario->getMsjAlerta()], Response::HTTP_BAD_REQUEST);

        return response()->json(['ok' => true, 'data' => $rs, 'totalRegistros' => $totalRegistros], Response::HTTP_OK);
    }

    public function BeneficiarioDatos(Request $req)
    {
        try {
            $this->validate($req, ['idpersona' => 'required|numeric',]);
        } catch (ValidationException $e) {
            return response()->json(['ok' => false, 'error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        $oBeneficiario = new BeneficiarioAll;
        $rs = $oBeneficiario->getDatosPersonales($req->input('idpersona'));

        if ($oBeneficiario->getMsjAlerta() != '') return response()->json(['ok' => false, 'error' => $oBeneficiario->getMsjAlerta()], Response::HTTP_BAD_REQUEST);

        return response()->json(['ok' => true, 'data' => $rs], Response::HTTP_OK);
    }

    public function BeneficiarioServicios(Request $req)
    {
        try {
            $this->validate($req, ['idpersona' => 'required|numeric',]);
        } catch (ValidationException $e) {
            return response()->json(['ok' => false, 'error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        $xmlFiltros    = UtilsArray::array2xml(['idpersona' => [$req->input('idpersona')]], '', 'idpersona', 'filtro');
        $oBeneficiario = new BeneficiarioAll;
        $rs = $oBeneficiario->getServicios($xmlFiltros);

        if ($oBeneficiario->getMsjAlerta() != '') return response()->json(['ok' => false, 'error' => $oBeneficiario->getMsjAlerta()], Response::HTTP_BAD_REQUEST);

        return response()->json(['ok' => true, 'data' => $rs], Response::HTTP_OK);
    }

    public function BeneficiarioPreguntas(Request $req)
    {
        try {
            $this->validate($req, ['idpersona' => 'required|numeric',]);
        } catch (ValidationException $e) {
            return response()->json(['ok' => false, 'error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        $oBeneficiario = new BeneficiarioAll;
        $rs = $oBeneficiario->getPreguntas($req->input('idpersona'));

        if ($oBeneficiario->getMsjAlerta() != '') return response()->json(['ok' => false, 'error' => $oBeneficiario->getMsjAlerta()], Response::HTTP_BAD_REQUEST);

        return response()->json(['ok' => true, 'data' => $rs], Response::HTTP_OK);
    }
}
