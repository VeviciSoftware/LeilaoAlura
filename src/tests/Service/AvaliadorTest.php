<?php 

namespace Leilao\Tests\Service;

require 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use LeilaoAlura\Model\Leilao;
use LeilaoAlura\Model\Lance;
use LeilaoAlura\Model\Usuario;
use LeilaoAlura\Service\Avaliador;

class AvaliadorTest extends TestCase
{
    public function testAvaliadorDeveEncontrarMaiorValor()
    {
        // Arrange - Given
        $leilao = $this->leilaoEmOrdemAleatoria();

        $leiloeiro = new Avaliador();

        // Act - When
        $leiloeiro->avalia($leilao);

        $maiorValor = $leiloeiro->getMaiorValor();

        // Assert - Then

        self::assertEquals(3500, $maiorValor);
    }

    public function testAvaliadorDeveEncontrarMenorValor()
    {
        // Arrange - Given
        $leilao = $this->leilaoEmOrdemAleatoria();

        $leiloeiro = new Avaliador();

        // Act - When
        $leiloeiro->avalia($leilao);

        $menorValor = $leiloeiro->getMenorValor();

        // Assert - Then

        self::assertEquals(1000, $menorValor);
    }

    public function testAvaliadorDeveEncontrarOMaiorValorDeLancesEmOrdemCrescente()
    {
        // Arrange - Given
        $leilao = $this->leilaoEmOrdemCrescente();

        $leiloeiro = new Avaliador();

        // Act - When
        $leiloeiro->avalia($leilao);

        $maiorValor = $leiloeiro->getMaiorValor();

        // Assert - Then
        self::assertEquals(3500, $maiorValor);
    }

    public function testAvaliadorDeveEncontrarOMaiorValorDeLancesEmOrdemDecrescente()
    {
        // Arrange - Given
        $leilao = $this->leilaoEmOrdemDecrescente();

        $leiloeiro = new Avaliador();

        // Act - When
        $leiloeiro->avalia($leilao);

        $maiorValor = $leiloeiro->getMaiorValor();

        // Assert - Then
        self::assertEquals(3500, $maiorValor);
    }

    public function testAvaliadorDeveEncontrarOMenorValorDeLancesEmOrdemDecrescente()
    {
        // Arrange - Given
        $leilao = $this->leilaoEmOrdemDecrescente();

        $leiloeiro = new Avaliador();

        // Act - When
        $leiloeiro->avalia($leilao);

        $menorValor = $leiloeiro->getMenorValor();

        // Assert - Then
        self::assertEquals(1000, $menorValor);
    }

    public function testAvaliadorDeveEncontrarOMenorValorDeLancesEmOrdemCrescente()
    {
        // Arrange - Given
        $leilao = $this->leilaoEmOrdemCrescente();

        $leiloeiro = new Avaliador();

        // Act - When
        $leiloeiro->avalia($leilao);

        $menorValor = $leiloeiro->getMenorValor();

        // Assert - Then
        self::assertEquals(1000, $menorValor);
    }

    public function testAvaliadorDeveEncontrarOsTresMaioresValores() 
    {
        $leilao = $this->leilaoEmOrdemAleatoria();

        $leiloeiro = new Avaliador();

        $leiloeiro->avalia($leilao);

        $maiores = $leiloeiro->getMaioresLances();

        static::assertCount(3, $maiores);
        static::assertEquals(3500, $maiores[0]->getValor());
        static::assertEquals(2500, $maiores[1]->getValor());
        static::assertEquals(2000, $maiores[2]->getValor());
    }

    //Métodos de criação de dados para os testes

    public function leilaoEmOrdemCrescente() 
    {
        $leilao = new Leilao('Fiat 147 0km');

        $joao = new Usuario('Joao');
        $maria = new Usuario('Maria');
        $jose = new Usuario('José');
        $ana = new Usuario('Ana');
        $jorge = new Usuario('Jorge');

        $leilao->recebeLance(new Lance($ana, 1000));
        $leilao->recebeLance(new Lance($jose, 1700));
        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($jorge, 2500));
        $leilao->recebeLance(new Lance($maria, 3500));

        return $leilao;
    }

    public function leilaoEmOrdemDecrescente() 
    {
        $leilao = new Leilao('Fiat 147 0km');

        $joao = new Usuario('Joao');
        $maria = new Usuario('Maria');
        $jose = new Usuario('José');
        $ana = new Usuario('Ana');
        $jorge = new Usuario('Jorge');

        $leilao->recebeLance(new Lance($maria, 3500));
        $leilao->recebeLance(new Lance($jorge, 2500));
        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($jose, 1700));
        $leilao->recebeLance(new Lance($ana, 1000));

        return $leilao;
    }

    public function leilaoEmOrdemAleatoria() 
    {
        $leilao = new Leilao('Fiat 147 0km');

        $joao = new Usuario('Joao');
        $maria = new Usuario('Maria');
        $jose = new Usuario('José');
        $ana = new Usuario('Ana');
        $jorge = new Usuario('Jorge');

        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria, 3500));
        $leilao->recebeLance(new Lance($jose, 1700));
        $leilao->recebeLance(new Lance($ana, 1000));
        $leilao->recebeLance(new Lance($jorge, 2500));

        return $leilao;
    }

}