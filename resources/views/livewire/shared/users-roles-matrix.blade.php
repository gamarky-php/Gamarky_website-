<div dir="rtl" class="space-y-6">
    <h2 class="text-2xl font-bold text-gray-900">👥 مصفوفة المستخدمين والأدوار</h2>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-sm text-green-800">✓ {{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
        <div class="grid grid-cols-2 gap-4">
            <input type="text" wire:model.live="search" placeholder="بحث بالاسم أو البريد..." class="rounded-lg border-gray-300">
            <select wire:model.live="filterRole" class="rounded-lg border-gray-300">
                <option value="">جميع الأدوار</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">المستخدم</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">البريد</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الأدوار</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $user->name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $user->email }}</td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-1">
                                @foreach($this->getUserRoles($user->id) as $role)
                                    <span class="px-2 py-1 text-xs font-semibold bg-indigo-100 text-indigo-800 rounded-full">{{ $role }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <button wire:click="openAssignModal({{ $user->id }})" class="px-3 py-1.5 bg-indigo-600 text-white rounded text-sm hover:bg-indigo-700">تعيين أدوار</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4">{{ $users->links() }}</div>
    </div>

    @if($showAssignModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75" wire:click="closeModal"></div>
                <div class="relative bg-white rounded-lg max-w-md w-full p-6">
                    <h3 class="text-xl font-bold mb-5">تعيين الأدوار</h3>
                    <form wire:submit.prevent="assignRoles" class="space-y-4">
                        @foreach($roles as $role)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="selectedRoles" value="{{ $role->id }}" class="w-5 h-5 text-indigo-600 border-gray-300 rounded">
                                <span class="text-sm font-medium text-gray-700">{{ $role->name }}</span>
                            </label>
                        @endforeach
                        <div class="flex justify-end gap-3 pt-4 border-t">
                            <button type="button" wire:click="closeModal" class="px-4 py-2 bg-gray-200 rounded-lg">إلغاء</button>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg">حفظ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
