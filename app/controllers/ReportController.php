<?php

class ReportController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('reports.index');
	}

	public function getChanges($data, $spot_address)
	{
		if($spot_address == 0)
		{
			echo $data::orderBy('created_at', 'DESC')->take(50)->get();
		}
		else
		{
			echo $data::orderBy('created_at', 'DESC')->where('spot_address', '=', $spot_address)->take(50)->get();
		}
	}
}
