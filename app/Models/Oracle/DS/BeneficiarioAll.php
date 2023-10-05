<?php

namespace App\Models\Oracle\DS;

use App\Models\Oracle\Adapter;

class BeneficiarioAll extends Adapter
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getBeneficiariosPag($ixmlFiltros, $pageNum, $pageSize, &$regTotal)
    {
        $par = (empty($ixmlFiltros) ? 'null' : sprintf("'%s'", $ixmlFiltros));
        $sql = sprintf("select DS_PF_BENEFICIARIO.f_getBrowse_pag(%s,  %d, %d) AS MFRC from dual", $par, $pageNum, $pageSize);

        $this->setSql($sql);

        $reg = $this->selectAll();
        $regTotal = $reg[0]['rowtotal'];
        return $reg;
    } // end getAllPagineo()

    public function getDatosPersonales($iidpersona)
    {
        $sql = sprintf("select DS_PF_BENEFICIARIO.f_getDatosPersonales(%d) AS MFRC from dual", $iidpersona);

        $this->setSql($sql);

        $reg = $this->selectOne();
        return $reg;
    }

    public function getServicios($ixmlFiltros)
    {
        $par = (empty($ixmlFiltros) ? 'null' : sprintf("'%s'", $ixmlFiltros));
        $sql = sprintf("select DS_PF_BENEFICIARIO.f_getServiciosXPersona(%s) AS MFRC from dual", $par);

        $this->setSql($sql);

        $reg = $this->selectAll();
        return $reg;
    }

    public function getPreguntas($iidpersona)
    {
        $sql = sprintf("select DS_PF_BENEFICIARIO.f_getPreguntas(%d) AS MFRC from dual", $iidpersona);

        $this->setSql($sql);

        $reg = $this->selectAll();
        return $reg;
    }
}
