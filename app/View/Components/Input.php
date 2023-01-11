<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Input extends Component {

	public $type;
	public $label;
	public $name;
	public $placeholder;

	public function __construct($type = '', $label = '', $name = '', $placeholder = '') {
		$this->type = $type;
		$this->label = $label;
		$this->placeholder = $placeholder;

		if (empty($name)) {
			$this->name = str_slug($label);
		} else {
			$this->name = $name;
		}
	}

	public function render() {
		return view('components.input');
	}
}
