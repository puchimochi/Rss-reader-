<?php

class AccountController extends Controller{

	public function signupAction(){
		return $this->render(
			array('_token'=>$this->generateCsrfToken('account/signup'),
				));
	}

	public function registerAction(){
		if(!$this->request->isPost()){
			$this->forward404();
		}

		$token = $this->request->getPost('_token');
		if(! $this->checkCsrfToken('account/signup',$token)){
			return $this->redirect('/account/signup');
		}

		$user_name = $this->request->getPost('user_name');
		$password = $this->request->getPost('password');
		$errors = array();

		//ユーザーIDのバリデーション
		if(! strlen($user_name)){
			$errors[] = 'ユーザーを入力してください。';
		} elseif (! preg_match('/^\w{3,20}$/', $user_name)) {
			$errors[] = 'ユーザーIDは半角英数字およびアンダースコアを3~20文字以内で入力してください。';
		} elseif (! $this->db_manager->get('User')->isUniqueUserName($user_name)) {
			$errors[] = 'ユーザーIDはすでに使用されています。';
		}

		//パスワードのバリデーション
		if(! strlen($password)){
			$errors[] = 'パスワードを入力してください。';
		} elseif ( 4 > strlen($password) || strlen($password) >30) {
			$errors[] = 'パスワードは4~30文字以内で入力してください。';
		}

		if (count($errors) === 0) {
			$this->db_manager->get('User')->insert($user_name,$password);
			$this->session->setAuthenticated(true);

			$user = $this->db_manager->get('User')->fetchByUserName($user_name);
			$this->session->set('user',$user);

			return $this->redirect('/');
		}

		return $this->render(array(
			'user_name' => "",
			'password' => "",
			'errors' => $errors,
			'_token' => $this->generateCsrfToken('account/signup'),
			),'signup');
	}

}