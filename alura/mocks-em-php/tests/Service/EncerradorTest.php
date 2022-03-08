<?php

namespace Alura\Leilao\Tests\Service;

use Alura\Leilao\Dao\Leilao as LeilaoDao;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Service\Encerrador;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class EncerradorTest extends TestCase
{
	public function testLeiloesComMaisDeUmaSemanaDevemSerEncerrados()
	{
		// Cria o primeiro leilão
		$leilao1 = new Leilao(
			'Fiat 147 0KM',
			new \DateTimeImmutable('8 days ago')
		);

		// Cria o segundo leilão
		$leilao2 = new Leilao(
			'Variant 1972 0KM',
			new \DateTimeImmutable('10 days ago')
		);

		// Cria um mock da classe LeilaoDao
		$leilaoDAO = $this->createMock(LeilaoDao::class);

		// Imita o método "recuperarNaoFinalizados"
		$leilaoDAO->method('recuperarNaoFinalizados')
			->willReturn([$leilao1, $leilao2]);

		// Imita o método "recuperarFinalizados"
		$leilaoDAO->method('recuperarFinalizados')
			->willReturn([$leilao1, $leilao2]);

		// Espera que o método "atualiza" seja chamado uma vez
		// para cada leilão
		$leilaoDAO->expects($this->exactly(2))
			->method('atualiza')
			->withConsecutive(
				[$leilao1],
				[$leilao2]
			);

		// Encerra os leilões
		$encerrador = new Encerrador($leilaoDAO);
		$encerrador->encerra();

		// Busca os leilões finalizados
		$leiloesFinalizados = [$leilao1, $leilao2];

		// Testa se os leilões que foram finalizados estão corretos
		self::assertCount(2, $leiloesFinalizados);
		self::assertTrue($leiloesFinalizados[0]->estaFinalizado());
		self::assertTrue($leiloesFinalizados[1]->estaFinalizado());
	}
}