<?php

namespace LeilaoAlura\Service;

use LeilaoAlura\Dao\Leilao as LeilaoDao;

class Encerrador
{
    private $dao;

    public function __construct(LeilaoDao $dao)
    {
        $this->dao = $dao;
    }
    public function encerra()
    {
        $leiloes = $this->dao->recuperarNaoFinalizados();

        foreach ($leiloes as $leilao) {
            if ($leilao->temMaisDeUmaSemana()) {
                $leilao->finaliza();
                $this->dao->atualiza($leilao);
            }
        }
    }
}
