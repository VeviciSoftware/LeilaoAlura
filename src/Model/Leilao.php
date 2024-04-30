<?php

namespace LeilaoAlura\Model;

class Leilao
{
    /** @var Lance[] */
    private $lances;
    /** @var string */
    private $descricao;

    public function __construct(string $descricao)
    {
        $this->descricao = $descricao;
        $this->lances = [];
    }

    public function recebeLance(Lance $lance)
    {
        // Verifica se o último usuário é o mesmo que está tentando dar o lance (não pode dar dois lances seguidos
        if(!empty($this->lances) && $this->ehDoUltimoUsuario($lance)) {
            throw new \DomainException('Usuário não pode propor dois lances seguidos');
        }

        //Se o usuário já deu 5 lances, não pode dar mais lances
        $totalLancesPorUsuario = $this->totalDeLancesPorUsuario();
        $nomeUsuario = $lance->getUsuario()->getNome();
        if(!empty($totalLancesPorUsuario[$nomeUsuario]) && $totalLancesPorUsuario[$nomeUsuario] >= 5) {
            throw new \DomainException('Usuário não pode dar mais de 5 lances por leilão');
        }

        $this->lances[] = $lance;
    }

    public function totalDeLancesPorUsuario() {
        $totalLancesPorUsuario = [];
        foreach ($this->lances as $lance) {
            $nomeUsuario = $lance->getUsuario()->getNome();
            if(!isset($totalLancesPorUsuario[$nomeUsuario])) {
                $totalLancesPorUsuario[$nomeUsuario] = 1;
            } else {
                $totalLancesPorUsuario[$nomeUsuario]++;
            }
        }
        return $totalLancesPorUsuario;
    }

    private function ehDoUltimoUsuario(Lance $lance): bool
    {
        $ultimoLance = $this->lances[array_key_last($this->lances)];
        return $lance->getUsuario() == $ultimoLance->getUsuario();
    }

    /**
     * @return Lance[]
     */
    public function getLances(): array
    {
        return $this->lances;
    }
}
