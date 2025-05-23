@extends('Admin.layouts.aside')
@section('title', 'RUBI Admin - Articles')

@section('content')
    <main class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold">Articles</h1>
            <p class="text-sm text-gray-500">Manage official publications in the RUBI application</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <!-- Total Articles -->
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex justify-between items-center mb-2">
                    <h2 class="text-sm font-medium">Total Articles</h2>
                    <div class="text-gray-500">
                        <i class="fas fa-file-alt"></i>
                    </div>
                </div>
                <div class="text-2xl font-bold">{{ $stats['total'] }}</div>
                <p class="text-xs text-gray-500">All registered articles</p>
            </div>

            <!-- Published -->
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex justify-between items-center mb-2">
                    <h2 class="text-sm font-medium">Published</h2>
                    <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-semibold text-green-700">
                    {{ $stats['published'] }}
                </span>
                </div>
                <div class="text-2xl font-bold">{{ $stats['published_percentage'] }} %</div>
                <p class="text-xs text-gray-500">Active publications</p>
            </div>

            <!-- Drafts -->
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex justify-between items-center mb-2">
                    <h2 class="text-sm font-medium">Drafts</h2>
                    <span class="inline-flex items-center rounded-full bg-yellow-50 px-2.5 py-0.5 text-xs font-semibold text-yellow-700">
                    {{ $stats['draft'] }}
                </span>
                </div>
                <div class="text-2xl font-bold">{{ $stats['draft_percentage'] }} %</div>
                <p class="text-xs text-gray-500">In progress</p>
            </div>

            <!-- Archived -->
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex justify-between items-center mb-2">
                    <h2 class="text-sm font-medium">Archived</h2>
                    <span class="inline-flex items-center rounded-full bg-gray-50 px-2.5 py-0.5 text-xs font-semibold text-gray-700">
                    {{ $stats['archived'] }}
                </span>
                </div>
                <div class="text-2xl font-bold">{{ $stats['archived_percentage'] }} %</div>
                <p class="text-xs text-gray-500">Inactive publications</p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-white rounded-lg border border-gray-200 mb-6">
            <div class="p-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold">Article Management</h2>
                        <p class="text-sm text-gray-500">View and manage articles registered in the RUBI application</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.articles.create') }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-black text-white hover:bg-gray-800 h-9 px-3">
                            <i class="fas fa-plus mr-2"></i>
                            Add Article
                        </a>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="p-4 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4">
                <form action="{{ route('admin.articles.index') }}" method="GET" class="relative w-full sm:w-64">
                    <i class="fas fa-search absolute left-2.5 top-2.5 h-4 w-4 text-gray-500"></i>
                    <input
                        type="search"
                        name="search"
                        placeholder="Search for an article..."
                        value="{{ request('search') }}"
                        class="flex h-10 w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-gray-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 pl-8"
                    />
                    @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}" />
                    @endif
                </form>
                <div class="flex gap-2">
                    <a href="{{ route('admin.articles.index', ['search' => request('search')]) }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-gray-200 bg-white hover:bg-gray-50 h-9 px-4 py-2 {{ !request('status') ? 'bg-gray-100' : '' }}">
                        All
                    </a>
                    <a href="{{ route('admin.articles.index', ['status' => 'published', 'search' => request('search')]) }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-gray-200 bg-white hover:bg-gray-50 h-9 px-4 py-2 {{ request('status') == 'published' ? 'bg-gray-100' : '' }}">
                        Published
                    </a>
                    <a href="{{ route('admin.articles.index', ['status' => 'draft', 'search' => request('search')]) }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-gray-200 bg-white hover:bg-gray-50 h-9 px-4 py-2 {{ request('status') == 'draft' ? 'bg-gray-100' : '' }}">
                        Drafts
                    </a>
                    <a href="{{ route('admin.articles.index', ['status' => 'archived', 'search' => request('search')]) }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-gray-200 bg-white hover:bg-gray-50 h-9 px-4 py-2 {{ request('status') == 'archived' ? 'bg-gray-100' : '' }}">
                        Archived
                    </a>
                </div>
            </div>
            <!-- Articles Grid -->
            <div class="p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($articles as $article)
                        <!-- Article Card -->
                        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden relative">
                            <!-- Clickable area covering the entire card except the dropdown button -->
                            <a href="{{ route('admin.articles.show', $article->id) }}" class="absolute inset-0 z-10" style="cursor: pointer;"></a>

                            <div class="p-4 pb-2">
                                <div class="flex justify-between items-start">
                                    @if($article->status == 'published')
                                        <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-semibold text-green-700">
                                Published
                            </span>
                                    @elseif($article->status == 'draft')
                                        <span class="inline-flex items-center rounded-full bg-yellow-50 px-2.5 py-0.5 text-xs font-semibold text-yellow-700">
                                Draft
                            </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-gray-50 px-2.5 py-0.5 text-xs font-semibold text-gray-700">
                                Archived
                            </span>
                                    @endif
                                    <div class="relative z-20">
                                        <button class="text-gray-500 hover:text-gray-700 action-btn p-1" onclick="toggleDropdown(this)">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div class="dropdown w-48 bg-white rounded-md shadow-lg border border-gray-200">
                                            <div class="py-1">
                                                <div class="px-4 py-2 text-base font-medium border-b border-gray-200">Actions</div>
                                                <a href="{{ route('admin.articles.show', $article->id) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    <i class="fas fa-eye mr-2"></i> View Details
                                                </a>
                                                <a href="{{ route('admin.articles.edit', $article->id) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    <i class="fas fa-edit mr-2"></i> Edit
                                                </a>
                                                <button class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-gray-100 w-full text-left" onclick="openDeleteDialog('{{ $article->id }}')">
                                                    <i class="fas fa-trash-alt mr-2"></i> Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h3 class="text-lg font-semibold mt-2">{{ $article->title }}</h3>
                            </div>
                            <div class="p-4 pt-0">
                                <div class="flex items-center gap-2 text-sm text-gray-500 mb-3">
                                    <i class="fas fa-calendar-alt h-4 w-4"></i>
                                    <span>{{ $article->date->format('d/m/Y') }}</span>
                                </div>
                                <p class="text-sm text-gray-500 line-clamp-3 mb-3">
                                    {{ Str::limit(strip_tags($article->content), 150) }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 p-8 text-center">
                            <p class="text-gray-500">No articles found.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-between p-4 border-t border-gray-200">
                <div class="text-sm text-gray-500">
                    @if($articles->count() > 0)
                        @if($articles instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            Showing {{ $articles->firstItem() }} to {{ $articles->lastItem() }} of {{ $articles->total() }} articles
                        @else
                            Showing {{ $articles->count() }} article(s)
                        @endif
                    @else
                        No articles found
                    @endif
                </div>
                @if($articles instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="flex items-center space-x-2">
                        @if($articles->onFirstPage())
                            <button disabled class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-gray-200 bg-white hover:bg-gray-50 h-8 w-8 p-0 opacity-50">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                        @else
                            <a href="{{ $articles->previousPageUrl() }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-gray-200 bg-white hover:bg-gray-50 h-8 w-8 p-0">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif

                        <span class="text-sm">{{ $articles->currentPage() }} of {{ $articles->lastPage() }}</span>

                        @if($articles->hasMorePages())
                            <a href="{{ $articles->nextPageUrl() }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-gray-200 bg-white hover:bg-gray-50 h-8 w-8 p-0">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <button disabled class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-gray-200 bg-white hover:bg-gray-50 h-8 w-8 p-0 opacity-50">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        @endif
                    </div>
                @endif
            </div>

        </div>

        <!-- Delete Confirmation Dialog -->
        <div class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50" id="deleteDialog">
            <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
                <div class="p-6">
                    <h2 class="text-lg font-semibold">Confirm Deletion</h2>
                    <p class="text-sm text-gray-500 mt-2">
                        Are you sure you want to delete this article? This action cannot be undone.
                    </p>
                </div>
                <div class="flex items-center justify-end gap-2 p-4 border-t border-gray-200">
                    <button class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-gray-200 bg-white hover:bg-gray-50 h-9 px-4" onclick="document.getElementById('deleteDialog').classList.add('hidden')">
                        Cancel
                    </button>
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-red-600 text-white hover:bg-red-700 h-9 px-4">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <style>
        .dropdown {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            z-index: 30;
        }

        .dropdown.show {
            display: block;
        }

        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>

    <script>
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.action-btn') && !event.target.closest('.dropdown')) {
                const dropdowns = document.querySelectorAll('.dropdown');
                dropdowns.forEach(dropdown => {
                    dropdown.classList.remove('show');
                });
            }
        });

        function toggleDropdown(button) {
            const allDropdowns = document.querySelectorAll('.dropdown');
            allDropdowns.forEach(dropdown => {
                if (dropdown !== button.nextElementSibling) {
                    dropdown.classList.remove('show');
                }
            });

            const dropdown = button.nextElementSibling;
            dropdown.classList.toggle('show');

            event.stopPropagation();
        }

        function openDeleteDialog(id) {
            document.getElementById('deleteForm').action = `/admin/articles/${id}`;
            document.getElementById('deleteDialog').classList.remove('hidden');
        }
    </script>
@endsection
