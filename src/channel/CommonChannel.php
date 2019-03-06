<?php

namespace Kppool\Channel;


class CommonChannel {
	protected $project;

	public function __construct($project) {
		$this->project = $project;
	}

	public function getProject() {
		return $this->project;
	}
}