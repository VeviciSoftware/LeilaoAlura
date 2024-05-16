<?php

namespace LeilaoAlura\Model;

class Leilao
{
    /** @var Lance[] */
    private $lances;
    /** @var string */
    private $descricao;
    /** @var bool */
    private $finalizado;
    /** @var \DateTimeInterface  */
    private $dataInicio;
    /** @var int */
    private $id;

    public function __construct(string $descricao, \DateTimeImmutable $dataInicio = null, int $id = null)
    {
        $this->descricao = $descricao;
        $this->finalizado = false;
        $this->lances = [];
        $this->dataInicio = $dataInicio ?? new \DateTimeImmutable();
        $this->id = $id;
    }

    public function recebeLance(Lance $lance)
    {
        if ($this->finalizado) {
            throw new \DomainException('Este leilão já está finalizado');
        }
    
        $ultimoLance = empty($this->lances)
            ? null
            : $this->lances[count($this->lances) - 1];
        if (!empty($this->lances) && $ultimoLance->getUsuario() == $lance->getUsuario()) {
            throw new \DomainException('Usuário já deu o último lance');
        }
    
        $totalLancesUsuario = array_reduce(
            $this->lances,
            function (int $totalAcumulado, Lance $lanceAtual) use ($lance) {
                if ($lanceAtual->getUsuario() == $lance->getUsuario()) {
                    return $totalAcumulado + 1;
                }
                return $totalAcumulado;
            },
            0
        );
    
        if ($totalLancesUsuario >= 5) {
            throw new \DomainException('Usuário não pode dar mais de 5 lances por leilão');
        }
    
        $this->lances[] = $lance;
    }

    public function finaliza()
    {
        $this->finalizado = true;
    }

    /**
     * @return Lance[]
     */
    public function getLances(): array
    {
        return $this->lances;
    }

    public function recuperarDescricao(): string
    {
        return $this->descricao;
    }

    public function estaFinalizado(): bool
    {
        return $this->finalizado;
    }

    public function recuperarDataInicio(): \DateTimeInterface
    {
        return $this->dataInicio;
    }

    public function temMaisDeUmaSemana(): bool
    {
        $hoje = new \DateTime();
        $intervalo = $this->dataInicio->diff($hoje);

        return $intervalo->days > 7;
    }

    public function recuperarId(): int
    {
        return $this->id;
    }
}
