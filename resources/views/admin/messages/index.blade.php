@extends('layouts.admin')

@section('title', 'Messages')
@section('heading', 'Messages')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Boîte de réception</h1>
            <p class="text-muted-tt mb-0">
                {{ $nonLus }} message(s) non lu(s) sur {{ $messages->total() }} affiché(s).
            </p>
        </div>
        <div class="btn-group">
            <a href="{{ route('admin.messages.index') }}" class="btn btn-light border {{ $filtre === '' ? 'active' : '' }}">Tous</a>
            <a href="{{ route('admin.messages.index', ['filtre' => 'non_lus']) }}" class="btn btn-light border {{ $filtre === 'non_lus' ? 'active' : '' }}">Non lus</a>
            <a href="{{ route('admin.messages.index', ['filtre' => 'lus']) }}" class="btn btn-light border {{ $filtre === 'lus' ? 'active' : '' }}">Lus</a>
        </div>
    </div>

    <div class="panel">
        <div class="table-responsive">
            <table class="table table-tt align-middle">
                <thead>
                    <tr>
                        <th>Expéditeur</th>
                        <th>Sujet</th>
                        <th>Reçu le</th>
                        <th>État</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $message)
                        <tr class="{{ $message->lu ? '' : 'fw-semibold' }}">
                            <td>
                                <div>{{ $message->nom }}</div>
                                <div class="text-small text-muted-tt fw-normal">{{ $message->email }}</div>
                            </td>
                            <td>
                                <a href="{{ route('admin.messages.show', $message) }}" class="text-reset">
                                    {{ \Illuminate\Support\Str::limit($message->sujet, 55) }}
                                </a>
                            </td>
                            <td class="text-small">{{ $message->created_at?->translatedFormat('j M Y · H:i') }}</td>
                            <td>
                                @if($message->lu)
                                    <span class="badge bg-secondary badge-status">Lu</span>
                                @else
                                    <span class="badge bg-warning text-dark badge-status">Nouveau</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('admin.messages.show', $message) }}" class="btn btn-light border btn-icon" title="Lire">
                                        <i class="bi bi-envelope-open"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.messages.destroy', $message) }}"
                                          onsubmit="return confirm('Supprimer ce message ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-icon" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted-tt py-4">Aucun message dans cette boîte.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $messages->links() }}</div>
@endsection
