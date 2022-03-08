<?php

namespace Alura\Leilao\Tests\Service;

use Alura\Leilao\Dao\Leilao as LeilaoDao;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Service\Encerrador;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * Cria um imitador (mock) da classe LeilaoDao
 * para não acessar o banco de dados, e sim uma 
 * imitação da tabela de leilões (private $leiloes).
 * Desta forma, apenas os métodos do serviço são testados.
 */
class LeilaoDaoMock extends LeilaoDao
{
	// Imita a tabela de leilões
	private $leiloes = [];

	// Imita o método "salva" de LeilaoDao
	public function salva(Leilao $leilao): void
	{
		$this->leiloes[] = $leilao;
	}

	// Imita o método "recuperarFinalizados" de LeilaoDao
	public function recuperarFinalizados(): array
	{
		return array_filter($this->leiloes, function (Leilao $leilao) {
			return $leilao->estaFinalizado();
		});
	}

	// Imita o método "recuperarNaoFinalizados" de LeilaoDao
	public function recuperarNaoFinalizados(): array
	{
		return array_filter($this->leiloes, function (Leilao $leilao) {
			return !$leilao->estaFinalizado();
		});
	}

	// Imita o método "atualiza" de LeilaoDao
	public function atualiza(Leilao $leilao) {}
}

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

		// Salva os leilões
		$leilaoDAO = new LeilaoDaoMock();
		$leilaoDAO->salva($leilao1);
		$leilaoDAO->salva($leilao2);

		// Encerra os leilões
		$encerrador = new Encerrador($leilaoDAO);
		$encerrador->encerra();

		// Busca os leilões finalizados
		$leiloesFinalizados = $leilaoDAO->recuperarFinalizados();

		// Testa se os leilões que foram finalizados estão corretos
		self::assertCount(2, $leiloesFinalizados);
		self::assertEquals(
			'Fiat 147 0KM',
			$leiloesFinalizados[0]->recuperarDescricao()
		);
		self::assertEquals(
			'Variant 1972 0KM',
			$leiloesFinalizados[1]->recuperarDescricao()
		);
	}
}