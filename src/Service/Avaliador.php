<?php

namespace LeilaoAlura\Service;
use LeilaoAlura\Model\Leilao;

class Avaliador {

    private $maiorValor;
    public function avalia(Leilao $leilao) : void
    {
        foreach ($leilao->getLances() as $lance) {
            if ($lance->getValor() > $this->maiorValor) {
                $this->maiorValor = $lance->getValor();
            }
        }
    }

    public function getMaiorValor() : float
    {
        return $this->maiorValor;
    }
}