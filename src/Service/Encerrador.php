<?php

namespace LeilaoAlura\Service;

use LeilaoAlura\Dao\Leilao as LeilaoDao;

class Encerrador
{
    private $dao;
    private $enviadorEmail;

    public function __construct(LeilaoDao $dao, EnviadorEmail $enviadorEmail)
    {
        $this->dao = $dao;
        $this->enviadorEmail = $enviadorEmail;
    }
    public function encerra()
    {
        $leiloes = $this->dao->recuperarNaoFinalizados();

        foreach ($leiloes as $leilao) {
            if ($leilao->temMaisDeUmaSemana()) {
               try {
                   $leilao->finaliza();
                   $this->dao->atualiza($leilao);
                   $this->enviadorEmail->notificadorTerminoLeilao($leilao);
               } catch (\DomainException $e) {
                   error_log($e->getMessage());
               }
            }
        }
    }
}
