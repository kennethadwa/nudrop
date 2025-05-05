<x-app-layout>
    <x-slot name="header">
        <div>
            <x-registrar-header />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-9xl mx-auto sm:px-8 lg:px-12 xl:px-16">

            <div class="overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold">Documents</h3>
                    <a href="{{ route('addDocument') }}"
                        class="rounded bg-blue-600 px-4 py-2 text-sm text-white shadow-sm shadow-black hover:bg-blue-700">New
                        Document</a>
                </div>

                <!-- Search Bar -->
                <div class="mb-4">
                    <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search for documents..."
                        class="w-full rounded border border-gray-300 p-2">
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200 bg-white" id="documentsTable">
                        <thead>
                            <tr class="bg-gray-100 text-start text-xs font-semibold uppercase text-gray-600">
                                <th class="border px-4 py-2 text-start">Document Name</th>
                                <th class="border px-4 py-2 text-start">Fee</th>
                                <th class="border px-4 py-2 text-start">Date Created</th>
                                <th class="border px-4 py-2 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($documents as $document)
                                <tr class="text-sm">
                                    <td class="border px-4 py-2 text-start">{{ $document->document_name ?? '—' }}</td>
                                    <td class="border px-4 py-2 text-start">₱{{ number_format($document->fee, 2) }}
                                    </td>
                                    <td class="border px-4 py-2 text-start">
                                        {{ $document->created_at->format('F d, Y') ?? '—' }}
                                    </td>
                                    <td class="border px-4 py-2 text-start">
                                        <div class="flex flex-col items-center justify-center gap-2 sm:flex-row">
                                            <a href="{{ route('documents.edit', $document->id) }}"
                                                class="flex items-center gap-2 rounded bg-blue-500 px-3 py-1.5 text-white shadow-sm shadow-black transition hover:bg-blue-600">Edit</a>

                                            <form id="delete-form-{{ $document->id }}"
                                                action="{{ route('documents.destroy', $document->id) }}" method="POST"
                                                style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="confirmDelete({{ $document->id }})"
                                                    class="flex items-center gap-2 rounded bg-red-500 px-3 py-1.5 text-white shadow-sm shadow-black transition hover:bg-red-600">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="border px-4 py-2 text-center text-gray-500">No documents
                                        found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-6">
                        {{ $documents->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        function searchTable() {
            var input, filter, table, tr, td, i, j, txtValue;
            input = document.getElementById('searchInput');
            filter = input.value.toUpperCase();
            table = document.getElementById('documentsTable');
            tr = table.getElementsByTagName('tr');

            for (i = 1; i < tr.length; i++) {
                tr[i].style.display = 'none';
                td = tr[i].getElementsByTagName('td');
                for (j = 0; j < td.length; j++) {
                    if (td[j]) {
                        txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = '';
                            break;
                        }
                    }
                }
            }
        }
    </script>

</x-app-layout>

@if (session('success'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            background: '#35408F',
            color: '#fff',
            text: @json(session('success')),
            confirmButtonColor: '#11CB65',
            confirmButtonText: 'OK'
        });
    </script>
@endif


<script>
    function confirmDelete(docId) {
        Swal.fire({
            title: 'Are you sure you want to delete this document?',
            text: "This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            background: '#35408F',
            color: '#fff',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
        }).then((result) => {
            if (result.isConfirmed) {
                // If confirmed, submit the form to delete the document
                document.getElementById('delete-form-' + docId).submit();
            }
        });
    }
</script>
