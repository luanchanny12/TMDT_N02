<x-layouts.admin title="Sửa FAQ" header="Sửa câu hỏi FAQ">
    <div class="max-w-2xl">
        <div class="bg-white rounded-xl shadow-sm border border-[#efe8e3] p-6">
            <form action="{{ route('admin.chatbot-faqs.update', $faq) }}" method="POST">
                @csrf @method('PUT')
                @include('admin.chatbot-faqs._form', ['faq' => $faq])
            </form>
        </div>
    </div>
</x-layouts.admin>
