@extends('layouts.app')
@section('title', 'Уведомления')
@section('mode', 'linen')

@section('content')

    <section class="py-10 px-6">
        <div class="workspace" style="max-width: 780px;">

            <div class="flex items-center justify-between mb-8">
                <div>
                    <div class="divider mb-2" style="justify-content: flex-start;"><span>личный кабинет</span></div>
                    <h1 class="text-4xl">Уведомления</h1>
                </div>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <form action="{{ route('notifications.read-all') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-ghost">
                            Прочитать все
                        </button>
                    </form>
                @endif
            </div>

            @forelse($notifications as $notification)
                @php
                    $data = $notification->data;
                    $isRead = $notification->read_at !== null;
                @endphp

                <div class="card p-5 mb-3 flex gap-4 reveal"
                     style="{{ !$isRead ? 'border-left: 3px solid var(--brown);' : '' }}">

                    {{-- Иконка типа --}}
                    <div class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center"
                         style="background: {{
                         $data['type'] === 'reply' ? 'rgba(61,79,51,0.15)' :
                         ($data['type'] === 'service_contact' ? 'rgba(184,136,88,0.15)' : 'rgba(107,138,92,0.15)')
                     }}; min-width: 40px;">
                    <span class="text-xs font-medium uppercase"
                          style="letter-spacing: 1px; color: {{
                              $data['type'] === 'reply' ? 'var(--forest-light)' :
                              ($data['type'] === 'service_contact' ? 'var(--brown)' : 'var(--success)')
                          }};">
                        {{ $data['type'] === 'reply' ? 'ФМ' : ($data['type'] === 'service_contact' ? 'УС' : 'НВ') }}
                    </span>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3 mb-1">
                        <span class="text-sm font-medium" style="color: var(--text-primary);">
                            {{ $data['title'] }}
                        </span>
                            @if(!$isRead)
                                <span class="badge badge-brown" style="font-size: 9px; padding: 2px 8px;">Новое</span>
                            @endif
                        </div>
                        <p class="text-sm text-secondary-c leading-relaxed mb-2">
                            {{ $data['message'] }}
                        </p>
                        <div class="flex items-center gap-4 text-xs text-muted-c">
                            <span>{{ $notification->created_at->diffForHumans() }}</span>
                            @if(isset($data['url']))
                                <a href="{{ route('notifications.read', $notification->id) }}"
                                   class="text-brown hover:underline uppercase tracking-widest"
                                   style="letter-spacing: 2px;">
                                    Перейти →
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Удалить --}}
                    <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" class="flex-shrink-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="text-muted-c hover:text-brown transition text-lg"
                                style="background: transparent; border: none; cursor: pointer;"
                                title="Удалить">×</button>
                    </form>
                </div>
            @empty
                <div class="card-flat py-16 text-center">
                    <p class="text-secondary-c">Уведомлений нет</p>
                </div>
            @endforelse

            @if($notifications->hasPages())
                <div class="mt-8">{{ $notifications->links() }}</div>
            @endif

        </div>
    </section>

@endsection
