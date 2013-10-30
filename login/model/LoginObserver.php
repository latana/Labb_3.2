<?php

namespace login\model;

/**
 * Callback interface
 */
interface LoginObserver {
	public function loginFailed();
	public function loginOK(TemporaryPasswordServer $info);
}

