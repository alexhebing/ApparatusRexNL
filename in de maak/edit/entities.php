<?php

class Entry
{
	public $id;

	public function getId()
	{
		return $this->id;
	}

	public $naam = 'default';
	public $pad = 'default';
	public $datumToegevoegd;

	public function fillWithoutId($naam, $pad)
	{
		$this->Init($naam, $pad, date("Y-m-d"));
	}

	public function fillWithId($id, $naam, $pad, $datumToegevoegd)
	{
		$this->id = $id;
		$this->Init($naam, $pad, $datumToegevoegd);
	}

	private function Init($naam, $pad, $datumToegevoegd)
	{
		$this->naam = $naam;
		$this->pad = $pad;
		$this->datumToegevoegd = $datumToegevoegd;
	}

	public function toString()
	{
		return $this->id . $this->naam . $this->pad . $this->datumToegevoegd . "\n"; 
	}
}

?>