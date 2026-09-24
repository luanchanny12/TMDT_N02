<x-layouts.admin title="Audit Logs">
    <div class="p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Nhật ký giao dịch (Audit Log)</h1>

            <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="flex flex-wrap gap-2">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="IP / tên / email..."
                       class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">

                <select name="action" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="">Tất cả action</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>
                            {{ $action }}
                        </option>
                    @endforeach
                </select>

                <button type="submit"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">
                    Lọc
                </button>
            </form>
        </div>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Thời gian</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Entity</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Metadata</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-900 whitespace-nowrap">
                                {{ $log->created_at?->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900">
                                {{ $log->user?->name ?? '—' }}
                                @if($log->user)
                                    <div class="text-xs text-gray-400">{{ $log->user->email }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold
                                    {{ str_contains($log->action, 'failed') || str_contains($log->action, 'blocked') || $log->action === 'review_rejected'
                                        ? 'bg-red-100 text-red-800'
                                        : 'bg-green-100 text-green-800' }}">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $log->entity_type }}{{ $log->entity_id ? '#'.$log->entity_id : '' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $log->ip_address ?? '—' }}</td>
                            <td class="px-4 py-3 text-xs text-gray-500 max-w-xs truncate"
                                title="{{ $log->metadata ? json_encode($log->metadata, JSON_UNESCAPED_UNICODE) : '' }}">
                                {{ $log->metadata ? json_encode($log->metadata, JSON_UNESCAPED_UNICODE) : '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                Chưa có nhật ký giao dịch.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $logs->links() }}
        </div>
    </div>
</x-layouts.admin>
