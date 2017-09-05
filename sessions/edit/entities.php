<?php

class Volcasession
{
	public $id;
	public $naam = 'leeg';
	public $plaatjePad = 'leeg';
	public $audioPad = 'default';
	public $datumTitel = 'default';
	public $beschrijving = 'leeg';
	public $boomFactor = 'Ambient';
	public $tempo = 0;
	public $datumToegevoegd;

	public function fillWithoutId($datumTitel, $audioPad)
	{
		$this->Init($datumTitel, $audioPad, date("Y-m-d"));
	}

	public function fillWithId($id, $datumTitel, $audioPad, $datumToegevoegd)
	{
		$this->id = $id;
		$this->Init($datumTitel, $audioPad, $datumToegevoegd);
	}

	private function Init($datumTitel, $audioPad, $datumToegevoegd)
	{
		$this->datumTitel = $datumTitel;
		$this->audioPad = $audioPad;
		$this->datumToegevoegd = $datumToegevoegd;
	}
}

class Boomfactor
{
	public $id;
	public $naam;
}

?>