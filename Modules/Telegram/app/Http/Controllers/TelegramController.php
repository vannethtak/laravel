<?php

namespace Modules\Telegram\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $print)
    {
        try {
            $response = Telegram::bot('mybot')->getMe();
            $chatId = '5034837799';
            $message = $print ?? 'Hello, world!';
            $sendMessage = Telegram::bot('mybot')->sendMessage([
                'chat_id' => $chatId,
                'text' => $message,
            ]);

            return response()->json([
                'bot' => $response,
                'message_status' => $sendMessage,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('telegram::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('telegram::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('telegram::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
