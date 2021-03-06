<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Notification;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::latest()->paginate(20);
        return view('admin-views.notification.index', compact('notifications'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required',
            'description' => 'required',
            'image'       => 'required',
        ], [
            'title.required' => '¡El título es obligatorio!',
        ]);

        if (!empty($request->file('image'))) {
            $image_name = Carbon::now()->toDateString() . "-" . uniqid() . "." . 'png';
            if (!Storage::disk('public')->exists('notification')) {
                Storage::disk('public')->makeDirectory('notification');
            }
            $note_img = Image::make($request->file('image'))->stream();
            Storage::disk('public')->put('notification/' . $image_name, $note_img);
        } else {
            $image_name = 'def.png';
        }

        $notification = new Notification;
        $notification->title = $request->title;
        $notification->description = $request->description;
        $notification->image = $image_name;
        $notification->status = 1;
        $notification->save();
        // Helpers::send_push_notif_to_topic($notification);
        try {
            Helpers::send_push_notif_to_topic($notification);
        } catch (\Exception $e) {
            Toastr::warning('Falló la notificación push!');
        }

        Toastr::success('¡Notificación enviada con éxito!');
        return back();
    }

    public function edit($id)
    {
        $notification = Notification::find($id);
        return view('admin-views.notification.edit', compact('notification'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'       => 'required',
            'description' => 'required',
        ], [
            'title.required' => '¡El título es obligatorio!',
        ]);

        $notification = Notification::find($id);

        if (!empty($request->file('image'))) {
            $image_name = Carbon::now()->toDateString() . "-" . uniqid() . "." . 'png';
            if (!Storage::disk('public')->exists('notification')) {
                Storage::disk('public')->makeDirectory('notification');
            }
            if (Storage::disk('public')->exists('notification/' . $notification['image'])) {
                Storage::disk('public')->delete('notification/' . $notification['image']);
            }
            $note_img = Image::make($request->file('image'))->stream();
            Storage::disk('public')->put('notification/' . $image_name, $note_img);
        } else {
            $image_name = $notification['image'];
        }

        $notification->title = $request->title;
        $notification->description = $request->description;
        $notification->image = $image_name;
        $notification->save();
        Toastr::success('¡Notificación actualizada correctamente!');
        return back();
    }

    public function status(Request $request)
    {
        if ($request->ajax()) {
            $notification = Notification::find($request->id);
            $notification->status = $request->status;
            $notification->save();
            $data = $request->status;
            return response()->json($data);
        }
    }

    public function delete(Request $request)
    {
        $notification = Notification::find($request->id);
        if (Storage::disk('public')->exists('notification/' . $notification['image'])) {
            Storage::disk('public')->delete('notification/' . $notification['image']);
        }
        $notification->delete();
        return response()->json();

    }
}
