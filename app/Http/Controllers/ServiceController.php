<?php

namespace App\Http\Controllers;

use App\Http\Requests\Service\CreateServiceRequest;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Notifications\ServiceContact;

class ServiceController extends Controller
{
    public function index(Request $request): View
    {
        $query = Service::approved()->with(['user', 'media'])->latest();

        if ($category = $request->input('category')) {
            $query->where('service_category', $category);
        }
        if ($region = $request->input('region')) {
            $query->where('region', 'like', '%' . $region . '%');
        }

        $services  = $query->paginate(12)->withQueryString();
        $regions   = Service::approved()->whereNotNull('region')->distinct()->orderBy('region')->pluck('region');
        $categories = Service::categories();

        return view('services.index', compact('services', 'regions', 'categories', 'category', 'region'));
    }

    public function show(Service $service): View
    {
        abort_unless($service->is_approved && $service->is_active, 404);
        $service->load(['user', 'media']);
        $related = Service::approved()->where('id', '!=', $service->id)->where('service_category', $service->service_category)->with('media')->take(3)->get();
        return view('services.show', compact('service', 'related'));
    }

    public function create(): View
    {
        abort_unless(auth()->check(), 403);
        $categories = Service::categories();
        return view('services.create', compact('categories'));
    }

    public function store(CreateServiceRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $service = Service::create([
            'title'            => $validated['title'],
            'description'      => $validated['description'],
            'service_category' => $validated['service_category'],
            'price'            => $validated['price'] ?? null,
            'price_unit'       => $validated['price_unit'] ?? null,
            'price_negotiable' => $request->boolean('price_negotiable'),
            'region'           => $validated['region'],
            'city'             => $validated['city'] ?? null,
            'phone'            => $validated['phone'] ?? null,
            'user_id'          => auth()->id(),
            'is_approved'      => false,
            'is_active'        => true,
        ]);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $service->addMedia($photo)->toMediaCollection('photos');
            }
        }

        return redirect()->route('services.index')
            ->with('success', 'Услуга отправлена на модерацию. После проверки она появится в каталоге.');
    }

    public function contact(\Illuminate\Http\Request $request, \App\Models\Service $service): \Illuminate\Http\RedirectResponse
    {
        abort_unless(auth()->check(), 403);

        $request->validate([
            'message' => ['required', 'string', 'min:10', 'max:1000'],
        ], [
            'message.required' => 'Введите сообщение',
            'message.min'      => 'Сообщение слишком короткое',
        ]);

        $service->user->notify(new \App\Notifications\ServiceContact(
            $service,
            auth()->user(),
            $request->message
        ));

        return back()->with('success', 'Ваше сообщение отправлено исполнителю');
    }

    public function destroy(Service $service): RedirectResponse
    {
        abort_unless(auth()->id() === $service->user_id, 403);
        $service->delete();
        return back()->with('success', 'Услуга удалена');
    }
}
