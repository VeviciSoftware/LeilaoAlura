<?php 

namespace Leilao\Tests\Service;

require 'vendor/autoload.php';

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use LeilaoAlura\Model\Leilao;
use LeilaoAlura\Model\Lance;
use LeilaoAlura\Model\Usuario;
use LeilaoAlura\Service\Avaliador;

class AvaliadorTest extends TestCase
{
    private $leiloeiro;
    public function criarAvaliador()
    {
        $this->leiloeiro = new Avaliador();
    }


    #[DataProvider('entregaLeiloes')]
    public function testAvaliadorDeveEncontrarMaiorValorDeLances(Leilao $leilao)
    {
        // Arrange - Given
        $leilao = $this->leilaoEmOrdemAleatoria();

        $this->criarAvaliador();

        // Act - When
        $this->leiloeiro->avalia($leilao);

        $maiorValor = $this->leiloeiro->getMaiorValor();

        // Assert - Then

        self::assertEquals(3500, $maiorValor);
    }

    public function testAvaliadorDeveEncontrarMenorValor()
    {
        // Arrange - Given
        $leilao = $this->leilaoEmOrdemAleatoria();

        $this->criarAvaliador();

        // Act - When
        $this->leiloeiro->avalia($leilao);

        $menorValor = $this->leiloeiro->getMenorValor();

        // Assert - Then

        self::assertEquals(1000, $menorValor);
    }

    public function testAvaliadorDeveEncontrarOsTresMaioresValores() 
    {
        $leilao = $this->leilaoEmOrdemAleatoria();

        $this->criarAvaliador();

        $this->leiloeiro->avalia($leilao);

        $maiores = $this->leiloeiro->getMaioresLances();

        static::assertCount(3, $maiores);
        static::assertEquals(3500, $maiores[0]->getValor());
        static::assertEquals(2500, $maiores[1]->getValor());
        static::assertEquals(2000, $maiores[2]->getValor());
    }

    //Métodos de criação de dados para os testes

    public static function leilaoEmOrdemCrescente() 
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

    public static function leilaoEmOrdemDecrescente() 
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

    public static function leilaoEmOrdemAleatoria() 
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

    public static function entregaLeiloes()
    {
        return [
            [self::leilaoEmOrdemAleatoria()],
            [self::leilaoEmOrdemCrescente()],
            [self::leilaoEmOrdemDecrescente()]
        ];
    }

}