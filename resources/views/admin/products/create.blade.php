<x-layouts.admin :title="'Thêm sản phẩm'" :header="'Thêm sản phẩm mới'">

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.products._form')
    </form>

</x-layouts.admin>
