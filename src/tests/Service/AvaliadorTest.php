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
        $leilao = new Leilao('Fiat 147 0km');

        $joao = new Usuario('Joao');
        $maria = new Usuario('Maria');
        $jose = new Usuario('José');

        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria, 3500));
        $leilao->recebeLance(new Lance($jose, 1700));

        $leiloeiro = new Avaliador();

        // Act - When
        $leiloeiro->avalia($leilao);

        $maiorValor = $leiloeiro->getMaiorValor();

        // Assert - Then

        self::assertEquals(3500, $maiorValor);
    }
}