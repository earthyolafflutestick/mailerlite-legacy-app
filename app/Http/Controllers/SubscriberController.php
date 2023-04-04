<?php

namespace App\Http\Controllers;

use App\Mailerlite\Error;
use App\Services\MailerLiteService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

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
        return view('subscribers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, MailerLiteService $mailerLite)
    {
        $result = $mailerLite->createSubscriber(
            $request->input('email'),
            $request->input('name'),
            $request->input('country')
        );

        if ($result instanceof Error) {
            return redirect()
                ->route('subscribers.create')
                ->withInput($request->only(['email', 'name', 'country']))
                ->withErrors(Arr::flatten($result->details->errors));
        }

        session()->flash('success', __('mailerlite.messages.subscriber_created'));

        return redirect()->route('subscribers.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, MailerLiteService $mailerLite, $id)
    {
        $result = $mailerLite->getSubscriber($id);

        if ($result instanceof Error) {
            abort(404);
        }

        return view('subscribers.edit', [
            'subscriber' => $result->records[0],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MailerLiteService $mailerLite, $id)
    {
        $result = $mailerLite->updateSubscriber(
            $id,
            $request->input('name'),
            $request->input('country')
        );

        if ($result instanceof Error) {
            return redirect()
                ->route('subscribers.edit', $id)
                ->withErrors(Arr::flatten($result->details->errors));
        }

        session()->flash('success', __('mailerlite.messages.subscriber_updated'));

        return redirect()->route('subscribers.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(MailerLiteService $mailerLite, $id)
    {
        $result = $mailerLite->deleteSubscriber($id);
        $response = [
            'code' => $result instanceof Error ? $result->code : 201,
            'notice' => $result instanceof Error ?
                view('partials.error')->withErrors(['error' => $result->message])->render() :
                view('partials.success', ['message' => __('mailerlite.messages.subscriber_deleted')])->render()
        ];

        return response()->json($response);
    }
}
