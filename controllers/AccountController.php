<?php

class AccountController extends Controller{

	public function signupAction(){
		return $this->render(
			array('_token'=>$this->generateCsrfToken('account/signup'),
				));
	}

	public function register(){
		return $this->render(
			array('_token'=>$this->generateCsrfToken('account/register'),
				));

	}
}