<?php

class AccountController extends Controller{

	protected $auth_actions = array('index','signout','follow');

	public function signupAction(){
		return $this->render(
			array(
				'user_name' => '',
				'password' => '',
				'_token'=>$this->generateCsrfToken('account/signup'),
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
			'user_name' => '',
			'password' => '',
			'errors' => $errors,
			'_token' => $this->generateCsrfToken('account/signup'),
			),'signup');
	}

	//アカウント情報管理
	public function indexAction(){
		$user = $this->session->get('user');
		$followings = $this->db_manager->User->fetchAllFollowingsByUserId($user['id']);
		return $this->render(array(
			'user' => $user,
			'followings' => $followings
			));
	}

	//ログインメソッド
	public function signinAction(){

		if($this->session->isAuthenticated()){
			return $this->redirect('/account');
		}

		return $this->render(array(
			'user_name' => '',
			'password' => '',
			'_token' => $this->generateCsrfToken('account/signin'),
			));
	}

	//ログアウトメソッド
	public function signoutAction(){
		$this->session->clear();
		$this->session->setAuthenticated(false);

		return $this->redirect('/account/signin');
	}

	//ログイン認証メソッド
	public function authenticateAction()
	{
		if($this->session->isAuthenticated()){
			return $this->redirect('/account');
		}

		if(! $this->request->isPost()){
			$this->forward404();
		}

		$token = $this->request->getPost('_token');
		if (! $this->checkCsrfToken('account/signin',$token)) {
			return $this->redirect('/account/signin');
		}

		$errors=array();
		$user_name = $this->request->getPost('user_name');
		$password = $this->request->getPost('password');

		if(! strlen($user_name)){
			$errors[] = 'ユーザーIDを入力してください。';
		}

		if(! strlen($password)){
			$errors[] = 'パスワードを入力してください。';
		}

		if(count($errors) === 0){
			$user_repository = $this->db_manager->get('User');
			$user = $user_repository->fetchByUserName($user_name);

			if (! $user || ($user['password'] !== $user_repository->hashPassword($password))) {
				$errors[] = 'ユーザーIDかパスワードが不正です。';
			}else {
				$this->session->setAuthenticated(true);
				$this->session->set('user',$user);

				return $this->redirect('/');
			}
		}

		return $this->render(array(
			'user_name' => $user_name,
			'password' => $password,
			'errors' => $errors,
			'_token' => $this->generateCsrfToken('account/signin'),
			),'signin');
	}

	public function followAction(){
		if (!$this->request->isPost()) {
			$this->forward404();
		}

		$following_name = $this->request->getPost('following_name');
		if (! $following_name) {
			$this->forward404();
		}

		$token = $this->request->getPost('_token');
		if(! $this->checkCsrfToken('account/follow',$token)){
			return $this->redirect('/user/'.$following_name);
		}

		$following_user = $this->db_manager->get('User')->fetchByUserName($following_name);
		if (! $following_user) {
			$this->forward404();
		}

		$user = $this->session->get('user');

		$following_repository = $this->db_manager->get('Following');
		if($user['id'] !== $following_user['id'] && !($following_repository->isFollowing($user['id'],$following_user['id']))){
			$following_repository->insert($user['id'],$following_user['id']);
		}

		return $this->redirect('/account');



	}

}