<?php

namespace App\Http\Controllers;

use App\Mailerlite\Error;
use App\Services\MailerLiteService;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, MailerLiteService $mailerLite)
    {
        $draw = intval($request->input('draw', 1));
        $result = $mailerLite->getSubscribers(
            $request->input('search.value'),
            $request->input('start', 0),
            $request->input('length', 10)
        );

        if ($request->wantsJson()) {
            $payload = [
                'draw' => $draw,
                'recordsTotal' => $result->count,
                'recordsFiltered' => $result->count,
                'subscribers' => $result->records,
            ];

            if ($result instanceof Error) {
                $payload['error'] = $result->message;
            }

            return $payload;
        }

        $data = [
            'deferLoading' => $result->count,
            'subscribers' => $result->records,
        ];

        if ($result instanceof Error) {
            $data['error'] = $result->message;
        }

        return view('subscribers.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
