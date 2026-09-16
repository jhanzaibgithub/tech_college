@extends('admin.layout')

@section('title', 'Banners')

@section('content')
    <section class="admin-card">
        <div class="admin-card-head">
            <div><h2>Homepage Banners</h2><p>Active banners appear in the hero carousel. Lower display numbers appear first.</p></div>
            <a class="admin-button" href="{{ route('admin.banners.create') }}"><i data-lucide="plus"></i> Add Banner</a>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>Order</th><th>Preview</th><th>Title</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse ($banners as $banner)
                        <tr>
                            <td>{{ $banner->sort_order }}</td>
                            <td><img class="admin-thumb" src="{{ $banner->imageUrl() }}" alt="{{ $banner->title }}"></td>
                            <td>{{ $banner->title }}</td>
                            <td>{{ $banner->is_active ? 'Active' : 'Hidden' }}</td>
                            <td class="admin-actions">
                                <a href="{{ route('admin.banners.edit', $banner) }}" aria-label="Edit {{ $banner->title }}"><i data-lucide="pencil"></i></a>
                                <form method="POST" action="{{ route('admin.banners.destroy', $banner) }}" data-confirm="This banner and its image will be deleted.">
                                    @csrf @method('DELETE')
                                    <button type="submit" aria-label="Delete {{ $banner->title }}"><i data-lucide="trash-2"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5">No banners yet. Add your first banner to replace the default homepage image.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
