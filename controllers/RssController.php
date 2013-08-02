<?php

class RssController extends Controller
{
	public function indexAction()
	{
		/**
		if (!$this->request->isPost()) {
			$this->forward404();
		}

		$token = $this->request->getPost('_token');
		if(! $this->checkCsrfToken('rss/add',$token)){
			return $this->redirect('/');
		}
*/

		$url = 'http://feed.pixnet.net/blog/posts/rss/haruhii';
		$data = simplexml_load_file($url);
		$title = $data->channel->title;

		foreach ($data->channel->item as $item ) {

				$entryTitle = $item->title;

		}


		$count = count($entryTitle);

		return $this->render(array(
			'data' => $data,
			'title' => $title,
			//'link' => $link,
			'entryTitle' => $entryTitle,
			'count' => $count,
			//'_token' =>$this->generateCsrfToken('rss/add'),
			));
	}

	public function addAction()
	{

	}
}