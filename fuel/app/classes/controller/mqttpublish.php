<?php

class Controller_Mqttpublish extends Controller
{
    public $_viewBasePath = 'mqttpublish/';

	public function action_index()
	{
        $viewPath = $this->_viewBasePath . 'index';
		return Response::forge(View::forge($viewPath));
	}

	public function action_hello()
	{
        $viewPath = $this->_viewBasePath . 'hello';
		return Response::forge(Presenter::forge($viewPath));
	}

	public function action_404()
	{
        $viewPath = $this->_viewBasePath . '404';
		return Response::forge(Presenter::forge($viewPath), 404);
	}
}
