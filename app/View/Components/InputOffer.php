<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InputOffer extends Component {

	public $type;
	public $label;
	public $name;
	public $placeholder;
	public $value;

	public function __construct($type = '', $label = '', $name = '', $placeholder = '', $value = '') {
		$this->type = $type;
		$this->label = $label;
		$this->placeholder = $placeholder;
		$this->value = $value;

		if (empty($name)) {
			$this->name = str_slug($label);
		} else {
			$this->name = $name;
		}
	}

	public function render() {
		return view('components.input_offer');
	}
}
