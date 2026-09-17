<x-layouts.admin title="Thêm FAQ" header="Thêm câu hỏi FAQ">
    <div class="max-w-2xl">
        <div class="bg-white rounded-xl shadow-sm border border-[#efe8e3] p-6">
            <form action="{{ route('admin.chatbot-faqs.store') }}" method="POST">
                @csrf
                @include('admin.chatbot-faqs._form')
            </form>
        </div>
    </div>
</x-layouts.admin>
