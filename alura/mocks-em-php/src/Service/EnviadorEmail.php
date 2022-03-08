<?php

namespace Alura\Leilao\Service;

class EnviadorEmail
{
	public function notificarTerminoLeilao(Leilao $leilao = null)
	{
		$sucesso = mail(
			'usuario@email.com',
			'Leilao finalizado',
			'O leilao para ' . $leilao->recuperarDescricao() . 'foi finalizado.'
		);

		if (!$sucesso) {
			throw new \DomainException('Erro ao enviar e-mail');
		}
	}
}