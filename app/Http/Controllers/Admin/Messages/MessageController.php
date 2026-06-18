<?php

namespace App\Http\Controllers\Admin\Messages;

use App\Http\Controllers\Controller;
use App\Models\PortfolioMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(Request $request): View
    {
        $filter = in_array($request->query('filter'), ['unread', 'read'], true)
            ? $request->query('filter')
            : 'all';

        $query = PortfolioMessage::query()->latest('id');

        if ($filter === 'unread') {
            $query->where('is_read', false);
        } elseif ($filter === 'read') {
            $query->where('is_read', true);
        }

        $messages = $query->paginate(15)->withQueryString();

        return view('admin.messages.index', [
            'messages' => $messages,
            'unreadCount' => PortfolioMessage::where('is_read', false)->count(),
            'totalCount' => PortfolioMessage::count(),
            'readCount' => PortfolioMessage::where('is_read', true)->count(),
            'filter' => $filter,
        ]);
    }

    public function toggleRead(PortfolioMessage $message): RedirectResponse
    {
        $message->update(['is_read' => ! $message->is_read]);

        return back()->with('status', $message->is_read ? 'Ditandai sudah dibaca.' : 'Ditandai belum dibaca.');
    }

    public function destroy(PortfolioMessage $message): RedirectResponse
    {
        $message->delete();

        return redirect()->route('admin.messages.index')->with('status', 'Pesan dihapus.');
    }

    public function unreadCount(): JsonResponse
    {
        return response()->json([
            'unread' => PortfolioMessage::where('is_read', false)->count(),
        ]);
    }
}
